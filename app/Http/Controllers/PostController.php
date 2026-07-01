<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected function authorizePostAccess(Post $post): void
    {
        $user = Auth::user();

        abort_unless(
            $user && ($user->id === $post->user_id || $user->isAdmin()),
            403
        );
    }

    public function index()
    {
        $posts = Post::latest()->paginate(20);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 's3');

            if ($path === false || $path === '') {
                \Illuminate\Support\Facades\Log::error('S3 FAIL: store() вернул false - проверь ключи и endpoint');
            } else {
                $data['image'] = $path;
                \Illuminate\Support\Facades\Log::info('S3 OK: ' . $path);
            }
        }

        $data['user_id'] = auth()->id();

        Post::create($data);

        \Illuminate\Support\Facades\Cache::flush();

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show(string $id)
    {
        $post = \Illuminate\Support\Facades\Cache::remember("posts:show:{$id}", 600, function () use ($id) {
            return Post::with(['user', 'comments.user'])->findOrFail($id);
        });

        $views = 0;

        $likes = 0;

        $similarPosts = \Illuminate\Support\Facades\Cache::remember("posts:similar:{$id}", 600, function () use ($post) {
            $words = explode(' ', str_replace(['-', '_', '/', '\\'], ' ', $post->title));
            $words = array_filter($words, fn($w) => mb_strlen($w) > 2);

            if (empty($words)) {
                return collect();
            }

            $query = Post::where('id', '!=', $post->id);
            foreach ($words as $word) {
                $query->orWhere('title', 'LIKE', "%{$word}%");
            }

            return $query->latest()->take(4)->get(['id', 'title', 'image']);
        });

        $likedPosts = session('liked_posts', []);
        $isLiked = in_array((int) $id, $likedPosts, true);

        return view('posts.show', compact('post', 'views', 'likes', 'isLiked', 'similarPosts'));
    }
    public function toggleLike(string $id)
    {
        $post = Post::findOrFail($id);
        $likedPosts = session('liked_posts', []);
        $postId = (int) $post->id;
        $liked = false;

        if (in_array($postId, $likedPosts, true)) {
            $likedPosts = array_values(array_filter($likedPosts, fn($likedId) => (int) $likedId !== $postId));
            try {
                Redis::decr("post:{$postId}:likes");
            } catch (\Exception $e) {
                Log::warning('Redis unavailable in toggleLike(decr): ' . $e->getMessage());
            }
        } else {
            $likedPosts[] = $postId;
            $likedPosts = array_values(array_unique($likedPosts));
            try {
                Redis::incr("post:{$postId}:likes");
            } catch (\Exception $e) {
                Log::warning('Redis unavailable in toggleLike(incr): ' . $e->getMessage());
            }
            $liked = true;
        }

        session(['liked_posts' => $likedPosts]);

        try {
            $likes = max(0, (int) Redis::get("post:{$postId}:likes"));
        } catch (\Exception $e) {
            $likes = 0;
        }

        if (request()->expectsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes' => $likes,
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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('', 's3');
            $data['image'] = $path;
        } else {
            $data['image'] = $post->image;
        }

        $post->update($data);

        \Illuminate\Support\Facades\Cache::flush();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);

        $post->delete();
        \Illuminate\Support\Facades\Cache::flush();

        // Мы полностью убрали блок try/catch с Redis, который вызывал ошибку 500
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = \Illuminate\Support\Facades\Cache::remember('posts:search:' . mb_strtolower($query), 300, function () use ($query) {
            $posts = Post::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->latest()
                ->take(6)
                ->get(['id', 'title', 'content', 'image']);

            return $posts->map(fn($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'excerpt' => \Illuminate\Support\Str::limit($post->content, 80),
                'image' => $post->image ? Storage::disk('s3')->url($post->image) : asset('images/default.png'),
                'url' => route('posts.show', $post->id),
            ]);
        });

        return response()->json($results);
    }
}
