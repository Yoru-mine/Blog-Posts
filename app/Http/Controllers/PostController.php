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
        $request->validate(['title' => 'required', 'content' => 'required', 'image' => 'nullable|image']);
        $data = $request->except('image');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $response = \Illuminate\Support\Facades\Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
                'image' => base64_encode(file_get_contents($request->file('image')->getRealPath())),
            ]);
            $data['image'] = $response->json()['data']['url'] ?? null;
        }
        Post::create($data);
        return redirect()->route('posts.index');
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
        $post = Post::findOrFail($id);
        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $response = \Illuminate\Support\Facades\Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
                'image' => base64_encode(file_get_contents($request->file('image')->getRealPath())),
            ]);
            $data['image'] = $response->json()['data']['url'] ?? $post->image;
        }
        $post->update($data);
        return redirect()->route('posts.index');
    }
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);

        $post->delete();
        \Illuminate\Support\Facades\Cache::flush();

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
                'image' => $post->image ?: asset('images/default.png'),
                'url' => route('posts.show', $post->id),
            ]);
        });

        return response()->json($results);
    }
}
