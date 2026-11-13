@php
    // CMT-022: avatar del profilo (media o Gravatar d=mp), iniziali se manca.
    $props = ($comment->commentator && method_exists($comment->commentator, 'commentatorProperties'))
        ? $comment->commentator->commentatorProperties()
        : null;
    $displayName = $props?->name ?? $comment->commentator?->name ?? __('comment::txt.anonymous');
    $avatarUrl = is_string($props?->avatar ?? null) ? trim((string) $props->avatar) : '';
    $initials = '';
    foreach (preg_split('/\s+/u', trim((string) $displayName)) ?: [] as $part) {
        if ($part === '') {
            continue;
        }
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        if (mb_strlen($initials) >= 2) {
            break;
        }
    }
    if ($initials === '') {
        $initials = '?';
    }
@endphp
<div class="comment-item border-b pb-3 mb-3" id="comment-{{ $comment->id }}">
    <div class="d-flex">
        @if($showAvatars && $comment->commentator)
        <div class="flex-shrink-0 me-3">
            @if($avatarUrl !== '')
                <img
                    src="{{ $avatarUrl }}"
                    referrerpolicy="no-referrer"
                    alt=""
                    class="rounded-circle"
                    width="40"
                    height="40"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <span
                    class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white fw-bold"
                    style="width:40px;height:40px;font-size:0.875rem"
                    aria-hidden="true"
                >{{ $initials }}</span>
            @endif
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
