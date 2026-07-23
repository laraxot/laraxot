<div x-data="{ tooltip: false }" class="relative">
    <button type="button" class="p-1.5 bg-transparent hover:bg-[#d8e6f1] rounded"
    @mouseover="tooltip = true" @mouseleave="tooltip = false" @click="tooltip = !tooltip">
    {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" width="20px">
        <path fill="white" d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10">
        </path>
        <path fill-rule="evenodd" clip-rule="evenodd"
            d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-5-.01c0-1.341-.47-2.584-1.311-3.491l1.057-1.262H13.18l-.43.493C12.005 5.276 11.066 5 9.99 5 6.88 5 5 7.288 5 9.99c0 1.322.45 2.544 1.272 3.452l-1.115 1.321h1.526l.47-.552c.763.493 1.722.789 2.837.789C13.121 15 15 12.712 15 9.99zm-7.084 1.5a3.374 3.374 0 01-.333-1.5c0-1.48.861-2.761 2.407-2.761.47 0 .88.118 1.233.335zm4.5-1.5c0 1.5-.88 2.781-2.426 2.781-.509 0-.94-.138-1.291-.375l3.346-3.964c.235.454.372.986.372 1.558z"
            fill="#1591ed"></path>
    </svg> --}}
    </button>
    <div x-cloak x-show.transition.origin.top="tooltip"
        class="absolute right-0 z-10 inline-block w-[330px] bottom-11 shadow-[0_2px_8px_0_#00000026] bg-white text-sm text-[#6b6a6a] rounded-lg">
        <p class="p-4">
            You are viewing play-money markets. To view real-money
            markets, please click on your account balance above
            and select real-money.
        </p>
        <div
            class="absolute size-3 bg-inherit rotate-45 bottom-0 translate-y-1/2 right-5 border-b border-r border-[#00000026]">
        </div>
    </div>
</div>