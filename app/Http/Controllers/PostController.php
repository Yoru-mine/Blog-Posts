<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Services\ImgbbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    protected const VISIBILITIES = ['public', 'friends', 'private'];

    protected function authorizePostAccess(Post $post): void
    {
        $user = Auth::user();

        abort_unless(
            $user && ($user->id === $post->user_id || $user->isAdmin()),
            403
        );
    }

    public function index(Request $request)
    {
        $query = Post::with('user')
            ->withCount('comments')
            ->where('is_published', true);

        $categorySlug = $request->string('category')->toString();
        $tagSlug = $request->string('tag')->toString();


        if ($categorySlug !== '') {
            $query->whereIn('category_id', DB::table('categories')->where('slug', $categorySlug)->select('id'));
        }

        if ($tagSlug !== '') {
            $query->whereIn('id', DB::table('post_tag')
                ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                ->where('tags.slug', $tagSlug)
                ->select('post_tag.post_id'));
        }

        $posts = $this->applyVisibility($query)
            ->latest()
            ->paginate(20);

        $draftsCount = auth()->check()
            ? Post::where('user_id', auth()->id())->where('is_published', false)->count()
            : null;

        return view('posts.index', compact('posts', 'draftsCount'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePostRequest $request, ImgbbService $imgbb)
    {
        $data = $request->validated();
        unset($data['image'], $data['tags'], $data['is_published']);

        $data['user_id'] = auth()->id();
        $data['is_published'] = true;

        $extra = $request->validate([
            'visibility' => ['nullable', Rule::in(self::VISIBILITIES)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'max:24'],
            'draft_id' => ['nullable', 'integer'],
        ]);

        $data['visibility'] = $extra['visibility'] ?? 'public';
        $data['category_id'] = $extra['category_id'];
        $tagNames = $extra['tags'] ?? [];

        $draft = ! empty($extra['draft_id'])
            ? Post::where('user_id', auth()->id())->where('is_published', false)->find($extra['draft_id'])
            : null;

        if ($request->hasFile('image')) {
            try {
                $data['image'] = $imgbb->upload($request->file('image'));
            } catch (\Exception $e) {
                return back()
                    ->withInput()
                    ->withErrors(['image' => 'Не удалось загрузить картинку на фотохостинг. Попробуйте позже.']);
            }
        }

        DB::transaction(function () use ($data, $tagNames, $draft) {
            if ($draft) {
                $draft->update($data);
                $post = $draft;
            } else {
                $post = Post::create($data);
            }

            $this->syncTags($post, $tagNames);
        });

        if ($draft) {
            $this->forgetPostCache($draft->id);
        }

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created success!');
    }

    public function saveDraft(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:20000'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'max:24'],
            'visibility' => ['nullable', Rule::in(self::VISIBILITIES)],
        ]);

        $tagNames = $data['tags'] ?? [];

        unset($data['tags']);

        $data['title'] = $data['title'] ?? '';
        $data['content'] = $data['content'] ?? '';
        $data['visibility'] = $data['visibility'] ?? 'public';
        $data['user_id'] = auth()->id();
        $data['is_published'] = false;

        $post = DB::transaction(function () use ($data, $tagNames) {
            $post = Post::create($data);

            $this->syncTags($post, $tagNames);

            return $post;
        });

        return response()->json([
            'message' => 'Draft created',
            'post_id' => $post->id,
        ], 201);
    }

    public function updateDraft(Request $request, string $id)
    {
        $post = Post::where('user_id', auth()->id())
            ->where('is_published', false)
            ->findOrFail($id);

        $data = $request->validate([
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'content' => ['sometimes', 'nullable', 'string', 'max:20000'],
            'category_id' => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'tags' => ['sometimes', 'nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'max:24'],
            'visibility' => ['sometimes', 'nullable', Rule::in(self::VISIBILITIES)],
        ]);

        $tagNames = array_key_exists('tags', $data) ? ($data['tags'] ?? []) : null;

        unset($data['tags']);

        foreach (['title', 'content'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] === null) {
                $data[$field] = '';
            }
        }

        if (array_key_exists('visibility', $data) && $data['visibility'] === null) {
            $data['visibility'] = 'public';
        }

        DB::transaction(function () use ($post, $data, $tagNames) {
            $post->update($data);

            if ($tagNames !== null) {
                $this->syncTags($post, $tagNames);
            }
        });

        $this->forgetPostCache($post->id);

        return response()->json([
            'message' => 'Draft saved',
            'post_id' => $post->id,
        ]);
    }

    public function drafts()
    {
        $posts = Post::where('user_id', auth()->id())
            ->where('is_published', false)
            ->latest('updated_at')
            ->paginate(12);

        return view('posts.drafts', compact('posts'));
    }

    public function publish(string $id)
    {
        $post = Post::where('user_id', auth()->id())
            ->where('is_published', false)
            ->findOrFail($id);

        if (trim((string) $post->title) === '' || trim((string) $post->content) === '' || ! $post->category_id) {
            return redirect()
                ->route('posts.edit', $post->id)
                ->withErrors(['title' => 'Fill in the title, text and category before publishing.']);
        }

        $post->update(['is_published' => true]);

        $this->forgetPostCache($post->id);

        return redirect()->route('posts.index')->with('success', 'Post published!');
    }

    public function show(string $id)
    {
        $post = Cache::remember("posts:show:{$id}", 600, function () use ($id) {
            return Post::with(['user', 'comments.user'])->findOrFail($id);
        });

        abort_unless($this->canView($post), 404);

        $this->registerView($post->id);

        $views = (int) DB::table('posts')->where('id', $post->id)->value('views');
        $likes = $post->likedByUsers()->count();

        $similarIds = Cache::remember("posts:similar:v2:{$id}", 600, function () use ($post) {
            $words = explode(' ', str_replace(['-', '_', '/', '\\'], ' ', $post->title));
            $words = array_slice(array_unique(array_filter($words, fn($w) => mb_strlen($w) > 2)), 0, 5);

            if (empty($words)) {
                return [];
            }

            return Post::where('id', '!=', $post->id)
                ->where('is_published', true)
                ->where('visibility', 'public')
                ->where(function ($query) use ($words) {
                    foreach ($words as $word) {
                        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $word);
                        $query->orWhere('title', 'LIKE', "%{$escaped}%");
                    }
                })
                ->latest()
                ->take(4)
                ->pluck('id')
                ->all();
        });

        $similarPosts = empty($similarIds)
            ? collect()
            : Post::whereIn('id', $similarIds)
            ->where('is_published', true)
            ->where('visibility', 'public')
            ->latest()
            ->get(['id', 'title', 'image']);

        $isLiked = auth()->check()
            ? $post->likedByUsers()->where('user_id', auth()->id())->exists()
            : false;

        return view('posts.show', compact('post', 'views', 'likes', 'isLiked', 'similarPosts'));
    }

    protected function forgetPostCache(int|string $id): void
    {
        Cache::forget("posts:show:{$id}");
        Cache::forget("posts:similar:v2:{$id}");
    }

    protected function canView(Post $post): bool
    {
        return $post->isVisibleTo(auth()->user());
    }

    protected function applyVisibility($query)
    {
        return $query->visibleTo(Auth::user());
    }

    protected function deleteRemoteImage(ImgbbService $imgbb, ?string $url): void
    {
        if (! $url) {
            return;
        }

        try {
            $imgbb->delete($url);
        } catch (\Throwable $e) {
            report($e);
        }
    }



    protected function syncTags(Post $post, array $names): void
    {
        $ids = [];

        foreach ($names as $name) {
            $name = mb_strtolower(trim((string) $name));
            $slug = Str::slug($name);

            if ($name === '' || $slug === '') {
                continue;
            }

            $ids[] = Tag::firstOrCreate(['slug' => $slug], ['name' => $name])->id;
        }

        $post->tags()->sync(array_unique($ids));
    }

    protected function registerView(int $postId): void
    {
        $viewer = auth()->check() ? 'user:' . auth()->id() : 'ip:' . request()->ip();

        if (Cache::add("post:{$postId}:viewed:{$viewer}", true, now()->addDay())) {
            DB::table('posts')->where('id', $postId)->increment('views');
        }
    }



    public function toggleLike(string $id)
    {
        if (! auth()->check()) {
            return request()->expectsJson()
                ? response()->json(['error' => 'Unauthorized'], 401)
                : redirect()->route('login');
        }

        $post = Post::findOrFail($id);
        abort_unless($this->canView($post), 404);

        $result = $post->likedByUsers()->toggle(auth()->id());
        $liked = count($result['attached']) > 0;
        $likesCount = $post->likedByUsers()->count();

        if (request()->expectsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes' => $likesCount,
            ]);
        }

        return back();
    }

    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, string $id, ImgbbService $imgbb)
    {
        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:20000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120|dimensions:max_width=6000,max_height=6000',
            'visibility' => ['required', Rule::in(self::VISIBILITIES)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'max:24'],
        ]);

        $tagNames = $validated['tags'] ?? [];
        unset($validated['tags']);

        if ($request->boolean('publish') && ! $post->is_published) {
            $validated['is_published'] = true;
        }

        $oldImage = null;

        if ($request->hasFile('image')) {
            try {
                $validated['image'] = $imgbb->upload($request->file('image'));
                $oldImage = $post->image;
            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Ошибка фотохостинга. Изменения не сохранены, попробуйте позже.']);
            }
        } else {
            unset($validated['image']);
        }

        DB::transaction(function () use ($post, $validated, $tagNames) {
            $post->update($validated);

            $this->syncTags($post, $tagNames);
        });

        $this->deleteRemoteImage($imgbb, $oldImage);
        $this->forgetPostCache($id);

        return redirect()->route('posts.index')->with('success', 'Пост обновлен!');
    }

    public function destroy(string $id, ImgbbService $imgbb)
    {
        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);

        $image = $post->image;

        $post->delete();

        $this->deleteRemoteImage($imgbb, $image);
        $this->forgetPostCache($id);

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (mb_strlen($query) < 2 || mb_strlen($query) > 50) {
            return response()->json([]);
        }

        $cacheKey = 'posts:search:v2:' . md5(mb_strtolower($query));

        $ids = Cache::remember($cacheKey, 300, function () use ($query) {
            $escapedQuery = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $query);

            return Post::where('is_published', true)
                ->where(function ($q) use ($escapedQuery) {
                    $q->where('title', 'LIKE', "%{$escapedQuery}%")
                        ->orWhere('content', 'LIKE', "%{$escapedQuery}%");
                })
                ->latest()
                ->take(30)
                ->pluck('id')
                ->all();
        });

        $results = empty($ids)
            ? collect()
            : $this->applyVisibility(Post::whereIn('id', $ids)->where('is_published', true))
            ->latest()
            ->take(6)
            ->get(['id', 'title', 'content', 'image'])
            ->map(fn($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'excerpt' => Str::limit($post->content, 80),
                'image' => $post->image ?: asset('images/default.png'),
                'url' => route('posts.show', $post->id),
            ]);

        return response()->json($results);
    }
}
