<div class="create-posts-section" id="home-create-post">
    <div class="background">
        <h1 class="title-main">Create new post</h1>

        <div class="form-container">
            @if ($errors->any())
                <div class="errors">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="image-box" onclick="document.getElementById('create-post-image-input').click()">
                    <span id="create-post-placeholder">Image</span>
                    <img id="create-post-preview" src="" alt="">
                    <input type="file" id="create-post-image-input" name="image" accept="image/*">
                </div>

                <div class="field">
                    <span>Title</span>
                    <input type="text" name="title" value="{{ old('title') }}">
                </div>

                <div class="field">
                    <span>Text</span>
                    <textarea name="content">{{ old('content') }}</textarea>
                </div>

                @php
                    $categoryList = \Illuminate\Support\Facades\DB::table('categories')
                        ->orderBy('name')
                        ->get(['id', 'name']);
                    $tagSuggestions = \Illuminate\Support\Facades\DB::table('tags')
                        ->orderBy('name')
                        ->limit(50)
                        ->pluck('name');
                    $selectedCategory = old('category_id');
                    $initialTags = collect(old('tags', []))->filter()->take(5)->values();
                @endphp

                <div class="field">
                    <span>Category</span>
                    <select name="category_id" required>
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
                </div>

                <div class="tags-field">
                    <div class="tags-field-label">Tags</div>

                    <div class="tags-box" id="post-tags-box">
                        @foreach ($initialTags as $tag)
                            <span class="tag-chip" data-tag="{{ $tag }}">#{{ $tag }}<button
                                    type="button" class="tag-chip-remove"
                                    aria-label="Remove tag {{ $tag }}">×</button><input type="hidden"
                                    name="tags[]" value="{{ $tag }}"></span>
                        @endforeach
                        <input type="text" id="post-tags-input" class="tags-input" maxlength="24" autocomplete="off"
                            list="post-tags-suggestions" placeholder="Add a tag and press Enter" aria-label="Add a tag">
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
                </div>

                @php
                    $visibilityOptions = [
                        'public' => ['Public', 'Everyone can see this post.'],
                        'friends' => ['Friends', 'Only your friends.'],
                        'private' => ['Only me', 'Visible only to you.'],
                    ];
                    $selectedVisibility = old('visibility', 'public');
                @endphp

                <div class="visibility-field">
                    <div class="visibility-field-label" id="visibility-label">Who can see this post</div>

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
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn1">Create post</button>
                    <button type="button" id="save-draft-button" class="btn1 btn1-secondary">Save as draft</button>
                </div>

                <input type="hidden" name="draft_id" id="draft-id" value="{{ old('draft_id') }}">

                <div class="draft-status" id="draft-status" role="status" aria-live="polite"></div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-posts-section {
        padding: 0 0 180px;
    }

    .create-posts-section .background {
        width: min(1280px, calc(100% - 48px));
        min-height: 920px;
        background: url("/images/fon.png") no-repeat center/cover;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 72px 20px;
        margin: 0 auto;
        box-shadow: var(--shadow-soft);
    }

    .create-posts-section .title-main {
        font-size: clamp(28px, 6vw, 48px);
        color: white;
        margin-bottom: 42px;
        text-align: center;
    }

    .create-posts-section .form-container {
        width: 100%;
        max-width: 1166px;
        background: rgba(0, 0, 0, 0.33);
        padding: clamp(20px, 4vw, 40px);
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .create-posts-section .form-container form {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .create-posts-section .image-box {
        width: min(445px, 100%);
        aspect-ratio: 16 / 9;
        background: #1B1A1A;
        margin: 0 auto clamp(32px, 6vw, 79px);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .create-posts-section .image-box input {
        display: none;
    }

    .create-posts-section #create-post-placeholder {
        color: #aaa;
        position: absolute;
    }

    .create-posts-section #create-post-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .create-posts-section .field {
        width: min(870px, 100%);
        margin: 0 auto 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .create-posts-section .field span {
        font-size: 16px;
        color: #aaa;
        margin-bottom: 5px;
    }

    .create-posts-section .field input,
    .create-posts-section .field textarea {
        width: 100%;
        background: #1B1A1A;
        border: none;
        color: white;
        font-family: inherit;
        font-size: 15px;
        padding: 10px;
        box-sizing: border-box;
    }

    .create-posts-section .field input {
        height: 50px;
    }

    .create-posts-section .field textarea {
        height: 101px;
        resize: vertical;
    }

    .create-posts-section .field select {
        width: 100%;
        height: 50px;
        background: #1B1A1A url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 20 20' fill='%23aaaaaa'%3E%3Cpath d='M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z'/%3E%3C/svg%3E") no-repeat right 14px center;
        border: none;
        color: white;
        font-family: inherit;
        font-size: 15px;
        padding: 0 38px 0 10px;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }

    .create-posts-section .field select:invalid {
        color: #aaa;
    }

    .create-posts-section .field select option {
        background: #1B1A1A;
        color: white;
    }

    .create-posts-section .tags-field {
        width: min(870px, 100%);
        margin: 0 auto 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .create-posts-section .tags-field-label {
        font-size: 16px;
        color: #aaa;
        margin-bottom: 5px;
    }

    .create-posts-section .tags-box {
        width: 100%;
        min-height: 50px;
        box-sizing: border-box;
        padding: 8px 10px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        background: #1B1A1A;
        border: 1px solid transparent;
        cursor: text;
        transition: border-color 0.2s ease;
    }

    .create-posts-section .tags-box:focus-within {
        border-color: rgba(255, 255, 255, 0.5);
    }

    .create-posts-section .tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 6px 4px 10px;
        font-size: 14px;
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }

    .create-posts-section .tag-chip-remove {
        padding: 0 4px;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.6);
        font-family: inherit;
        font-size: 16px;
        line-height: 1;
        cursor: pointer;
    }

    .create-posts-section .tag-chip-remove:hover {
        color: white;
    }

    .create-posts-section .tags-input {
        flex: 1;
        min-width: 150px;
        height: 32px;
        padding: 0;
        border: none;
        outline: none;
        background: transparent;
        color: white;
        font-family: inherit;
        font-size: 15px;
    }

    .create-posts-section .tags-meta {
        width: 100%;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: 6px;
        font-size: 13px;
        color: #aaa;
    }

    .create-posts-section .visibility-field {
        width: min(870px, 100%);
        margin: 0 auto 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .create-posts-section .visibility-field-label {
        font-size: 16px;
        color: #aaa;
        margin-bottom: 5px;
    }

    .create-posts-section .visibility-options {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .create-posts-section .visibility-option {
        position: relative;
        display: block;
        cursor: pointer;
    }

    .create-posts-section .visibility-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .create-posts-section .visibility-card {
        height: 100%;
        padding: 12px 14px;
        box-sizing: border-box;
        background: #1B1A1A;
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-align: left;
        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .create-posts-section .visibility-title {
        font-size: 15px;
        font-weight: 600;
        color: white;
        margin-bottom: 4px;
    }

    .create-posts-section .visibility-hint {
        font-size: 13px;
        line-height: 1.4;
        color: #aaa;
    }

    .create-posts-section .visibility-option:hover .visibility-card {
        border-color: rgba(255, 255, 255, 0.35);
    }

    .create-posts-section .visibility-option input:checked+.visibility-card {
        border-color: white;
        background: #262525;
        box-shadow: 0 0 0 1px white inset;
    }

    .create-posts-section .visibility-option input:focus-visible+.visibility-card {
        outline: 2px solid rgba(255, 255, 255, 0.6);
        outline-offset: 2px;
    }

    .create-posts-section .btn1 {
        width: min(216px, 100%);
        height: 64px;
        background: rgba(16, 16, 16, 0.88);
        color: white;
        font-family: inherit;
        border: 1px solid rgba(255, 255, 255, 0.1);
        cursor: pointer;
        transition: 0.3s;
        margin: 20px auto 0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .create-posts-section .btn1:hover {
        background: white;
        color: black;
    }

    .create-posts-section .form-actions {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
        margin-top: 20px;
    }

    .create-posts-section .form-actions .btn1 {
        margin: 0;
    }

    .create-posts-section .btn1-secondary {
        background: transparent;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .create-posts-section .draft-status {
        width: 100%;
        min-height: 20px;
        margin-top: 14px;
        text-align: center;
        font-size: 14px;
        color: #aaa;
    }

    .create-posts-section .draft-status.is-error {
        color: #ff6b6b;
    }

    .create-posts-section .errors {
        color: #ff6b6b;
        margin-bottom: 15px;
        width: min(870px, 100%);
    }

    @media (max-width: 768px) {
        .create-posts-section {
            padding: 0 0 80px;
        }

        .create-posts-section .background {
            width: 100%;
            min-height: unset;
            padding: 80px 16px 48px;
        }

        .create-posts-section .visibility-options {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.getElementById('create-post-image-input').addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById('create-post-preview');
                const text = document.getElementById('create-post-placeholder');

                img.src = e.target.result;
                img.style.display = 'block';
                text.style.display = 'none';
            };

            reader.readAsDataURL(file);
        }
    });
</script>

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
