<form wire:submit="comment" class="relative mb-4 space-y-2">
    <label for="new-comment-text" class="sr-only">{{ __('comment::txt.write_comment') }}</label>
    <textarea
        id="new-comment-text"
        @if($commentConfig->mentions['enabled'] ?? false) wire:model.live="ui.text" @else wire:model="ui.text" @endif
        class="w-full rounded-md border border-gray-300 p-3 text-sm dark:border-gray-600 dark:bg-gray-800"
        rows="3"
        placeholder="{{ __('comment::txt.write_comment') }}"
        required
    ></textarea>
    @if($commentConfig->mentions['enabled'] ?? false)
        @livewire(\Modules\Comment\Filament\Widgets\Mention\MentionSearchWidget::class)
    @endif
    @error('ui.text')
        <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror
    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        {{ __('comment::txt.submit') }}
    </button>
</form>
