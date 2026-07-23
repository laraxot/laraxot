<div class="py-4 bg-white mt-4 p-4 rounded">
    <h4 class="font-semibold text-lg font-roboto text-neutral-5">
        Related Markets
    </h4>
    <div class="flex flex-col gap-4 mt-4">
        <div class="px-4 py-3 border border-neutral-2 rounded-lg">
            <a
                class="mb-3 sm:max-w-full text-xl font-semibold text-neutral-4 block"
                href="#next-target-for-the-us-interest-rate-in-march"
                >Next target for the US interest rate in March</a
                >
            <div class="flex items-center gap-4 lg:gap-8">
                <div class="relative" x-data="{ tooltip: false }">
                    <button
                    type="button"
                    class="flex items-center gap-4 lg:gap-8 rounded p-1.5 hover:bg-[#d8e6f1] cursor-pointer"
                    @mouseover="tooltip = true"
                    @mouseleave="tooltip = false"
                    @click="tooltip = !tooltip"
                    >
                    <div class="flex items-center gap-1.5">
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
                        <span
                            x-text="market.wagers_count_total"
                            class="text-sm text-[#666666]"
                            >8</span
                            >
                    </div>
                    <div class="flex items-center gap-1.5">
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
                        <span class="text-sm text-[#666666]">10,910 ø</span>
                    </div>
                    </button>
                    <!-- tooltip -->
                    <div
                        x-show.transition.origin.top="tooltip"
                        class="absolute z-10 inline-block w-[262px] bottom-11 shadow-[0_2px_8px_0_#00000026] bg-white text-sm text-[#6b6a6a] rounded-lg"
                        style="display: none"
                        >
                        <div class="p-4 flex flex-col gap-2.5">
                            <template x-if="market.wagers_count_total > 0">
                                <div>
                                    <!-- forecasters -->
                                    <div class="flex flex-col gap-2.5 mb-2.5">
                                        <p class="text-black font-semibold text-sm">
                                            Forecasters
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 20 20"
                                                width="16px"
                                                >
                                                <path
                                                    fill="white"
                                                    d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10"
                                                    ></path>
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-5-.01c0-1.341-.47-2.584-1.311-3.491l1.057-1.262H13.18l-.43.493C12.005 5.276 11.066 5 9.99 5 6.88 5 5 7.288 5 9.99c0 1.322.45 2.544 1.272 3.452l-1.115 1.321h1.526l.47-.552c.763.493 1.722.789 2.837.789C13.121 15 15 12.712 15 9.99zm-7.084 1.5a3.374 3.374 0 01-.333-1.5c0-1.48.861-2.761 2.407-2.761.47 0 .88.118 1.233.335zm4.5-1.5c0 1.5-.88 2.781-2.426 2.781-.509 0-.94-.138-1.291-.375l3.346-3.964c.235.454.372.986.372 1.558z"
                                                    fill="#1591ed"
                                                    ></path>
                                            </svg>
                                            <span
                                                class="text-sm text-neutral-3"
                                                x-text="market.wagers_count+' in play money'"
                                                ></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg
                                                width="16px"
                                                viewBox="0 0 18 18"
                                                xmlns="http://www.w3.org/2000/svg"
                                                >
                                                <path
                                                    d="M8.99935 17.3334C4.39685 17.3334 0.666016 13.6026 0.666016 9.00008C0.666016 4.39758 4.39685 0.666748 8.99935 0.666748C13.6018 0.666748 17.3327 4.39758 17.3327 9.00008C17.3327 13.6026 13.6018 17.3334 8.99935 17.3334ZM6.08268 10.6667V12.3334H8.16602V14.0001H9.83268V12.3334H10.666C11.2185 12.3334 11.7485 12.1139 12.1392 11.7232C12.5299 11.3325 12.7493 10.8026 12.7493 10.2501C12.7493 9.69755 12.5299 9.16764 12.1392 8.77694C11.7485 8.38624 11.2185 8.16675 10.666 8.16675H7.33268C7.22218 8.16675 7.11619 8.12285 7.03805 8.04471C6.95991 7.96657 6.91602 7.86059 6.91602 7.75008C6.91602 7.63957 6.95991 7.53359 7.03805 7.45545C7.11619 7.37731 7.22218 7.33341 7.33268 7.33341H11.916V5.66675H9.83268V4.00008H8.16602V5.66675H7.33268C6.78015 5.66675 6.25024 5.88624 5.85954 6.27694C5.46884 6.66764 5.24935 7.19755 5.24935 7.75008C5.24935 8.30262 5.46884 8.83252 5.85954 9.22322C6.25024 9.61392 6.78015 9.83341 7.33268 9.83341H10.666C10.7765 9.83341 10.8825 9.87731 10.9606 9.95545C11.0388 10.0336 11.0827 10.1396 11.0827 10.2501C11.0827 10.3606 11.0388 10.4666 10.9606 10.5447C10.8825 10.6228 10.7765 10.6667 10.666 10.6667H6.08268Z"
                                                    fill="#00D966"
                                                    ></path>
                                            </svg>
                                            <span
                                                class="text-sm text-neutral-3"
                                                x-text="market.wagers_count_canonical+' in real money'"
                                                ></span>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            </template>
                            <div>
                                <!-- forecasters -->
                                <div class="flex flex-col gap-2.5 mb-2.5">
                                    <p class="text-black font-semibold text-sm">
                                        Forecasters
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 20 20"
                                            width="16px"
                                            >
                                            <path
                                                fill="white"
                                                d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10"
                                                ></path>
                                            <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-5-.01c0-1.341-.47-2.584-1.311-3.491l1.057-1.262H13.18l-.43.493C12.005 5.276 11.066 5 9.99 5 6.88 5 5 7.288 5 9.99c0 1.322.45 2.544 1.272 3.452l-1.115 1.321h1.526l.47-.552c.763.493 1.722.789 2.837.789C13.121 15 15 12.712 15 9.99zm-7.084 1.5a3.374 3.374 0 01-.333-1.5c0-1.48.861-2.761 2.407-2.761.47 0 .88.118 1.233.335zm4.5-1.5c0 1.5-.88 2.781-2.426 2.781-.509 0-.94-.138-1.291-.375l3.346-3.964c.235.454.372.986.372 1.558z"
                                                fill="#1591ed"
                                                ></path>
                                        </svg>
                                        <span
                                            class="text-sm text-neutral-3"
                                            x-text="market.wagers_count+' in play money'"
                                            >5 in play money</span
                                            >
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            width="16px"
                                            viewBox="0 0 18 18"
                                            xmlns="http://www.w3.org/2000/svg"
                                            >
                                            <path
                                                d="M8.99935 17.3334C4.39685 17.3334 0.666016 13.6026 0.666016 9.00008C0.666016 4.39758 4.39685 0.666748 8.99935 0.666748C13.6018 0.666748 17.3327 4.39758 17.3327 9.00008C17.3327 13.6026 13.6018 17.3334 8.99935 17.3334ZM6.08268 10.6667V12.3334H8.16602V14.0001H9.83268V12.3334H10.666C11.2185 12.3334 11.7485 12.1139 12.1392 11.7232C12.5299 11.3325 12.7493 10.8026 12.7493 10.2501C12.7493 9.69755 12.5299 9.16764 12.1392 8.77694C11.7485 8.38624 11.2185 8.16675 10.666 8.16675H7.33268C7.22218 8.16675 7.11619 8.12285 7.03805 8.04471C6.95991 7.96657 6.91602 7.86059 6.91602 7.75008C6.91602 7.63957 6.95991 7.53359 7.03805 7.45545C7.11619 7.37731 7.22218 7.33341 7.33268 7.33341H11.916V5.66675H9.83268V4.00008H8.16602V5.66675H7.33268C6.78015 5.66675 6.25024 5.88624 5.85954 6.27694C5.46884 6.66764 5.24935 7.19755 5.24935 7.75008C5.24935 8.30262 5.46884 8.83252 5.85954 9.22322C6.25024 9.61392 6.78015 9.83341 7.33268 9.83341H10.666C10.7765 9.83341 10.8825 9.87731 10.9606 9.95545C11.0388 10.0336 11.0827 10.1396 11.0827 10.2501C11.0827 10.3606 11.0388 10.4666 10.9606 10.5447C10.8825 10.6228 10.7765 10.6667 10.666 10.6667H6.08268Z"
                                                fill="#00D966"
                                                ></path>
                                        </svg>
                                        <span
                                            class="text-sm text-neutral-3"
                                            x-text="market.wagers_count_canonical+' in real money'"
                                            >3 in real money</span
                                            >
                                    </div>
                                </div>
                                <hr />
                            </div>
                            <!-- volume -->
                            <div class="flex flex-col gap-2.5">
                                <p class="text-black font-semibold text-sm">
                                    Volume
                                </p>
                                <p
                                    class="text-sm leading-none text-neutral-3 font-roboto"
                                    >
                                    Amount traded in a market, plus its initial
                                    liquity.
                                </p>
                                <div class="flex items-center gap-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 20 20"
                                        width="16px"
                                        >
                                        <path
                                            fill="white"
                                            d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10"
                                            ></path>
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-5-.01c0-1.341-.47-2.584-1.311-3.491l1.057-1.262H13.18l-.43.493C12.005 5.276 11.066 5 9.99 5 6.88 5 5 7.288 5 9.99c0 1.322.45 2.544 1.272 3.452l-1.115 1.321h1.526l.47-.552c.763.493 1.722.789 2.837.789C13.121 15 15 12.712 15 9.99zm-7.084 1.5a3.374 3.374 0 01-.333-1.5c0-1.48.861-2.761 2.407-2.761.47 0 .88.118 1.233.335zm4.5-1.5c0 1.5-.88 2.781-2.426 2.781-.509 0-.94-.138-1.291-.375l3.346-3.964c.235.454.372.986.372 1.558z"
                                            fill="#1591ed"
                                            ></path>
                                    </svg>
                                    <span class="text-sm text-neutral-3"
                                        >10,910 ø in play money</span
                                        >
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg
                                        width="16px"
                                        viewBox="0 0 18 18"
                                        xmlns="http://www.w3.org/2000/svg"
                                        >
                                        <path
                                            d="M8.99935 17.3334C4.39685 17.3334 0.666016 13.6026 0.666016 9.00008C0.666016 4.39758 4.39685 0.666748 8.99935 0.666748C13.6018 0.666748 17.3327 4.39758 17.3327 9.00008C17.3327 13.6026 13.6018 17.3334 8.99935 17.3334ZM6.08268 10.6667V12.3334H8.16602V14.0001H9.83268V12.3334H10.666C11.2185 12.3334 11.7485 12.1139 12.1392 11.7232C12.5299 11.3325 12.7493 10.8026 12.7493 10.2501C12.7493 9.69755 12.5299 9.16764 12.1392 8.77694C11.7485 8.38624 11.2185 8.16675 10.666 8.16675H7.33268C7.22218 8.16675 7.11619 8.12285 7.03805 8.04471C6.95991 7.96657 6.91602 7.86059 6.91602 7.75008C6.91602 7.63957 6.95991 7.53359 7.03805 7.45545C7.11619 7.37731 7.22218 7.33341 7.33268 7.33341H11.916V5.66675H9.83268V4.00008H8.16602V5.66675H7.33268C6.78015 5.66675 6.25024 5.88624 5.85954 6.27694C5.46884 6.66764 5.24935 7.19755 5.24935 7.75008C5.24935 8.30262 5.46884 8.83252 5.85954 9.22322C6.25024 9.61392 6.78015 9.83341 7.33268 9.83341H10.666C10.7765 9.83341 10.8825 9.87731 10.9606 9.95545C11.0388 10.0336 11.0827 10.1396 11.0827 10.2501C11.0827 10.3606 11.0388 10.4666 10.9606 10.5447C10.8825 10.6228 10.7765 10.6667 10.666 10.6667H6.08268Z"
                                            fill="#00D966"
                                            ></path>
                                    </svg>
                                    <span class="text-sm text-neutral-3"
                                        >≈$102.42 in USD</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="absolute size-3 bg-inherit rotate-45 bottom-0 translate-y-1/2 left-12 border-b border-r border-[#00000026]"
                            ></div>
                    </div>
                </div>
                <!-- bet end -->
                <div class="relative" x-data="{ tooltip: false }">
                    <button
                    type="button"
                    @mouseover="tooltip = true"
                    @mouseleave="tooltip = false"
                    @click="tooltip = !tooltip"
                    class="flex items-center gap-1.5 p-1.5 hover:bg-[#d8e6f1] bg-transparent cursor-pointer rounded"
                    >
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
                    <span class="text-sm text-[#666666]">Mar 20, 2024</span>
                    </button>
                    <!-- tooltip -->
                    <div
                        x-show.transition.origin.top="tooltip"
                        class="absolute z-10 inline-block w-max bottom-11 shadow-[0_2px_8px_0_#00000026] bg-white text-sm text-[#6b6a6a] rounded-lg"
                        style="display: none"
                        >
                        <div class="p-4 flex flex-col gap-2.5">
                            <p
                                class="text-black font-semibold text-sm leading-none"
                                >
                                Bet end date
                            </p>
                            <p
                                class="text-sm leading-none text-neutral-3 font-roboto whitespace-nowrap"
                                x-text="formatDate(market.bet_end_date,{year: 'numeric', month:'long', day: 'numeric'})"
                                >
                                March 20, 2024
                            </p>
                        </div>
                        <div
                            class="absolute size-3 bg-inherit rotate-45 bottom-0 translate-y-1/2 left-12 border-b border-r border-[#00000026]"
                            ></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 border border-neutral-2 rounded-lg">
            <a
                class="mb-3 sm:max-w-full text-xl font-semibold text-neutral-4 block"
                href="#next-target-for-the-us-interest-rate-in-march"
                >Next target for the US interest rate in March</a
                >
            <div class="flex items-center gap-4 lg:gap-8">
                <div class="relative" x-data="{ tooltip: false }">
                    <button
                    type="button"
                    class="flex items-center gap-4 lg:gap-8 rounded p-1.5 hover:bg-[#d8e6f1] cursor-pointer"
                    @mouseover="tooltip = true"
                    @mouseleave="tooltip = false"
                    @click="tooltip = !tooltip"
                    >
                    <div class="flex items-center gap-1.5">
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
                        <span
                            x-text="market.wagers_count_total"
                            class="text-sm text-[#666666]"
                            >8</span
                            >
                    </div>
                    <div class="flex items-center gap-1.5">
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
                        <span class="text-sm text-[#666666]">10,910 ø</span>
                    </div>
                    </button>
                    <!-- tooltip -->
                    <div
                        x-show.transition.origin.top="tooltip"
                        class="absolute z-10 inline-block w-[262px] bottom-11 shadow-[0_2px_8px_0_#00000026] bg-white text-sm text-[#6b6a6a] rounded-lg"
                        style="display: none"
                        >
                        <div class="p-4 flex flex-col gap-2.5">
                            <template x-if="market.wagers_count_total > 0">
                                <div>
                                    <!-- forecasters -->
                                    <div class="flex flex-col gap-2.5 mb-2.5">
                                        <p class="text-black font-semibold text-sm">
                                            Forecasters
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 20 20"
                                                width="16px"
                                                >
                                                <path
                                                    fill="white"
                                                    d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10"
                                                    ></path>
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-5-.01c0-1.341-.47-2.584-1.311-3.491l1.057-1.262H13.18l-.43.493C12.005 5.276 11.066 5 9.99 5 6.88 5 5 7.288 5 9.99c0 1.322.45 2.544 1.272 3.452l-1.115 1.321h1.526l.47-.552c.763.493 1.722.789 2.837.789C13.121 15 15 12.712 15 9.99zm-7.084 1.5a3.374 3.374 0 01-.333-1.5c0-1.48.861-2.761 2.407-2.761.47 0 .88.118 1.233.335zm4.5-1.5c0 1.5-.88 2.781-2.426 2.781-.509 0-.94-.138-1.291-.375l3.346-3.964c.235.454.372.986.372 1.558z"
                                                    fill="#1591ed"
                                                    ></path>
                                            </svg>
                                            <span
                                                class="text-sm text-neutral-3"
                                                x-text="market.wagers_count+' in play money'"
                                                ></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg
                                                width="16px"
                                                viewBox="0 0 18 18"
                                                xmlns="http://www.w3.org/2000/svg"
                                                >
                                                <path
                                                    d="M8.99935 17.3334C4.39685 17.3334 0.666016 13.6026 0.666016 9.00008C0.666016 4.39758 4.39685 0.666748 8.99935 0.666748C13.6018 0.666748 17.3327 4.39758 17.3327 9.00008C17.3327 13.6026 13.6018 17.3334 8.99935 17.3334ZM6.08268 10.6667V12.3334H8.16602V14.0001H9.83268V12.3334H10.666C11.2185 12.3334 11.7485 12.1139 12.1392 11.7232C12.5299 11.3325 12.7493 10.8026 12.7493 10.2501C12.7493 9.69755 12.5299 9.16764 12.1392 8.77694C11.7485 8.38624 11.2185 8.16675 10.666 8.16675H7.33268C7.22218 8.16675 7.11619 8.12285 7.03805 8.04471C6.95991 7.96657 6.91602 7.86059 6.91602 7.75008C6.91602 7.63957 6.95991 7.53359 7.03805 7.45545C7.11619 7.37731 7.22218 7.33341 7.33268 7.33341H11.916V5.66675H9.83268V4.00008H8.16602V5.66675H7.33268C6.78015 5.66675 6.25024 5.88624 5.85954 6.27694C5.46884 6.66764 5.24935 7.19755 5.24935 7.75008C5.24935 8.30262 5.46884 8.83252 5.85954 9.22322C6.25024 9.61392 6.78015 9.83341 7.33268 9.83341H10.666C10.7765 9.83341 10.8825 9.87731 10.9606 9.95545C11.0388 10.0336 11.0827 10.1396 11.0827 10.2501C11.0827 10.3606 11.0388 10.4666 10.9606 10.5447C10.8825 10.6228 10.7765 10.6667 10.666 10.6667H6.08268Z"
                                                    fill="#00D966"
                                                    ></path>
                                            </svg>
                                            <span
                                                class="text-sm text-neutral-3"
                                                x-text="market.wagers_count_canonical+' in real money'"
                                                ></span>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            </template>
                            <div>
                                <!-- forecasters -->
                                <div class="flex flex-col gap-2.5 mb-2.5">
                                    <p class="text-black font-semibold text-sm">
                                        Forecasters
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 20 20"
                                            width="16px"
                                            >
                                            <path
                                                fill="white"
                                                d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10"
                                                ></path>
                                            <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-5-.01c0-1.341-.47-2.584-1.311-3.491l1.057-1.262H13.18l-.43.493C12.005 5.276 11.066 5 9.99 5 6.88 5 5 7.288 5 9.99c0 1.322.45 2.544 1.272 3.452l-1.115 1.321h1.526l.47-.552c.763.493 1.722.789 2.837.789C13.121 15 15 12.712 15 9.99zm-7.084 1.5a3.374 3.374 0 01-.333-1.5c0-1.48.861-2.761 2.407-2.761.47 0 .88.118 1.233.335zm4.5-1.5c0 1.5-.88 2.781-2.426 2.781-.509 0-.94-.138-1.291-.375l3.346-3.964c.235.454.372.986.372 1.558z"
                                                fill="#1591ed"
                                                ></path>
                                        </svg>
                                        <span
                                            class="text-sm text-neutral-3"
                                            x-text="market.wagers_count+' in play money'"
                                            >5 in play money</span
                                            >
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            width="16px"
                                            viewBox="0 0 18 18"
                                            xmlns="http://www.w3.org/2000/svg"
                                            >
                                            <path
                                                d="M8.99935 17.3334C4.39685 17.3334 0.666016 13.6026 0.666016 9.00008C0.666016 4.39758 4.39685 0.666748 8.99935 0.666748C13.6018 0.666748 17.3327 4.39758 17.3327 9.00008C17.3327 13.6026 13.6018 17.3334 8.99935 17.3334ZM6.08268 10.6667V12.3334H8.16602V14.0001H9.83268V12.3334H10.666C11.2185 12.3334 11.7485 12.1139 12.1392 11.7232C12.5299 11.3325 12.7493 10.8026 12.7493 10.2501C12.7493 9.69755 12.5299 9.16764 12.1392 8.77694C11.7485 8.38624 11.2185 8.16675 10.666 8.16675H7.33268C7.22218 8.16675 7.11619 8.12285 7.03805 8.04471C6.95991 7.96657 6.91602 7.86059 6.91602 7.75008C6.91602 7.63957 6.95991 7.53359 7.03805 7.45545C7.11619 7.37731 7.22218 7.33341 7.33268 7.33341H11.916V5.66675H9.83268V4.00008H8.16602V5.66675H7.33268C6.78015 5.66675 6.25024 5.88624 5.85954 6.27694C5.46884 6.66764 5.24935 7.19755 5.24935 7.75008C5.24935 8.30262 5.46884 8.83252 5.85954 9.22322C6.25024 9.61392 6.78015 9.83341 7.33268 9.83341H10.666C10.7765 9.83341 10.8825 9.87731 10.9606 9.95545C11.0388 10.0336 11.0827 10.1396 11.0827 10.2501C11.0827 10.3606 11.0388 10.4666 10.9606 10.5447C10.8825 10.6228 10.7765 10.6667 10.666 10.6667H6.08268Z"
                                                fill="#00D966"
                                                ></path>
                                        </svg>
                                        <span
                                            class="text-sm text-neutral-3"
                                            x-text="market.wagers_count_canonical+' in real money'"
                                            >3 in real money</span
                                            >
                                    </div>
                                </div>
                                <hr />
                            </div>
                            <!-- volume -->
                            <div class="flex flex-col gap-2.5">
                                <p class="text-black font-semibold text-sm">
                                    Volume
                                </p>
                                <p
                                    class="text-sm leading-none text-neutral-3 font-roboto"
                                    >
                                    Amount traded in a market, plus its initial
                                    liquity.
                                </p>
                                <div class="flex items-center gap-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 20 20"
                                        width="16px"
                                        >
                                        <path
                                            fill="white"
                                            d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10"
                                            ></path>
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-5-.01c0-1.341-.47-2.584-1.311-3.491l1.057-1.262H13.18l-.43.493C12.005 5.276 11.066 5 9.99 5 6.88 5 5 7.288 5 9.99c0 1.322.45 2.544 1.272 3.452l-1.115 1.321h1.526l.47-.552c.763.493 1.722.789 2.837.789C13.121 15 15 12.712 15 9.99zm-7.084 1.5a3.374 3.374 0 01-.333-1.5c0-1.48.861-2.761 2.407-2.761.47 0 .88.118 1.233.335zm4.5-1.5c0 1.5-.88 2.781-2.426 2.781-.509 0-.94-.138-1.291-.375l3.346-3.964c.235.454.372.986.372 1.558z"
                                            fill="#1591ed"
                                            ></path>
                                    </svg>
                                    <span class="text-sm text-neutral-3"
                                        >10,910 ø in play money</span
                                        >
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg
                                        width="16px"
                                        viewBox="0 0 18 18"
                                        xmlns="http://www.w3.org/2000/svg"
                                        >
                                        <path
                                            d="M8.99935 17.3334C4.39685 17.3334 0.666016 13.6026 0.666016 9.00008C0.666016 4.39758 4.39685 0.666748 8.99935 0.666748C13.6018 0.666748 17.3327 4.39758 17.3327 9.00008C17.3327 13.6026 13.6018 17.3334 8.99935 17.3334ZM6.08268 10.6667V12.3334H8.16602V14.0001H9.83268V12.3334H10.666C11.2185 12.3334 11.7485 12.1139 12.1392 11.7232C12.5299 11.3325 12.7493 10.8026 12.7493 10.2501C12.7493 9.69755 12.5299 9.16764 12.1392 8.77694C11.7485 8.38624 11.2185 8.16675 10.666 8.16675H7.33268C7.22218 8.16675 7.11619 8.12285 7.03805 8.04471C6.95991 7.96657 6.91602 7.86059 6.91602 7.75008C6.91602 7.63957 6.95991 7.53359 7.03805 7.45545C7.11619 7.37731 7.22218 7.33341 7.33268 7.33341H11.916V5.66675H9.83268V4.00008H8.16602V5.66675H7.33268C6.78015 5.66675 6.25024 5.88624 5.85954 6.27694C5.46884 6.66764 5.24935 7.19755 5.24935 7.75008C5.24935 8.30262 5.46884 8.83252 5.85954 9.22322C6.25024 9.61392 6.78015 9.83341 7.33268 9.83341H10.666C10.7765 9.83341 10.8825 9.87731 10.9606 9.95545C11.0388 10.0336 11.0827 10.1396 11.0827 10.2501C11.0827 10.3606 11.0388 10.4666 10.9606 10.5447C10.8825 10.6228 10.7765 10.6667 10.666 10.6667H6.08268Z"
                                            fill="#00D966"
                                            ></path>
                                    </svg>
                                    <span class="text-sm text-neutral-3"
                                        >≈$102.42 in USD</span
                                        >
                                </div>
                            </div>
                        </div>
                        <div
                            class="absolute size-3 bg-inherit rotate-45 bottom-0 translate-y-1/2 left-12 border-b border-r border-[#00000026]"
                            ></div>
                    </div>
                </div>
                <!-- bet end -->
                <div class="relative" x-data="{ tooltip: false }">
                    <button
                    type="button"
                    @mouseover="tooltip = true"
                    @mouseleave="tooltip = false"
                    @click="tooltip = !tooltip"
                    class="flex items-center gap-1.5 p-1.5 hover:bg-[#d8e6f1] bg-transparent cursor-pointer rounded"
                    >
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
                    <span class="text-sm text-[#666666]">Mar 20, 2024</span>
                    </button>
                    <!-- tooltip -->
                    <div
                        x-show.transition.origin.top="tooltip"
                        class="absolute z-10 inline-block w-max bottom-11 shadow-[0_2px_8px_0_#00000026] bg-white text-sm text-[#6b6a6a] rounded-lg"
                        style="display: none"
                        >
                        <div class="p-4 flex flex-col gap-2.5">
                            <p
                                class="text-black font-semibold text-sm leading-none"
                                >
                                Bet end date
                            </p>
                            <p
                                class="text-sm leading-none text-neutral-3 font-roboto whitespace-nowrap"
                                x-text="formatDate(market.bet_end_date,{year: 'numeric', month:'long', day: 'numeric'})"
                                >
                                March 20, 2024
                            </p>
                        </div>
                        <div
                            class="absolute size-3 bg-inherit rotate-45 bottom-0 translate-y-1/2 left-12 border-b border-r border-[#00000026]"
                            ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>