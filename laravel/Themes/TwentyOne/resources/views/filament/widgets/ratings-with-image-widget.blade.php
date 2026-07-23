<div>
    <article class="bg-white p-6 lg:p-[18px] rounded-lg border flex flex-col xot-modal-place-bet overflow-hidden"
        style="max-height: 270px;">
        <!-- Grid dei ratings -->

        @if ($openRatingId)
            <div class="p-4 bg-gray-100 rounded-lg flex flex-col gap-4">
                <!-- Example: replace with your real component -->
                <h3 class="text-lg font-bold">Place bet for: sdfgsdgd
                </h3>

                <!-- Your custom bet form here... -->
                @livewire(\Modules\Predict\Http\Livewire\Widgets\BetFormWidget::class, [
                    'article' => $article,
                    'ratingId' => $openRatingId,
                ])
                {{-- <livewire:your-bet-form :rating-id="$openRatingId" :article="$article" /> --}}

                <!-- Back button -->
                <button wire:click="$set('openRatingId', null)"
                    class="flex items-center gap-2 text-blue-600 hover:underline">
                    <!-- Back arrow SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 rotate-180" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </button>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 flex-1 [grid-auto-rows:1fr]"
                style="height: 232px; max-height: 232px;">
                @foreach ($ratings as $rating)
                    {{-- <button wire:click="openBetModal('{{ $rating['id'] }}')" --}}
                    <button wire:click="$set('openRatingId', '{{ $rating['id'] }}')"
                        class="relative block h-full overflow-hidden rounded-lg
                        {{ $rating_title == $rating['title'] ? 'border-[3px] border-blue-600 shadow-lg shadow-blue-300' : '' }}"
                        wire:loading.class="opacity-50" {{-- wire:target="openBetModal('{{ $rating['id'] }}')" --}}>

                        <!-- Loading indicator -->
                        <div class="absolute inset-0 z-10 grid pointer-events-none place-items-center">
                            <x-filament::loading-indicator class="w-5 h-5" wire:loading
                                wire:target="openBetModal('{{ $rating['id'] }}')" />
                        </div>

                        <!-- Immagine e overlay -->
                        <figure class="h-full w-full">
                            <img class="object-cover w-full h-full" alt="{{ $rating['title'] }}"
                                title="{{ $rating['title'] }}" src="{{ $rating['image'] }}" loading="lazy" />
                        </figure>
                        <div class="absolute inset-0 transition bg-transparent hover:bg-blue-500/30"></div>

                        <!-- Percentuale e titolo -->
                        <div
                            class="p-1.5 absolute inset-0 flex flex-col text-start justify-between pointer-events-none">
                            <div @class([
                                'flex items-center justify-center h-8 rounded-sm bg-neutral-5 w-11',
                                'text-white' => !$rating_title || $rating_title == $rating['title'],
                                'text-gray-400' => $rating_title && $rating_title != $rating['title'],
                            ])>
                                <span>{{ $ratings_percentage[$rating['id']] }}%</span>
                            </div>
                            <p class="text-sm font-medium text-white leading-[1.1]">
                                {{ $rating['title'] }}
                            </p>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </article>

    <!-- Modals per ogni rating -->
    @foreach ($ratings as $rating)
        <x-filament::modal id="modal-rating-{{ $rating['id'] }}" :close-button="true" :close-by-clicking-away="true">
            <x-slot name="heading">
                {{ $article->title }}
            </x-slot>

            <!-- Error message -->
            @error('bet')
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ $message }}
                </div>
            @enderror

            @if (Auth::guest())
                @include('pub_theme::filament.widgets.ratings-widget.guest')
            @elseif(Auth::check() && 'expired' === $article->getTimeLeftForHumans())
                @include('pub_theme::filament.widgets.ratings-widget.check_expired')
            @else
                @include('pub_theme::filament.widgets.ratings-widget.check', [
                    'rating' => $rating,
                    'currentPrice' => $current_prices[$rating['id']] ?? null,
                ])
            @endif
        </x-filament::modal>
    @endforeach
</div>
