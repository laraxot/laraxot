@php
    use Illuminate\Support\Facades\Auth;
    use Modules\Comment\Enums\NotificationSubscriptionType;
    use Modules\Comment\Policies\CommentPolicy;
    use Modules\Comment\Models\Contracts\CanComment;

    $commentPolicy = app(CommentPolicy::class);
    $commentator = Auth::user();
    $commentator = $commentator instanceof CanComment ? $commentator : null;
@endphp

<section class="comment-section space-y-4" @class(['comment-section-newest-first' => $uiConfig->newestFirst])>
    @if($uiConfig->writable && $uiConfig->notifyOptions && Auth::check())
        <div class="text-end text-sm">
            <label for="comment-subscription" class="sr-only">{{ __('comment::txt.notification_subscription') }}</label>
            <select
                id="comment-subscription"
                wire:change="updateSelectedNotificationSubscriptionType($event.target.value)"
                class="rounded-md border border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-800"
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
        @if($uiConfig->showReplies || $uiConfig->showReactions)
            @livewire(\Modules\Comment\Filament\Widgets\Comment\CommentWidget::class, [
                'comment' => $comment,
                'uiConfig' => [
                    'showAvatar' => $uiConfig->showAvatars,
                    'newestFirst' => $uiConfig->newestFirst,
                    'writable' => $uiConfig->writable,
                    'showReplies' => $uiConfig->showReplies,
                    'showReactions' => $uiConfig->showReactions,
                ],
            ], key('comment-'.$comment->id))
        @else
            <x-comment::comment-item :comment="$comment" :show-avatars="$uiConfig->showAvatars" />
        @endif
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $uiConfig->noCommentsText ?? __('comment::txt.no_comments') }}
        </p>
    @endforelse

    @if($this->comments->hasPages())
        <div class="mt-3">
            {{ $this->comments->links() }}
        </div>
    @endif

    @auth
        @if($uiConfig->writable && ! $uiConfig->newestFirst)
            @include('comment::filament.widgets.commentable.partials.new-comment')
        @endif
    @endauth
</section>
