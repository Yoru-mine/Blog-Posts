@extends('layouts.app')

@section('title', 'Edit post')

@section('content')
    @php
        $isDraft = $post->is_published !== null && !$post->is_published;
    @endphp
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


        .edit-select {
            width: 100%;
            min-height: 58px;
            padding: 0 44px 0 18px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(20, 20, 20, 0.62) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 20 20' fill='%23aaaaaa'%3E%3Cpath d='M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z'/%3E%3C/svg%3E") no-repeat right 18px center;
            color: #f5f5f5;
            font-size: 16px;
            font-family: inherit;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }

        .edit-select:invalid {
            color: rgba(255, 255, 255, 0.5);
        }

        .edit-select:focus {
            border-color: rgba(255, 255, 255, 0.28);
        }

        .edit-select option {
            background: #1B1A1A;
            color: #f5f5f5;
        }

        .tags-box {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            min-height: 58px;
            padding: 10px 14px;
            box-sizing: border-box;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(20, 20, 20, 0.62);
            cursor: text;
            transition: border-color 0.2s ease;
        }

        .tags-box:focus-within {
            border-color: rgba(255, 255, 255, 0.28);
        }

        .tag-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 6px 5px 12px;
            font-size: 14px;
            color: #f5f5f5;
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tag-chip-remove {
            padding: 0 4px;
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.6);
            font-family: inherit;
            font-size: 16px;
            line-height: 1;
            cursor: pointer;
        }

        .tag-chip-remove:hover {
            color: #fff;
        }

        .tags-input {
            flex: 1;
            min-width: 160px;
            height: 34px;
            padding: 0;
            border: none;
            outline: none;
            background: transparent;
            color: #f5f5f5;
            font-family: inherit;
            font-size: 16px;
        }

        .tags-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
        }

        .visibility-options {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .visibility-option {
            position: relative;
            display: block;
            cursor: pointer;
        }

        .visibility-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .visibility-card {
            height: 100%;
            padding: 14px 16px;
            box-sizing: border-box;
            background: rgba(20, 20, 20, 0.62);
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .visibility-title {
            font-size: 15px;
            font-weight: 600;
            color: #f5f5f5;
            margin-bottom: 4px;
        }

        .visibility-hint {
            font-size: 13px;
            line-height: 1.45;
            color: rgba(255, 255, 255, 0.56);
        }

        .visibility-option:hover .visibility-card {
            border-color: rgba(255, 255, 255, 0.28);
        }

        .visibility-option input:checked+.visibility-card {
            background: rgba(242, 237, 226, 0.1);
            border-color: #f2ede2;
            box-shadow: 0 0 0 1px #f2ede2 inset;
        }

        .visibility-option input:focus-visible+.visibility-card {
            outline: 2px solid rgba(255, 255, 255, 0.6);
            outline-offset: 2px;
        }

        .edit-button-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #f4f4f4;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: none;
        }

        .draft-status {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        .draft-status.is-error {
            color: #efb3b3;
        }

        .draft-pill {
            display: inline-block;
            margin: 0 0 18px 12px;
            padding: 2px 10px;
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #1E1E1E;
            background: #f2ede2;
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

            .visibility-options {
                grid-template-columns: 1fr;
            }


        }
    </style>

    <div class="edit-page">
        <div class="edit-shell">
            <section class="edit-hero">
                <div>
                    <span class="edit-kicker">Edit workspace</span>
                    @if ($isDraft)
                        <span class="draft-pill">Draft</span>
                    @endif
                    <h1 class="edit-title">{{ $isDraft ? 'Edit your draft' : 'Edit your post' }}</h1>
                    <p class="edit-copy">
                        Update the title, text, and visual mood of your post here. The layout stays in the same atmosphere
                        as the rest of the project, so editing feels like part of the same world.
                    </p>
                </div>

                <div class="edit-preview">
                    <div class="edit-preview-label">Current image</div>
                    <img src="{{ $post->image ?: asset('images/default.png') }}" alt="{{ $post->title }}"
                        class="edit-preview-image">
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

                    @php
                        $categoryList = \Illuminate\Support\Facades\DB::table('categories')
                            ->orderBy('name')
                            ->get(['id', 'name']);
                        $tagSuggestions = \Illuminate\Support\Facades\DB::table('tags')
                            ->orderBy('name')
                            ->limit(50)
                            ->pluck('name');
                        $selectedCategory = old('category_id', $post->category_id ?? null);

                        // Теги поста из базы; после ошибки валидации берём то, что ввёл пользователь
                        $rawTags = old('tags');
                        if ($rawTags === null) {
                            $rawTags = \Illuminate\Support\Facades\DB::table('post_tag')
                                ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                                ->where('post_tag.post_id', $post->id)
                                ->orderBy('post_tag.id')
                                ->pluck('tags.name')
                                ->all();
                        }
                        $initialTags = collect($rawTags)->filter()->take(5)->values();
                    @endphp

                    <div class="edit-field">
                        <label for="category_id" class="edit-label">Category</label>
                        <select name="category_id" id="category_id" class="edit-select" required>
                            @if ($categoryList->isEmpty())
                                <option value="" disabled selected>No categories available</option>
                            @else
                                <option value="" disabled @selected(!$selectedCategory)>Select category</option>
                                @foreach ($categoryList as $category)
                                    <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('category_id')
                            <div class="edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="edit-field">
                        <label for="post-tags-input" class="edit-label">Tags</label>
                        <div class="tags-box" id="post-tags-box">
                            @foreach ($initialTags as $tag)
                                <span class="tag-chip" data-tag="{{ $tag }}">#{{ $tag }}<button
                                        type="button" class="tag-chip-remove"
                                        aria-label="Remove tag {{ $tag }}">×</button><input type="hidden"
                                        name="tags[]" value="{{ $tag }}"></span>
                            @endforeach
                            <input type="text" id="post-tags-input" class="tags-input" maxlength="24" autocomplete="off"
                                list="post-tags-suggestions" placeholder="Add a tag and press Enter">
                        </div>
                        <datalist id="post-tags-suggestions">
                            @foreach ($tagSuggestions as $suggestion)
                                <option value="{{ $suggestion }}"></option>
                            @endforeach
                        </datalist>
                        <div class="tags-meta">
                            <span>Up to 5 tags. Press Enter or comma to add.</span>
                            <span id="post-tags-count">0/5</span>
                        </div>
                        @error('tags')
                            <div class="edit-error">{{ $message }}</div>
                        @enderror
                        @error('tags.*')
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

                    @php
                        $visibilityOptions = [
                            'public' => ['Public', 'Everyone can see this post.'],
                            'friends' => ['Friends', 'Only your friends.'],
                            'private' => ['Only me', 'Visible only to you.'],
                        ];
                        $selectedVisibility = old('visibility', $post->visibility ?? 'public');
                    @endphp

                    <div class="edit-field">
                        <span class="edit-label" id="visibility-label">Who can see this post</span>
                        <div class="visibility-options" role="radiogroup" aria-labelledby="visibility-label">
                            @foreach ($visibilityOptions as $value => [$title, $hint])
                                <label class="visibility-option">
                                    <input type="radio" name="visibility" value="{{ $value }}"
                                        @checked($selectedVisibility === $value)>
                                    <div class="visibility-card">
                                        <div class="visibility-title">{{ $title }}</div>
                                        <div class="visibility-hint">{{ $hint }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('visibility')
                            <div class="edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="edit-actions"
                        style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <div style="display: flex; gap: 15px; align-items: center;">
                            @if ($isDraft)
                                <button type="submit" name="publish" value="1"
                                    class="edit-button">Publish</button>
                                <button type="button" id="save-draft-button" data-draft-id="{{ $post->id }}"
                                    class="edit-button edit-button-secondary">Save draft</button>
                                <span class="draft-status" id="draft-status" role="status" aria-live="polite"></span>
                            @else
                                <button type="submit" class="edit-button">Update post</button>
                            @endif
                            <a href="{{ route('posts.show', $post->id) }}" class="edit-link">Open post</a>
                        </div>
                </form>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="margin: 0;"
                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button">Delete</button>
                </form>
        </div>
        </section>
        </form>
        </section>
    </div>
    </div>

    <script>
        (function() {
            const box = document.getElementById('post-tags-box');
            const input = document.getElementById('post-tags-input');
            const counter = document.getElementById('post-tags-count');

            if (!box || !input) return;

            const MAX_TAGS = 5;

            function normalize(value) {
                return value
                    .trim()
                    .replace(/^#+/, '')
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\p{L}\p{N}_-]/gu, '');
            }

            function currentTags() {
                return Array.from(box.querySelectorAll('.tag-chip')).map((chip) => chip.dataset.tag);
            }

            function refresh() {
                const count = currentTags().length;

                counter.textContent = count + '/' + MAX_TAGS;
                input.disabled = count >= MAX_TAGS;
                input.placeholder = count >= MAX_TAGS ? '' : 'Add a tag and press Enter';
            }

            function addTag(raw) {
                const tag = normalize(raw);
                const tags = currentTags();

                if (!tag || tags.includes(tag) || tags.length >= MAX_TAGS) return;

                const chip = document.createElement('span');
                chip.className = 'tag-chip';
                chip.dataset.tag = tag;
                chip.append('#' + tag);

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'tag-chip-remove';
                remove.setAttribute('aria-label', 'Remove tag ' + tag);
                remove.textContent = '×';

                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'tags[]';
                hidden.value = tag;

                chip.append(remove, hidden);
                box.insertBefore(chip, input);
                refresh();
            }

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ',') {
                    // Enter в поле тега не должен отправлять форму
                    event.preventDefault();
                    addTag(input.value);
                    input.value = '';
                } else if (event.key === 'Backspace' && !input.value) {
                    const chips = box.querySelectorAll('.tag-chip');
                    chips[chips.length - 1]?.remove();
                    refresh();
                }
            });

            // Вставка списка через запятую и ввод запятой на мобильных клавиатурах
            input.addEventListener('input', () => {
                if (input.value.includes(',')) {
                    input.value.split(',').forEach(addTag);
                    input.value = '';
                }
            });

            input.addEventListener('blur', () => {
                addTag(input.value);
                input.value = '';
            });

            box.addEventListener('click', (event) => {
                const removeButton = event.target.closest('.tag-chip-remove');

                if (removeButton) {
                    removeButton.closest('.tag-chip').remove();
                    refresh();
                    input.focus();
                    return;
                }

                if (event.target === box) input.focus();
            });

            refresh();
        })();
    </script>

    @if ($isDraft)
        <script>
            (function() {
                const button = document.getElementById('save-draft-button');
                const status = document.getElementById('draft-status');

                if (!button || !status) return;

                const form = button.closest('form');
                const draftIdInput = document.getElementById('draft-id');
                const csrfToken = '{{ csrf_token() }}';
                const createUrl = '{{ route('posts.saveDraft') }}';
                const updateUrl = '{{ route('posts.updateDraft', ['id' => '__ID__']) }}';
                const fixedId = button.dataset.draftId || '';

                function setStatus(text, isError) {
                    status.textContent = text;
                    status.classList.toggle('is-error', Boolean(isError));
                }

                function collect() {
                    const data = new FormData(form);
                    const categoryId = data.get('category_id');

                    return {
                        title: data.get('title') || '',
                        content: data.get('content') || '',
                        category_id: categoryId ? Number(categoryId) : null,
                        visibility: data.get('visibility') || 'public',
                        tags: data.getAll('tags[]'),
                    };
                }

                button.addEventListener('click', async () => {
                    const draftId = fixedId || (draftIdInput ? draftIdInput.value : '');

                    button.disabled = true;
                    setStatus('Saving...');

                    try {
                        const response = await fetch(draftId ? updateUrl.replace('__ID__', draftId) :
                            createUrl, {
                                method: draftId ? 'PATCH' : 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify(collect()),
                            });

                        const result = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            const firstError = result.errors ? Object.values(result.errors)[0][0] : null;
                            throw new Error(firstError || 'Could not save the draft.');
                        }

                        if (draftIdInput && result.post_id) draftIdInput.value = result.post_id;

                        const time = new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        setStatus('Draft saved at ' + time + '. The image is added when you publish.');
                    } catch (error) {
                        setStatus(error.message || 'Could not save the draft.', true);
                    } finally {
                        button.disabled = false;
                    }
                });
            })();
        </script>
    @endif
@endsection
