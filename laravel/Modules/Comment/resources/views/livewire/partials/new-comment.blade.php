<form wire:submit="comment" class="mb-4 space-y-2">
    <label for="new-comment-text" class="sr-only">{{ __('comment::txt.write_comment') }}</label>
    <textarea
        id="new-comment-text"
        wire:model="text"
        class="w-full rounded-md border border-gray-300 p-3 text-sm dark:border-gray-600 dark:bg-gray-800"
        rows="3"
        placeholder="{{ __('comment::txt.write_comment') }}"
        required
    ></textarea>
    @error('text')
        <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror
    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        {{ __('comment::txt.submit') }}
    </button>
</form>
