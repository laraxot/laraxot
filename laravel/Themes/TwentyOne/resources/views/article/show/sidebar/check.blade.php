<div class="flex flex-col" {{-- x-show="isloggedIn" --}}>
    <div
        class="block w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
        >
        <div
            class="w-full flex py-3 px-3 justify-between bg-blue-1 text-white font-bold"
            >
            {{ __('predict::bet.place-bet') }}
        </div>
        <div
            class="flex flex-col my-4 border gap-3 rounded-t justify-center items-center w-11/12 mx-auto"
            >
            <div class="bg-neutral-1 w-full p-1 rounded mx-auto">
                <div class="py-4 px-2 flex justify-between items-center">
                    <span class="text-sm">{{ __('predict::bet.your-bet') }}</span>
                    <div
                        class="
                        "
                        >
                        <button
                            id="dropdownHoverButton"
                            data-dropdown-toggle="dropdownHover"
                            data-dropdown-trigger="hover"
                            class="bg-transparent hover:bg-white focus:ring-4 focus:outline-none focus:bg-white font-medium rounded-lg text-sm px-3 py-2.5 text-center text-black inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button"
                            >
                            <span x-text="selection"></span>
                            <svg
                                class="w-2.5 h-2.5 ms-3"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 10 6"
                                >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m1 1 4 4 4-4"
                                    />
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div
                            id="dropdownHover"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"
                            >
                            <ul
                                class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownHoverButton"
                                >
                                <li>
                                    <a
                                        href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        >Yes</a
                                        >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        >No</a
                                        >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="w-full px-4 py-2.5 bg-white inline-flex items-center justify-between"
                >
                <input
                    class="text-[32px] w-1/2 inline-flex border border-none font-bold text-neutral-3 appearance-none"
                    type="text"
                    value="100"
                    />
                <div class="flex"></div>
                <span class="">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 20"
                        width="20px"
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
                </span>
                <span class="ml-1 place-bet__text-field__displayCode"
                    >Ooms</span
                    >
            </div>
        </div>
        <div class="flex w-full items-center justify-center py-3 mb-3">
            <a href="/m/cash-in" class="text-blue-1 font-semibold"
                >Add real money</a
                >
        </div>
        <div class="px-4 mb-3">
            <div
                class="flex justify-between items-center py-2 text-neutral-4 text-base"
                >
                <span>Av. price / share</span>
                <span class="font-bold text-black"
                    >0<span> / Share</span></span
                    >
            </div>
            <div
                class="flex justify-between items-center py-2 text-neutral-4 text-base"
                >
                <span>Bet Amount</span>
                <span class="font-bold text-black"
                    >100<span class="ml-1">Ooms</span></span
                    >
            </div>
            <div
                class="flex justify-between items-center py-2 text-neutral-4 text-base"
                >
                <span>Bet Amount</span>
                <span class="font-bold text-green-400"
                    >0<span class="ml-1">Ooms</span></span
                    >
            </div>
            <button
                type="button"
                class="px-5 my-2 mt-4 w-full flex items-center justify-center py-3 text-lg font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:bg-opacity-15"
                disabled
                >
            Please select an outcome
            </button>
        </div>
    </div>
</div>