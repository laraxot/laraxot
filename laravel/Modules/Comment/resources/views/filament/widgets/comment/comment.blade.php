@php
    use Illuminate\Support\Facades\Auth;
    use Modules\Comment\Datas\CommentConfigData;
    use Modules\Comment\Models\Comment;
    use Modules\Comment\Models\Contracts\CanComment;
    use Modules\Comment\Policies\CommentPolicy;
@endphp
{{--
    Un commento (CMT-022). Livewire vuole UN solo elemento radice, sempre presente: un `@if` in testa lascia un commento HTML
    come primo nodo e al secondo render il componente figlio fallisce con "Invalid Livewire child tag name". Per questo la radice
    c'e' anche quando il commento e' stato eliminato, e tutto il resto vive dentro.
--}}
<div class="cmt-comment" @if($comment instanceof Comment) id="comment-{{ $comment->id }}" @endif>
@if($comment instanceof Comment)
    @php
        $commentPolicy = app(CommentPolicy::class);
        $commentator = Auth::user();
        $commentator = $commentator instanceof CanComment ? $commentator : null;
        $props = $comment->commentatorProperties();
        $displayName = $props?->name ?? $comment->commentator?->name ?? __('comment::txt.anonymous');
        $storedAvatar = is_string($props?->avatar ?? null) ? trim((string) $props->avatar) : '';
        // CMT-022: avatar del profilo o Gravatar (richiesta esplicita dell'utente, supera D-03); iniziali se manca.
        $useRemoteAvatar = $storedAvatar !== '';
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
        $canReply = $uiConfig->showReplies && $uiConfig->writable && $commentPolicy->create($commentator) && $this->canReply();
        $replyFormId = 'cmt-reply-'.$comment->id;
    @endphp
    <article class="cmt-item" aria-label="{{ __('comment::txt.comment_by', ['name' => $displayName]) }}">
        @if($uiConfig->showAvatar && $comment->commentator)
            @if($useRemoteAvatar)
                <img
                    src="{{ $storedAvatar }}"
                    alt=""
                    referrerpolicy="no-referrer"
                    class="cmt-avatar"
                    width="40"
                    height="40"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <span class="cmt-avatar cmt-avatar--initials" aria-hidden="true">{{ $initials }}</span>
            @endif
        @endif

        <div class="cmt-content">
            <header class="cmt-meta">
                <strong class="cmt-author">{{ $displayName }}</strong>
                <time class="cmt-time" datetime="{{ $comment->created_at?->toIso8601String() }}">{{ $comment->created_at?->diffForHumans() }}</time>
            </header>

            <div class="cmt-status" role="status" aria-live="polite">{{ $statusMessage }}</div>

            @if($uiConfig->isEditing)
                <form wire:submit="edit" class="cmt-form" novalidate>
                    {{ $this->form }}
                    <div class="cmt-form-actions">
                        <button type="submit" class="cmt-btn cmt-btn--primary" wire:target="edit" wire:loading.attr="disabled">
                            {{ __('comment::txt.save_changes') }}
                        </button>
                        <button type="button" class="cmt-btn cmt-btn--secondary" wire:click="stopEditing">
                            {{ __('comment::txt.cancel') }}
                        </button>
                    </div>
                </form>
            @else
                <div class="cmt-body">
                    {!! $comment->text ?? e($comment->original_text) !!}
                </div>
            @endif

            @if($comment->isPending())
                <p class="cmt-pending">{{ __('comment::txt.pending_approval') }}</p>
                @if($uiConfig->writable)
                    <div class="cmt-actions">
                        @can('approve', $comment)
                            <button type="button" class="cmt-link cmt-link--success" wire:click="approve">{{ __('comment::txt.approve') }}</button>
                        @endcan
                        @can('reject', $comment)
                            <button type="button" class="cmt-link cmt-link--danger" wire:click="reject">{{ __('comment::txt.reject') }}</button>
                        @endcan
                    </div>
                @endif
            @endif

            @if($uiConfig->showReactions && $comment->reactions->isNotEmpty())
                <div class="cmt-reactions">
                    @foreach($comment->reactions as $reaction)
                        <span class="cmt-reaction">{{ $reaction->reaction }}</span>
                    @endforeach
                </div>
            @endif

            @if($uiConfig->writable)
                <div class="cmt-actions">
                    @if($canReply && ! $uiConfig->isReplying)
                        <button
                            type="button"
                            class="cmt-link"
                            wire:click="startReplying"
                            aria-label="{{ __('comment::txt.reply_to', ['name' => $displayName]) }}"
                            aria-expanded="false"
                            aria-controls="{{ $replyFormId }}"
                        >{{ __('comment::txt.reply') }}</button>
                    @endif
                    @can('update', $comment)
                        @unless($uiConfig->isEditing)
                            <button type="button" class="cmt-link" wire:click="startEditing">{{ __('comment::txt.edit') }}</button>
                        @endunless
                    @endcan
                    @can('delete', $comment)
                        <button
                            type="button"
                            class="cmt-link cmt-link--danger"
                            wire:click="delete"
                            wire:confirm="{{ __('comment::txt.confirm_delete') }}"
                        >{{ __('comment::txt.delete') }}</button>
                    @endcan
                    @if($uiConfig->showReactions)
                        @foreach(CommentConfigData::make()->allowedReactions as $reaction)
                            <button type="button" class="cmt-reaction cmt-reaction--button" wire:click="toggleReaction('{{ $reaction }}')">{{ $reaction }}</button>
                        @endforeach
                    @endif
                </div>
            @endif

            {{-- Il form di risposta esiste solo dopo «Rispondi»: l'editor non si monta sotto ogni commento. --}}
            @if($uiConfig->isReplying && $canReply)
                <form wire:submit="reply" id="{{ $replyFormId }}" class="cmt-form cmt-form--reply" novalidate>
                    <p class="cmt-replying-to">{{ __('comment::txt.replying_to', ['name' => $displayName]) }}</p>
                    {{ $this->form }}
                    <div class="cmt-form-actions">
                        <button type="submit" class="cmt-btn cmt-btn--primary" wire:target="reply" wire:loading.attr="disabled">
                            {{ __('comment::txt.post_reply') }}
                        </button>
                        <button type="button" class="cmt-btn cmt-btn--secondary" wire:click="stopReplying">
                            {{ __('comment::txt.cancel') }}
                        </button>
                    </div>
                </form>
            @endif

            @if($uiConfig->showReplies && $comment->nestedComments->isNotEmpty())
                <ul class="cmt-replies" aria-label="{{ __('comment::txt.replies_to', ['name' => $displayName]) }}">
                    @foreach($comment->nestedComments as $nested)
                        @continue(! $commentPolicy->see($commentator, $nested))
                        <li class="cmt-replies-item">
                            @livewire(\Modules\Comment\Filament\Widgets\Comment\CommentWidget::class, [
                                'comment' => $nested,
                                'showAvatar' => $uiConfig->showAvatar,
                                'newestFirst' => $uiConfig->newestFirst,
                                'writable' => $uiConfig->writable,
                                'showReplies' => $uiConfig->showReplies,
                                'showReactions' => $uiConfig->showReactions,
                            ], key('nested-comment-'.$nested->id))
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </article>
@endif
</div>
