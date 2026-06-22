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

                <button type="submit" class="btn1">Create post</button>
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
        font-size: 48px;
        color: white;
        margin-bottom: 42px;
        text-align: center;
    }

    .create-posts-section .form-container {
        width: 1166px;
        height: 790px;
        background: rgba(0, 0, 0, 0.33);
        padding: 40px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .create-posts-section .image-box {
        width: 445px;
        height: 241px;
        background: #1B1A1A;
        margin: 0 auto 79px;
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
        width: 870px;
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
        padding: 10px;
        box-sizing: border-box;
    }

    .create-posts-section .field input {
        height: 50px;
    }

    .create-posts-section .field textarea {
        height: 101px;
        resize: none;
    }

    .create-posts-section .btn1 {
        width: 216px;
        height: 75px;
        background: rgba(16, 16, 16, 0.88);
        color: white;
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

    .create-posts-section .errors {
        color: #ff6b6b;
        margin-bottom: 15px;
        width: 870px;
    }

    @media (max-width: 1280px) {
        .create-posts-section .background {
            width: calc(100% - 40px);
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
