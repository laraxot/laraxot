<x-filament-panels::page>
    <div class="space-y-8">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                {{ __('platform.navigation_settings.title') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                {{ __('platform.navigation_settings.intro') }}
            </p>
        </div>

        @php
            $pill = function (bool $enabled): string {
                return $enabled
                    ? 'bg-primary-600 text-white hover:bg-primary-500'
                    : 'bg-gray-200 text-gray-600 hover:bg-gray-300 dark:bg-white/10 dark:text-gray-300 dark:hover:bg-white/20';
            };
        @endphp

        {{-- Modules --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                {{ __('platform.navigation_settings.modules') }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('platform.navigation_settings.modules_hint') }}
            </p>

            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($tree['modules'] as $module)
                    <button
                        type="button"
                        wire:click="toggle('{{ $module['key'] }}')"
                        wire:loading.attr="disabled"
                        class="flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-sm font-medium transition {{ $pill($module['enabled']) }}"
                    >
                        <span class="truncate">{{ $module['label'] }}</span>
                        <span class="text-xs">
                            {{ $module['enabled'] ? __('platform.navigation_settings.on') : __('platform.navigation_settings.off') }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Panels -> groups -> items --}}
        @foreach ($tree['panels'] as $panel)
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                    {{ $panel['label'] }}
                </h3>

                <div class="mt-4 space-y-5">
                    @foreach ($panel['groups'] as $group)
                        <div class="rounded-lg border border-gray-100 dark:border-white/5">
                            <div class="flex items-center justify-between gap-3 border-b border-gray-100 bg-gray-50 px-4 py-2 dark:border-white/5 dark:bg-white/5">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    {{ $group['label'] !== '' ? $group['label'] : __('platform.navigation_settings.ungrouped') }}
                                </span>

                                @if ($group['key'] !== null)
                                    <button
                                        type="button"
                                        wire:click="toggle('{{ $group['key'] }}')"
                                        wire:loading.attr="disabled"
                                        class="rounded-full px-3 py-1 text-xs font-medium transition {{ $pill($group['enabled']) }}"
                                    >
                                        {{ $group['enabled'] ? __('platform.navigation_settings.group_on') : __('platform.navigation_settings.group_off') }}
                                    </button>
                                @endif
                            </div>

                            <div class="divide-y divide-gray-100 dark:divide-white/5">
                                @foreach ($group['items'] as $item)
                                    <div class="flex items-center justify-between gap-3 px-4 py-2.5">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm text-gray-800 dark:text-gray-100">{{ $item['label'] }}</p>
                                            <p class="truncate text-xs text-gray-400 dark:text-gray-500">{{ $item['module'] }}</p>
                                        </div>

                                        @if ($item['protected'])
                                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500 dark:bg-white/10 dark:text-gray-400">
                                                <x-heroicon-m-lock-closed class="h-3.5 w-3.5" />
                                                {{ __('platform.navigation_settings.protected') }}
                                            </span>
                                        @else
                                            <button
                                                type="button"
                                                wire:click="toggle('{{ $item['key'] }}')"
                                                wire:loading.attr="disabled"
                                                class="rounded-full px-3 py-1 text-xs font-medium transition {{ $pill($item['enabled']) }}"
                                            >
                                                {{ $item['enabled'] ? __('platform.navigation_settings.on') : __('platform.navigation_settings.off') }}
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
