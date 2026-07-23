<div
    wire:key="mention-search"
    x-data="{ show: @entangle('ui.showDropdown') }"
    x-show="show"
    x-cloak
    class="relative"
>
    <div class="absolute z-50 mt-1 w-full rounded-md border border-gray-300 bg-white shadow-lg dark:border-gray-600 dark:bg-gray-800">
        @if($results->isNotEmpty())
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($results as $user)
                    <li>
                        <button
                            type="button"
                            wire:click="selectUser({{ $user->getKey() }})"
                            class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <span>{{ $user->{$commentConfig->models['name'] ?? 'name'} }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="px-3 py-2 text-sm text-gray-500">{{ __('comment::txt.no_results') }}</p>
        @endif
    </div>
</div>
