@php
    $props = ($comment->commentator && method_exists($comment->commentator, 'commentatorProperties'))
        ? $comment->commentator->commentatorProperties()
        : null;
    $avatarUrl = $props?->avatar
        ?? ($comment->commentator
            ? 'https://www.gravatar.com/avatar/'.md5(strtolower((string) ($comment->commentator->email ?? ''))).'?d=mp&size=40'
            : '');
    $displayName = $props?->name ?? $comment->commentator?->name ?? __('comment::txt.anonymous');
@endphp
<div class="comment-item border-b pb-3 mb-3" id="comment-{{ $comment->id }}">
    <div class="d-flex">
        @if($showAvatars && $comment->commentator)
        <div class="flex-shrink-0 me-3">
            <img 
                src="{{ $avatarUrl }}" 
                alt="{{ $displayName }}"
                class="rounded-circle"
                width="40"
                height="40"
            >
        </div>
        @endif
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between">
                <strong>{{ $displayName }}</strong>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <p class="mb-1">{{ $comment->original_text }}</p>
            
            @if($comment->reactions->isNotEmpty())
            <div class="reactions">
                @foreach($comment->reactions as $reaction)
                    <span class="badge bg-light text-dark me-1">{{ $reaction->reaction }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>