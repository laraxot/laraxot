<div wire:ignore x-data="{
    betAmount: @entangle('betAmount').live,
    currentRatingChoice: @entangle('ratingId').live,
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
}" x-init="$nextTick(() => {
    calculateBetValues();
});
$watch('betAmount', () => calculateBetValues());
$watch('currentRatingChoice', () => calculateBetValues());" class="flex flex-col h-full">
    <div class="flex-1">
        <div x-data="{
            open: false,
            options: @js($rating_opts),
            images: @js($rating_images),
            selected: @entangle('ratingId').live
        }" class="relative mb-6">

            <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('predict::bet.place-bet') }}</label>

            <!-- Selected option -->
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between border border-gray-300 rounded-lg px-3 py-2 bg-white shadow-sm">
                <div class="flex items-center gap-2">
                    <template x-if="selected && images[selected]">
                        <img :src="images[selected]" alt="" class="w-6 h-6 rounded-full object-cover">
                    </template>
                    <span x-text="options[selected] || '{{ __('Select') }}'"></span>
                </div>
                <svg class="w-4 h-4 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Options list -->
            <div x-show="open" @click.away="open = false"
                class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                <template x-for="(title, id) in options" :key="id">
                    <button type="button" @click="selected = id; open = false"
                        class="w-full flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                        <img :src="images[id]" alt="" class="w-6 h-6 rounded-full object-cover">
                        <span x-text="title"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
    <div class="mb-6">
        <label class="text-sm font-medium text-gray-700 block mb-2">{{ __('predict::bet.amount') }}</label>
        <div class="flex items-center border border-gray-300 rounded-full overflow-hidden shadow-sm">
            <!-- Minus Button -->
            <button type="button"
                class="w-12 h-12 text-gray-500 hover:text-gray-700 flex items-center justify-center focus:outline-none rounded-full"
                x-on:click="decrementAmount" wire:loading.attr="disabled">
                <!-- Icon Minus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                </svg>
            </button>
            <!-- Input Field -->
            <input type="text" x-model.debounce.500ms="betAmount" wire:model.debounce.500ms="betAmount"
                @blur="
    let val = parseFloat(betAmount.replace(',', '.'));
    betAmount = isNaN(val) ? '0.00' : val.toFixed(2);
  "
                class="flex-1 text-center text-base font-semibold text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 px-4 py-2 rounded-full"
                inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" placeholder="0.00" />
            <!-- Plus Button -->
            <button type="button"
                class="w-12 h-12 text-gray-500 hover:text-gray-700 flex items-center justify-center focus:outline-none rounded-full"
                x-on:click="incrementAmount" wire:loading.attr="disabled">
                <!-- Icon Plus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                </svg>
            </button>
        </div>
        <!-- Error Message -->
        @error('betAmount')
            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>
