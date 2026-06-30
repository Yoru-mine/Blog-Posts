@props(['comment'])

@php
    $userReaction = $comment
        ->reactions()
        ->where('user_id', auth()->id())
        ->first();
@endphp

<div class="reaction-group">
    <button class="reaction-btn {{ $userReaction && $userReaction->reaction === 'like' ? 'active' : '' }}"
        data-comment-id="{{ $comment->id }}" data-reaction="like">
        <img src="{{ asset('images/heart.svg') }}" class="heart-img" alt="Like">
    </button>
    <button
        class="reaction-btn reaction-btn-burn {{ $userReaction && $userReaction->reaction === 'burn' ? 'active' : '' }}"
        data-comment-id="{{ $comment->id }}" data-reaction="burn">
        <img src="{{ asset('images/burn.svg') }}" class="burn-img" alt="Burn">
    </button>
    <button
        class="reaction-btn reaction-btn-lol {{ $userReaction && $userReaction->reaction === 'lol' ? 'active' : '' }}"
        data-comment-id="{{ $comment->id }}" data-reaction="lol">
        <img src="{{ asset('images/lol.svg') }}" class="lol-img" alt="Lol">
    </button>
     <button
        class="reaction-btn reaction-btn-sad {{ $userReaction && $userReaction->reaction === 'sad' ? 'active' : '' }}"
        data-comment-id="{{ $comment->id }}" data-reaction="sad">
        <img src="{{ asset('images/sad.svg') }}" class="sad-img" alt="Sad">
    </button>
</div>
