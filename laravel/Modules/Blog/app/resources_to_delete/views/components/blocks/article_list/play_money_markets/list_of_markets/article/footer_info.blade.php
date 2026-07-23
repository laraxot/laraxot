<div class="flex items-center justify-between px-6 lg:px-0">
    <div class="flex items-center gap-4 lg:gap-8">
        <div class="relative" x-data="{ tooltip: false }">
            <button type="button"
            class="flex items-center gap-4 lg:gap-8 rounded p-1.5 hover:bg-[#d8e6f1] cursor-pointer"
            @mouseover="tooltip = true" @mouseleave="tooltip = false" @click="tooltip = !tooltip">
            <div class="flex items-center gap-1.5">
                <x-heroicon-o-user class="size-4"/>
                <span {{-- x-text="market.wagers_count_total" --}} class="text-sm text-[#666666]"></span>
            </div>
            <div class="flex items-center gap-1.5">
                <x-heroicon-o-chart-bar class="size-4"/>
                <span {{-- x-text="formatCurrency(market.volume_play_money)+'ø'" --}}
                    class="text-sm text-[#666666]"></span>
            </div>
            </button>
            <!-- tooltip -->
            <div x-cloak x-show.transition.origin.top="tooltip"
                class="absolute z-10 inline-block w-[262px] bottom-11 shadow-[0_2px_8px_0_#00000026] bg-white text-sm text-[#6b6a6a] rounded-lg">
                <div class="p-4 flex flex-col gap-2.5">
                    <!-- forecasters -->
                    @include('predict::components.blocks.article_list.play_money_markets.list_of_markets.footer_info.forecasters')
                    <!-- volume -->
                    @include('predict::components.blocks.article_list.play_money_markets.list_of_markets.footer_info.volume')
                </div>
                <div
                    class="absolute size-3 bg-inherit rotate-45 bottom-0 translate-y-1/2 left-12 border-b border-r border-[#00000026]">
                </div>
            </div>
        </div>
        <!-- bet end -->
        @include('predict::components.blocks.article_list.play_money_markets.list_of_markets.footer_info.bet_end')
    </div>
    @include('predict::components.blocks.article_list.play_money_markets.list_of_markets.footer_info.view_real_money')
</div>