<div class="space-y-4">
    <h2 class="font-semibold text-2xl text-neutral-5">{{ $title }}</h2>
    {{-- {{ dddx(get_defined_vars()) }} --}}
    {{-- {{ dddx($_theme->getMethodData($method)) }} --}}
    <div class="space-y-4">

        @foreach($_theme->getMethodData($method) as $item)
            {{-- {{ dddx($item->getTimeLeftForHumans()) }} --}}
            <article class="bg-white hover:bg-[#c2ced1] rounded-lg p-4">
                <a
                    href="{{ route('article.view', ['lang'=>$lang,'slug' => $item->slug ]) }}"
                    class="text-neutral-5 text-base font-semibold"
                    >
                {{ $item->title }}
                </a>
                <div class="mt-2.5 flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        <svg
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            class="question-card__person-icon"
                            fill="#292929"
                            width="13px"
                            >
                            <path
                                fill-rule="evenodd"
                                d="M11.5 4C9.02 4 7 6.02 7 8.5S9.02 13 11.5 13 16 10.98 16 8.5 13.98 4 11.5 4M8.744 14.386A6.51 6.51 0 0 1 5 8.5C5 4.916 7.916 2 11.5 2S18 4.916 18 8.5a6.51 6.51 0 0 1-3.744 5.886C18.156 15.508 21 18.946 21 23h-2c0-3.97-3.309-7-7.5-7S4 19.03 4 23H2c0-4.055 2.845-7.492 6.744-8.614z"
                                ></path>
                        </svg>
                        <span class="text-sm text-[#666666]">
                            {{-- 3212 --}}
                            {{ $item->betting_users }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg
                            viewBox="0 0 16 17"
                            fillcolor="#292929"
                            width="13px"
                            xmlns="http://www.w3.org/2000/svg"
                            >
                            <path
                                d="M0.5 9H3.83333V16.5H0.5V9ZM12.1667 5.66667H15.5V16.5H12.1667V5.66667ZM6.33333 0.666668H9.66667V16.5H6.33333V0.666668Z"
                                fill="#6B6A6A"
                                ></path>
                        </svg>
                        <span class="text-sm text-[#666666]">
                            {{-- 3364798  --}}
                            {{ $item->volume_credit }} ø
                        </span>
                    </div>
                    <!-- Bet end date -->
                    <div class="flex items-center gap-1">
                        <span>
                            <svg
                                fill="none"
                                width="16px"
                                viewBox="0 0 14 14"
                                xmlns="http://www.w3.org/2000/svg"
                                >
                                <path
                                    d="M7 13.6667C6.21207 13.6667 5.43185 13.5115 4.7039 13.2099C3.97594 12.9084 3.31451 12.4665 2.75736 11.9093C2.2002 11.3522 1.75825 10.6907 1.45672 9.96276C1.15519 9.23481 0.999997 8.45459 0.999997 7.66666C0.999997 6.87873 1.15519 6.09852 1.45672 5.37056C1.75825 4.64261 2.2002 3.98117 2.75736 3.42402C3.31451 2.86687 3.97594 2.42491 4.7039 2.12339C5.43185 1.82186 6.21207 1.66666 7 1.66666C8.5913 1.66666 10.1174 2.2988 11.2426 3.42402C12.3679 4.54924 13 6.07536 13 7.66666C13 9.25796 12.3679 10.7841 11.2426 11.9093C10.1174 13.0345 8.5913 13.6667 7 13.6667ZM7 12.3333C7.61283 12.3333 8.21967 12.2126 8.78585 11.9781C9.35204 11.7436 9.86649 11.3998 10.2998 10.9665C10.7332 10.5332 11.0769 10.0187 11.3114 9.45252C11.546 8.88633 11.6667 8.2795 11.6667 7.66666C11.6667 7.05383 11.546 6.44699 11.3114 5.88081C11.0769 5.31462 10.7332 4.80017 10.2998 4.36683C9.86649 3.93349 9.35204 3.58975 8.78585 3.35522C8.21967 3.1207 7.61283 3 7 3C5.76232 3 4.57534 3.49166 3.70017 4.36683C2.825 5.242 2.33333 6.42899 2.33333 7.66666C2.33333 8.90434 2.825 10.0913 3.70017 10.9665C4.57534 11.8417 5.76232 12.3333 7 12.3333ZM7.66666 7.66666H9.66666V9H6.33333V4.33333H7.66666V7.66666ZM0.16333 3.18866L2.52066 0.831329L3.46333 1.774L1.10666 4.13133L0.163997 3.18866H0.16333ZM11.4767 0.831329L13.834 3.18866L12.8913 4.13133L10.534 1.774L11.4767 0.831329Z"
                                    fill="#292929"
                                    ></path>
                            </svg>
                        </span>
                        <span class="text-sm text-[#666666]">{{ $item->closed_at_date }}</span>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>