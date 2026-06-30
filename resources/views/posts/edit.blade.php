@extends('layouts.app')

@section('title', 'Edit post')

@section('content')
    <style>
        .edit-page {
            min-height: 100vh;
            padding: 132px 24px 72px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 22%),
                radial-gradient(circle at right center, rgba(255, 220, 220, 0.08), transparent 22%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), transparent 32%);
        }

        .edit-shell {
            max-width: 1220px;
            margin: 0 auto;
            display: grid;
            gap: 28px;
        }

        .edit-hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 26px;
            padding: 38px;
            background: rgba(28, 28, 28, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .edit-kicker {
            display: inline-block;
            margin-bottom: 18px;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
        }

        .edit-title {
            margin: 0 0 16px;
            font-size: 52px;
            line-height: 1.03;
        }

        .edit-copy {
            max-width: 680px;
            margin: 0;
            font-size: 17px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.78);
        }

        .edit-preview {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 18px;
            display: grid;
            align-content: start;
            gap: 14px;
        }

        .edit-preview-label {
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.52);
        }

        .edit-preview-image {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            background: rgba(255, 255, 255, 0.05);
        }

        .edit-preview-note {
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.7);
        }

        .edit-card {
            padding: 32px;
            background: rgba(30, 30, 30, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .edit-form {
            display: grid;
            gap: 22px;
        }

        .edit-field {
            display: grid;
            gap: 10px;
        }

        .edit-label {
            font-size: 13px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.56);
        }

        .edit-input,
        .edit-textarea,
        .edit-file {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(20, 20, 20, 0.62);
            color: #f5f5f5;
            font-size: 16px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .edit-input,
        .edit-file {
            min-height: 58px;
            padding: 0 18px;
        }

        .edit-textarea {
            min-height: 220px;
            padding: 16px 18px;
            resize: vertical;
            line-height: 1.75;
        }

        .edit-input:focus,
        .edit-textarea:focus,
        .edit-file:focus {
            border-color: rgba(255, 255, 255, 0.28);
            background: rgba(24, 24, 24, 0.92);
        }

        .edit-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 10px;
        }

        .edit-button,
        .delete-button,
        .edit-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .edit-button {
            background: #f2ede2;
            color: #1E1E1E;
            box-shadow: 0 0 18px rgba(255, 255, 255, 0.14);
        }

        .edit-link {
            background: rgba(255, 255, 255, 0.08);
            color: #f4f4f4;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .delete-button {
            background: rgba(220, 80, 80, 0.15);
            color: #efb3b3;
            border: 1px solid rgba(220, 80, 80, 0.25);
        }

        .delete-button:hover {
            background: rgba(220, 80, 80, 0.28);
            transform: translateY(-1px);
            opacity: 1;
        }

        .edit-button:hover,
        .edit-link:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .edit-error {
            font-size: 13px;
            line-height: 1.55;
            color: #efb3b3;
        }


        @media (max-width: 1024px) {
            .edit-hero {
                grid-template-columns: 1fr;
            }

            .edit-title {
                font-size: 40px;
            }
        }

        @media (max-width: 720px) {
            .edit-page {
                padding: 112px 14px 36px;
            }

            .edit-hero,
            .edit-card {
                padding: 22px 18px;
            }

            .edit-title {
                font-size: 34px;
            }

            .edit-actions {
                flex-direction: column;
            }

            .edit-button,
            .edit-link {
                width: 100%;
            }

            .edit-preview-image {
                height: 210px;
            }


        }
    </style>

    <div class="edit-page">
        <div class="edit-shell">
            <section class="edit-hero">
                <div>
                    <span class="edit-kicker">Edit workspace</span>
                    <h1 class="edit-title">Edit your post</h1>
                    <p class="edit-copy">
                        Update the title, text, and visual mood of your post here. The layout stays in the same atmosphere
                        as the rest of the project, so editing feels like part of the same world.
                    </p>
                </div>

                <div class="edit-preview">
                    <div class="edit-preview-label">Current image</div>
                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('images/default.png') }}"
                        alt="{{ $post->title }}" class="edit-preview-image">
                    <p class="edit-preview-note">
                        This is the image currently attached to the post. You can upload a new one below if you want to
                        change the visual.
                    </p>
                </div>
            </section>

            <section class="edit-card">
                <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data"
                    class="edit-form">
                    @csrf
                    @method('PUT')

                    <div class="edit-field">
                        <label for="title" class="edit-label">Title</label>
                        <input type="text" name="title" id="title" class="edit-input"
                            value="{{ old('title', $post->title) }}" required>
                        @error('title')
                            <div class="edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="edit-field">
                        <label for="content" class="edit-label">Content</label>
                        <textarea name="content" id="content" class="edit-textarea" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="edit-field">
                        <label for="image" class="edit-label">Replace image</label>
                        <label for="image" class="edit-file-label">
                            <span class="edit-file-icon">↑</span>
                            <span class="edit-file-text" id="file-text">Choose file...</span>
                            <input type="file" id="image" name="image" accept="image/*" style="display:none"
                                onchange="document.getElementById('file-text').textContent = this.files[0]?.name || 'Choose file...'">
                        </label>
                        @error('image')
                            <div class="edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="edit-actions">
                        <button type="submit" class="edit-button">Update post</button>

                        <a href="{{ route('posts.show', $post->id) }}" class="edit-link">Open post</a>
                    </div>
                </form>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;"
                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button">Delete</button>
                </form>
            </section>
        </div>
    </div>
@endsection
