<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

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
        $posts = Post::latest()->paginate(8);
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
            'image' => 'nullable|image|max:51200',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        $data['user_id'] = Auth::id();

        Post::create($data);
        Redis::del('post:all');

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show(string $id)
    {
        $views = Redis::incr("post:{$id}:views");
        $post = Post::with(['user', 'comments.user'])->findOrFail($id);
        $likes = (int) Redis::get("post:{$id}:likes");
        $likedPosts = session('liked_posts', []);
        $isLiked = in_array((int) $id, $likedPosts, true);

        return view('posts.show', compact('post', 'views', 'likes', 'isLiked'));
    }

    public function toggleLike(string $id)
    {
        $post = Post::findOrFail($id);
        $likedPosts = session('liked_posts', []);
        $postId = (int) $post->id;
        $liked = false;

        if (in_array($postId, $likedPosts, true)) {
            $likedPosts = array_values(array_filter($likedPosts, fn ($likedId) => (int) $likedId !== $postId));
            Redis::decr("post:{$postId}:likes");
        } else {
            $likedPosts[] = $postId;
            $likedPosts = array_values(array_unique($likedPosts));
            Redis::incr("post:{$postId}:likes");
            $liked = true;
        }

        session(['liked_posts' => $likedPosts]);

        $likes = max(0, (int) Redis::get("post:{$postId}:likes"));

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
            'image' => 'nullable|image|max:51200',
        ]);

        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        } else {
            $data['image'] = $post->image;
        }

        $post->update($data);
        Redis::del('post:all');

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorizePostAccess($post);

        $post->delete();
        Redis::del('post:all');

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
