{{-- <div class="filament-widget"> --}}
<div>
    {{-- <x-filament::card> --}}
        @foreach($user_ratings as $rating)
            <!-- Nuova Card -->
            <div class="p-4 border border-gray-300 rounded-2xl shadow-md bg-white space-y-4">
                <div class="flex items-center">
                    <!-- Icon -->
                    <img src="{{ asset($rating['image']) }}" alt="Bet Icon" class="w-10 h-10 rounded-full">
                    <!-- Text -->
                    <div class="ml-3 flex-grow">
                        <p class="text-sm font-semibold text-gray-500">{{ __('predict::article.your_bet') }}</p>
                        <p class="text-lg font-bold text-gray-800">{{ $rating['title'] }}</p>
                    </div>
                    <!-- Button -->
                    <button class="bg-blue-600 text-white text-sm font-semibold px-5 py-2 rounded hover:bg-blue-700"
                        wire:click="sell('{{ $rating['predict_victory'] }}')">
                        <x-filament::loading-indicator class="w-5 h-5" wire:loading wire:target="sell('{{ $rating['predict_victory'] }}')"/>
                        {{ __('predict::article.sell') }}</button>
                </div>
                <div class="grid grid-cols-3 items-center mt-4 text-sm gap-4">
                    <!-- Previsione -->
                    <div class="text-center">
                        <p class="text-gray-500">{{ __('predict::article.your_amount') }}</p>
                        <p class="font-bold">{{ $rating['credit'] }}</p>
                    </div>
                    <!-- Se vinci -->
                    <div class="text-center">
                        <p class="text-gray-500">{{ __('predict::article.if_win') }}</p>
                        <div class="flex items-center justify-center">
                            <span class="font-bold text-green-600">{{ $rating['predict_victory'] }}</span>
                            <div class="ml-2">
                                @include('ui::svg.credit')
                            </div>
                        </div>
                    </div>
                    <!-- Sell for -->
                    <div class="text-center">
                        <p class="text-gray-500">predict::article.sell_for</p>
                        <div class="flex items-center justify-center">
                            <span class="font-bold text-red-600">{{ $rating['predict_victory'] }}</span>
                            <div class="ml-2">
                                @include('ui::svg.credit')
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            {{-- <div class="p-4 space-y-2 border rounded-2xl">
                <div class="grid grid-cols-4">
                    <div class="flex items-center col-span-2 space-x-4">
                        <div class="relative w-10 h-10 rounded-lg overflow-clip">
                            <img src="{{ asset($rating['image']) }}" class="absolute inset-0 object-cover border-none aspect-square" alt="">
                        </div>
                        <div>
                            <div class="flex items-center space-x-1">
                                <div class="text-sm text-gray-500">{{ __('predict::article.your_bet') }}</div>
                            </div>
                            <div class="font-bold">{{ $rating['title'] }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm text-gray-500">{{ __('predict::article.your_amount') }}</div>
                        <div class="flex items-center space-x-1">
                            <div class="font-bold">{{ $rating['credit'] }}</div>
                        </div>
                    </div>
            
                    <div>
                        <div>
                            <div class="text-sm text-gray-500">{{ __('predict::article.if_win') }}</div>
                            <div class="flex items-center font-bold text-teal-500">
                                {{ $rating['predict_victory'] }}
                                <div class="ml-2">
                                    @include('ui::svg.credit')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="w-full px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600"
                        wire:click="sell('{{ $rating['predict_victory'] }}')">
                        <x-filament::loading-indicator class="w-5 h-5" wire:loading wire:target="sell('{{ $rating['predict_victory'] }}')"/>
                        {{ __('predict::article.sell') }}
                    </button>
                </div>
            </div> --}}
        @endforeach
    {{-- </x-filament::card> --}}

    <x-filament-actions::modals />
</div>

