<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\ImgbbService;
use Illuminate\Support\Facades\Cache;

class ApiPostController extends Controller
{
    private function canManage(Post $post): bool
    {
        $user = request()->user();

        return $user && ($user->id === $post->user_id || $user->isAdmin());
    }


    private function viewer()
    {
        return request()->user('sanctum');
    }

    private function forgetCache(int|string $id): void
    {
        Cache::forget("posts:show:{$id}");
        Cache::forget("posts:similar:v2:{$id}");
    }

    private function deleteRemoteImage(ImgbbService $imgbb, ?string $url): void
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

    public function index()
    {
        $posts = Post::published()
            ->visibleTo($this->viewer())
            ->latest()
            ->paginate(5);

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request, ImgbbService $imgbb)
    {
        $data = $request->validated();
        unset($data['image']);
        $data['user_id'] = $request->user()->id;
        $data['is_published'] = true;
        $data['visibility'] = 'public';

        if ($request->hasFile('image')) {
            try {
                $data['image'] = $imgbb->upload($request->file('image'));
            } catch (\Exception $e) {
                return response()->json(['message' => 'Image upload failed'], 502);
            }
        }

        $post = Post::create($data);

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (! $post || ! $post->isVisibleTo($this->viewer())) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return new PostResource($post);
    }

    public function update(StorePostRequest $request, $id, ImgbbService $imgbb)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if (! $this->canManage($post)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validated();
        unset($data['image']);

        $oldImage = null;

        if ($request->hasFile('image')) {
            try {
                $data['image'] = $imgbb->upload($request->file('image'));
                $oldImage = $post->image;
            } catch (\Exception $e) {
                return response()->json(['message' => 'Image upload failed'], 502);
            }
        }

        $post->update($data);

        $this->deleteRemoteImage($imgbb, $oldImage);
        $this->forgetCache($post->id);

        return new PostResource($post);
    }

    public function destroy($id, ImgbbService $imgbb)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if (! $this->canManage($post)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $image = $post->image;

        $post->delete();

        $this->deleteRemoteImage($imgbb, $image);
        $this->forgetCache($id);

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
