@php
    use Illuminate\Support\Facades\Auth;
    use Modules\Comment\Enums\NotificationSubscriptionType;
    use Modules\Comment\Policies\CommentPolicy;

    $commentPolicy = app(CommentPolicy::class);
@endphp

<section class="comment-section space-y-4" @class(['comment-section-newest-first' => $newestFirst])>
    @if($writable && $notifyOptions && Auth::check())
        <div class="text-end text-sm">
            <label for="comment-subscription" class="sr-only">{{ __('comment::txt.notification_subscription') }}</label>
            <select
                id="comment-subscription"
                wire:change="updateSelectedNotificationSubscriptionType($event.target.value)"
                class="rounded-md border border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-800"
            >
                @foreach(NotificationSubscriptionType::cases() as $case)
                    <option value="{{ $case->value }}" @selected($notifySubType === $case->value)>
                        {{ $case->description() }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @auth
        @if($writable && $newestFirst)
            @include('comment::livewire.partials.new-comment')
        @endif
    @endauth

    @forelse($this->comments as $comment)
        @continue(! $commentPolicy->see(auth()->user(), $comment))
        @if($showReplies || $showReactions)
            <livewire:comments-comment
                :key="$comment->id"
                :comment="$comment"
                :show-avatar="$showAvatars"
                :newest-first="$newestFirst"
                :writable="$writable"
                :show-replies="$showReplies"
                :show-reactions="$showReactions"
            />
        @else
            <x-comment::comment-item :comment="$comment" :show-avatars="$showAvatars" />
        @endif
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $noCommentsText ?? __('comment::txt.no_comments') }}
        </p>
    @endforelse

    @if($this->comments->hasPages())
        <div class="mt-3">
            {{ $this->comments->links() }}
        </div>
    @endif

    @auth
        @if($writable && ! $newestFirst)
            @include('comment::livewire.partials.new-comment')
        @endif
    @endauth
</section>
