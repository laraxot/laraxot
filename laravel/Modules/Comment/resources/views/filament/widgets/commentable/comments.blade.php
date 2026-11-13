@php
    use Illuminate\Support\Facades\Auth;
    use Modules\Comment\Enums\NotificationSubscriptionType;
    use Modules\Comment\Policies\CommentPolicy;
    use Modules\Comment\Models\Contracts\CanComment;

    $commentPolicy = app(CommentPolicy::class);
    $commentator = Auth::user();
    $commentator = $commentator instanceof CanComment ? $commentator : null;
@endphp

<section class="cmt-section" @class(['cmt-section--newest-first' => $uiConfig->newestFirst])>
    <x-comment::styles />

    {{-- Esito dell'ultima azione (pubblicato, in attesa di approvazione): sempre nel DOM, cosi' lo screen reader lo annuncia. --}}
    <div class="cmt-status" role="status" aria-live="polite">{{ $statusMessage }}</div>

    @if($uiConfig->writable && $uiConfig->notifyOptions && Auth::check())
        <div class="cmt-subscription">
            <label for="comment-subscription" class="cmt-sr-only">{{ __('comment::txt.notification_subscription') }}</label>
            <select
                id="comment-subscription"
                wire:change="updateSelectedNotificationSubscriptionType($event.target.value)"
            >
                @foreach(NotificationSubscriptionType::cases() as $case)
                    <option value="{{ $case->value }}" @selected($uiConfig->notifySubType === $case->value)>
                        {{ $case->description() }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @auth
        @if($uiConfig->writable && $uiConfig->newestFirst)
            @include('comment::filament.widgets.commentable.partials.new-comment')
        @endif
    @endauth

    @forelse($this->comments as $comment)
        @continue(! $commentPolicy->see($commentator, $comment))
        @livewire(\Modules\Comment\Filament\Widgets\Comment\CommentWidget::class, [
            'comment' => $comment,
            'showAvatar' => $uiConfig->showAvatars,
            'newestFirst' => $uiConfig->newestFirst,
            'writable' => $uiConfig->writable,
            'showReplies' => $uiConfig->showReplies,
            'showReactions' => $uiConfig->showReactions,
        ], key('comment-'.$comment->id))
    @empty
        <p class="cmt-empty">
            {{ $uiConfig->noCommentsText ?? __('comment::txt.no_comments') }}
        </p>
    @endforelse

    @if($this->comments->hasPages())
        <div class="cmt-pagination">
            {{ $this->comments->links() }}
        </div>
    @endif

    @auth
        @if($uiConfig->writable && ! $uiConfig->newestFirst)
            @include('comment::filament.widgets.commentable.partials.new-comment')
        @endif
    @endauth
</section>
