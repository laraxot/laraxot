@php
/**
 * Predict Comments Widget View
 * 
 * @var Modules\Predict\Filament\Widgets\PredictCommentsWidget $this
 * @var Illuminate\Contracts\View\View $this
 */
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Header --}}
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                <span>{{ __('predict::comments.widget.title') }}</span>
            </div>
        </x-slot>

        {{-- Form Nuovo Commento --}}
        <div class="mb-6">
            <form wire:submit="submitComment" class="space-y-3">
                <textarea
                    id="new-comment"
                    wire:model="newComment"
                    placeholder="{{ __('predict::comments.form.placeholder') }}"
                    rows="3"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500"
                    required
                    minlength="10"
                    maxlength="2000"
                    aria-label="{{ __('predict::comments.form.label') }}"
                ></textarea>

                @error('newComment')
                    <p class="text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror

                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('predict::comments.form.help') }}
                    </p>
                    
                    <x-filament::button type="submit" color="primary">
                        <x-heroicon-o-paper-airplane class="w-4 h-4 mr-2" />
                        {{ __('predict::comments.form.submit') }}
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- Ordinamento --}}
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $this->table->getTotalRecords() }} {{ __('predict::comments.count') }}
            </p>

            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('predict::comments.sort.label') }}
                </span>

                <x-filament::select
                    wire:model.live="sort"
                    size="sm"
                    class="w-40"
                >
                    <x-filament::select.option value="popular">
                        <x-heroicon-o-hand-thumb-up class="w-4 h-4 inline mr-1" />
                        {{ __('predict::comments.sort.popular') }}
                    </x-filament::select.option>
                    <x-filament::select.option value="recent">
                        <x-heroicon-o-clock class="w-4 h-4 inline mr-1" />
                        {{ __('predict::comments.sort.recent') }}
                    </x-filament::select.option>
                    <x-filament::select.option value="oldest">
                        <x-heroicon-o-calendar class="w-4 h-4 inline mr-1" />
                        {{ __('predict::comments.sort.oldest') }}
                    </x-filament::select.option>
                </x-filament::select>
            </div>
        </div>

        {{-- Tabella Commenti --}}
        {{ $this->table }}
    </x-filament::section>
</x-filament-widgets::widget>
