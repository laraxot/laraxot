<!-- Modal Content -->
<div wire:ignore 
     x-data="{
        pricePerShare: 0,
        toWinAmount: 0,
        betAmount: @entangle('betAmount').live,
        currentRatingChoice: @entangle('ratingchoice').live,
        prices: @js($current_prices),

        getCurrentPrice() {
            return this.prices[this.currentRatingChoice] || 'N/A';
        },

        incrementAmount() {
            let current = parseFloat(this.betAmount) || 0;
            this.betAmount = (current + 50).toFixed(2);
        },

        decrementAmount() {
            let current = parseFloat(this.betAmount) || 0;
            this.betAmount = Math.max(0, current - 50).toFixed(2);
        },

        async calculateBetValues() {
            let result = await $wire.getCurrentBetCalculation();
            this.toWinAmount = result.to_win || 0;
            this.pricePerShare = result.price_per_share || 0;
        }
     }"

     x-init="
        $nextTick(() => {
            calculateBetValues();
        });
        $watch('betAmount', () => calculateBetValues());
        $watch('currentRatingChoice', () => calculateBetValues());
     "

     class="flex flex-col h-full">

    <!-- Content Wrapper -->
    <div class="flex-1">
        <!-- Outcome Selection -->
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 block mb-2">{{__('predict::bet.place-bet')}}</label>
            <select
                x-model="currentRatingChoice"
                wire:model="ratingchoice"
                class="w-full text-sm font-medium text-gray-900 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                @foreach ($rating_opts as $id => $title)
                <option value="{{ $id }}">{{ $title }}</option>
                @endforeach
            </select>
        </div>

        <!-- Amount Section -->
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 block mb-2">{{__('predict::bet.amount')}}</label>
            <div class="flex items-center border border-gray-300 rounded-full overflow-hidden shadow-sm">
                <!-- Minus Button -->
                <button
                    type="button"
                    class="w-12 h-12 text-gray-500 hover:text-gray-700 flex items-center justify-center focus:outline-none rounded-full"
                    x-on:click="decrementAmount"
                    wire:loading.attr="disabled">
                    <!-- Icon Minus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                    </svg>
                </button>
                <!-- Input Field -->
                <input
                    type="text"
                    x-model.debounce.100ms="betAmount"
                    wire:model.debounce.100ms="betAmount"
                    @input="$event.target.value = $event.target.value.replace(/[^0-9,.]/g, '')"
                    @blur="$event.target.value = parseFloat($event.target.value || 0).toFixed(2)"
                    class="flex-1 text-center text-base font-semibold text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 px-4 py-2 rounded-full"
                    inputmode="decimal"
                    pattern="[0-9]*[.,]?[0-9]*"
                    placeholder="0.00" />
                <!-- Plus Button -->
                <button
                    type="button"
                    class="w-12 h-12 text-gray-500 hover:text-gray-700 flex items-center justify-center focus:outline-none rounded-full"
                    x-on:click="incrementAmount"
                    wire:loading.attr="disabled">
                    <!-- Icon Plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                </button>
            </div>
            <!-- Error Message -->
            @error('betAmount')
            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Price and Winnings -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm font-medium text-gray-700">{{__('predict::bet.purchase-price')}}</p>
                <p class="text-sm font-medium text-gray-900"
                    x-text="parseFloat(pricePerShare).toFixed(4)"></p>
            </div>

            <!-- To Win -->
            <div>
                <p class="text-sm font-medium text-gray-700">{{__('predict::bet.if-you-win')}}</p>
                <p class="text-sm font-medium text-green-500"
                    x-text="parseFloat(toWinAmount).toFixed(2) + ' ø'"></p>
            </div>
        </div>
    </div>

    <!-- Place Bet Button -->
    <button
        wire:click="placeBet"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-75 cursor-not-allowed"
        class="w-full text-white bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 font-medium text-base rounded-full py-3 focus:outline-none shadow-md flex items-center justify-center gap-2 transition-all duration-200 mt-auto">
        <span wire:loading.remove wire:target="placeBet">
            {{__('predict::bet.your-bet')}}
        </span>
        <span wire:loading wire:target="placeBet">
            {{__('predict::bet.your-bet')}}
        </span>
        <x-filament::loading-indicator
            wire:loading
            wire:target="placeBet"
            class="w-5 h-5" />
    </button>
</div>