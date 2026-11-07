<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Comment;
use App\Models\Post;




Route::get('/', function () {
    $posts = \App\Models\Post::where('is_published', true)->where('visibility', 'public')->latest()->take(8)->get();
    return view('home', compact('posts'));
})->name('home');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
Route::get('/users/search', [UserController::class, 'searchUsers'])->middleware('throttle:30,1')->name('users.search');
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('users.show');

Route::get('/dashboard', function () {
    $user = Auth::user();
    $recentPosts = $user ? $user->posts()->where('is_published', true)->latest()->take(3)->get() : collect();
    $stats = [
        'posts_count' => $user ? $user->posts()->where('is_published', true)->count() : 0,
        'comments_count' => $user ? $user->posts()->where('is_published', true)->withCount('comments')->get()->sum('comments_count') : 0,
        'latest_post' => $recentPosts->first(),
    ];

    return view('dashboard', compact('user', 'recentPosts', 'stats'));
})->middleware(['auth'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::post('/posts/draft', [PostController::class, 'saveDraft'])
        ->middleware('throttle:60,1')
        ->name('posts.saveDraft');

    Route::patch('/posts/draft/{id}', [PostController::class, 'updateDraft'])
        ->middleware('throttle:60,1')
        ->name('posts.updateDraft');

    Route::get('/posts/drafts', [PostController::class, 'drafts'])->name('posts.drafts');
    Route::patch('/posts/{id}/publish', [PostController::class, 'publish'])->name('posts.publish');
    Route::post('/users/{user}/following', [FollowingController::class, 'store'])->middleware('throttle:30,1')->name('users.follow');
    Route::delete('/users/{user}/following', [FollowingController::class, 'destroy'])->middleware('throttle:30,1')->name('users.unfollow');
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroyAvatar');
    Route::get('/admin-panel', function () {
        $stats = [
            'posts' => Post::count(),
            'comments' => Comment::count(),
            'users' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
        ];

        $latestPosts = Post::latest()->take(4)->get();
        $latestComments = Comment::latest()->take(4)->get();
        $latestUsers = User::latest()->take(4)->get();

        return view('admin.panel', compact('stats', 'latestPosts', 'latestComments', 'latestUsers'));
    })->middleware('admin')->name('admin.panel');

    Route::get('/admin-panel/posts', function () {
        $posts = Post::latest()->with('user')->paginate(10);

        return view('admin.posts', compact('posts'));
    })->middleware('admin')->name('admin.posts');

    Route::get('/admin-panel/comments', function () {
        $comments = Comment::latest()->with(['post', 'user'])->paginate(10);

        return view('admin.comments', compact('comments'));
    })->middleware('admin')->name('admin.comments');

    Route::get('/admin-panel/users', function () {
        $users = User::latest()->paginate(10);

        return view('admin.users', compact('users'));
    })->middleware('admin')->name('admin.users');

    Route::patch('/admin-panel/users/{user}/toggle-admin', function (User $user) {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot change your own admin role.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return back()->with('success', 'User role updated.');
    })->middleware('admin')->name('admin.users.toggle-admin');

    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->middleware('throttle:10,1')->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->middleware('throttle:60,1')->name('posts.like');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->middleware('throttle:20,1')->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/upload', [FileUploadController::class, 'store'])->middleware('throttle:20,1')->name('upload.file');
});

Route::get('/posts/search', [PostController::class, 'search'])->middleware('throttle:30,1')->name('posts.search');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
require __DIR__ . '/auth.php';
