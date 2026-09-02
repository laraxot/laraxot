<div class="relative" x-data="{ tooltip: false }">
    <button type="button" @mouseover="tooltip = true" @mouseleave="tooltip = false"
    @click="tooltip = !tooltip"
    class="flex items-center gap-1.5 p-1.5 hover:bg-[#d8e6f1] bg-transparent cursor-pointer rounded">
    <x-heroicon-o-clock class="size-4"/>
    <span {{-- x-text="formatDate(market.bet_end_date)" --}} class="text-sm text-[#666666]"></span>
    </button>
    <!-- tooltip -->
    <div x-cloak x-show.transition.origin.top="tooltip"
        class="absolute z-10 inline-block w-max bottom-11 shadow-[0_2px_8px_0_#00000026] bg-white text-sm text-[#6b6a6a] rounded-lg">
        <div class="p-4 flex flex-col gap-2.5">
            <p class="text-sm font-semibold leading-none text-black">
                Bet end date
            </p>
            <p class="text-sm leading-none text-neutral-3 font-roboto whitespace-nowrap"
                {{-- x-text="formatDate(market.bet_end_date,year:'numeric',month:'long',day:'numeric')" --}}>
                {{ $article->closed_at_date }}
            </p>
        </div>
        <div
            class="absolute size-3 bg-inherit rotate-45 bottom-0 translate-y-1/2 left-12 border-b border-r border-[#00000026]">
        </div>
    </div>
</div>