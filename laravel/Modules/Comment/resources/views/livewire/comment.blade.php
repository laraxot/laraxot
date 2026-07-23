@php
    use Modules\Comment\Policies\CommentPolicy;

    $commentPolicy = app(CommentPolicy::class);
    $props = $comment->commentatorProperties();
    $avatarUrl = $props?->avatar
        ?? ($comment->commentator
            ? 'https://www.gravatar.com/avatar/'.md5(strtolower((string) ($comment->commentator->email ?? ''))).'?d=mp&size=40'
            : '');
    $displayName = $props?->name ?? $comment->commentator?->name ?? __('comment::txt.anonymous');
@endphp
<div
    id="comment-{{ $comment->id }}"
    class="comment-thread border-b border-gray-200 pb-4 mb-4 dark:border-gray-700"
    wire:key="comment-{{ $comment->id }}"
>
    <div class="flex gap-3">
        @if($showAvatar && $comment->commentator)
            <img
                src="{{ $avatarUrl }}"
                alt="{{ $displayName }}"
                class="h-10 w-10 shrink-0 rounded-full"
                width="40"
                height="40"
            >
        @endif
        <div class="min-w-0 flex-1">
            <div class="mb-1 flex flex-wrap items-center justify-between gap-2">
                <strong class="text-sm text-gray-900 dark:text-gray-100">{{ $displayName }}</strong>
                <small class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at?->diffForHumans() }}</small>
            </div>

            @if($isEditing)
                <form wire:submit="edit" class="mb-2 space-y-2">
                    <textarea wire:model="editText" class="w-full rounded-md border border-gray-300 p-2 text-sm dark:border-gray-600 dark:bg-gray-800" rows="3"></textarea>
                    @error('editText') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    <div class="flex gap-2">
                        <button type="submit" class="rounded-md bg-blue-600 px-3 py-1 text-sm text-white">{{ __('comment::txt.submit') }}</button>
                        <button type="button" wire:click="stopEditing" class="rounded-md border px-3 py-1 text-sm">{{ __('comment::txt.cancel') }}</button>
                    </div>
                </form>
            @else
                <div class="prose prose-sm mb-2 max-w-none text-gray-800 dark:text-gray-200">
                    {!! $comment->text ?? e($comment->original_text) !!}
                </div>
            @endif

            @if($comment->isPending())
                <p class="mb-2 text-xs text-amber-600">{{ __('comment::txt.pending_approval') }}</p>
                @if($writable)
                    @can('approve', $comment)
                        <button type="button" wire:click="approve" class="mr-2 text-xs text-green-600 underline">{{ __('comment::txt.approve') }}</button>
                    @endcan
                    @can('reject', $comment)
                        <button type="button" wire:click="reject" class="text-xs text-red-600 underline">{{ __('comment::txt.reject') }}</button>
                    @endcan
                @endif
            @endif

            @if($showReactions && $comment->reactions->isNotEmpty())
                <div class="mb-2 flex flex-wrap gap-1">
                    @foreach($comment->reactions as $reaction)
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs dark:bg-gray-700">{{ $reaction->reaction }}</span>
                    @endforeach
                </div>
            @endif

            @if($writable)
                <div class="mb-2 flex flex-wrap gap-3 text-xs">
                    @can('update', $comment)
                        @unless($isEditing)
                            <button type="button" wire:click="startEditing" class="text-blue-600 underline">{{ __('comment::txt.edit') }}</button>
                        @endunless
                    @endcan
                    @can('delete', $comment)
                        <button type="button" wire:click="delete" class="text-red-600 underline">{{ __('comment::txt.delete') }}</button>
                    @endcan
                    @if($showReactions)
                        @foreach(\Modules\Comment\Datas\CommentConfigData::make()->allowedReactions() as $reaction)
                            <button type="button" wire:click="toggleReaction('{{ $reaction }}')" class="hover:opacity-80">{{ $reaction }}</button>
                        @endforeach
                    @endif
                </div>
            @endif

            @if($showReplies && $writable && $commentPolicy->create(auth()->user()))
                <form wire:submit="reply" class="mb-3 space-y-2">
                    <textarea wire:model="replyText" class="w-full rounded-md border border-gray-300 p-2 text-sm dark:border-gray-600 dark:bg-gray-800" rows="2" placeholder="{{ __('comment::txt.write_comment') }}"></textarea>
                    @error('replyText') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    <button type="submit" class="rounded-md bg-blue-600 px-3 py-1 text-sm text-white">{{ __('comment::txt.submit') }}</button>
                </form>
            @endif

            @if($showReplies && $comment->nestedComments->isNotEmpty())
                <div class="ms-4 mt-3 space-y-3 border-s border-gray-200 ps-4 dark:border-gray-700">
                    @foreach($comment->nestedComments as $nested)
                        @continue(! $commentPolicy->see(auth()->user(), $nested))
                        <livewire:comments-comment
                            :key="$nested->id"
                            :comment="$nested"
                            :show-avatar="$showAvatar"
                            :newest-first="$newestFirst"
                            :writable="$writable"
                            :show-replies="false"
                            :show-reactions="$showReactions"
                        />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
