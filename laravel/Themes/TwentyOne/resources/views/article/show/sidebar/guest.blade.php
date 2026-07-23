<div class="flex flex-col gap-4" {{-- x-show="!isloggedIn" --}}>
    <div
        class="block w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
        >
        <div
            class="w-full flex py-3 px-3 justify-between bg-blue-1 text-white font-bold"
            >
            {{ __('predict::bet.place-bet') }}
        </div>
        <div
            class="flex flex-col gap-3 py-3 justify-center items-center"
            >
            <p class="text-sm">{{ __('predict::bet.sign-up-or-login-to-participate') }}</p>
            <div
                class="flex gap-2 items-center *:border *:h-10 *:w-10 *:border-neutral-1 *:rounded-full *:flex *:items-center *:justify-center"
                >
                <div class="">
                    <button
                        role="button"
                        class="button-main button-main--size-full button-main--type-outlined button-main--variant-shade facebook-login__button"
                        >
                        <svg
                            class="facebook-login__icon"
                            xmlns="http://www.w3.org/2000/svg"
                            width="20px"
                            height="19px"
                            viewBox="0 0 8 14"
                            >
                            <g
                                fill="none"
                                fill-rule="evenodd"
                                stroke="none"
                                stroke-width="1"
                                >
                                <g
                                    fill="#1877f2"
                                    fill-rule="nonzero"
                                    transform="translate(-637 -911)"
                                    >
                                    <g transform="translate(484 905)">
                                        <g transform="translate(144)">
                                            <path
                                                d="M16.5 6v2.8h-1.4c-.483 0-.7.567-.7 1.05v1.75h2.1v2.8h-2.1V20h-2.8v-5.6H9.5v-2.8h2.1V8.8A2.8 2.8 0 0114.4 6h2.1z"
                                                ></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
                <div class="place-bet__login_required--methods--icon">
                    <button
                        role="button"
                        class="button-main button-main--size-full button-main--type-outlined button-main--variant-shade google-button"
                        >
                        <svg
                            viewBox="0 0 533.5 544.3"
                            xmlns="http://www.w3.org/2000/svg"
                            class="google-button__icon"
                            width="20px"
                            height="20px"
                            >
                            <path
                                d="M533.5 278.4c0-18.5-1.5-37.1-4.7-55.3H272.1v104.8h147c-6.1 33.8-25.7 63.7-54.4 82.7v68h87.7c51.5-47.4 81.1-117.4 81.1-200.2z"
                                fill="#4285f4"
                                ></path>
                            <path
                                d="M272.1 544.3c73.4 0 135.3-24.1 180.4-65.7l-87.7-68c-24.4 16.6-55.9 26-92.6 26-71 0-131.2-47.9-152.8-112.3H28.9v70.1c46.2 91.9 140.3 149.9 243.2 149.9z"
                                fill="#34a853"
                                ></path>
                            <path
                                d="M119.3 324.3c-11.4-33.8-11.4-70.4 0-104.2V150H28.9c-38.6 76.9-38.6 167.5 0 244.4l90.4-70.1z"
                                fill="#fbbc04"
                                ></path>
                            <path
                                d="M272.1 107.7c38.8-.6 76.3 14 104.4 40.8l77.7-77.7C405 24.6 339.7-.8 272.1 0 169.2 0 75.1 58 28.9 150l90.4 70.1c21.5-64.5 81.8-112.4 152.8-112.4z"
                                fill="#ea4335"
                                ></path>
                        </svg>
                    </button>
                </div>
                <div class="place-bet__login_required--methods--icon">
                    <button
                        role="button"
                        class="button-main button-main--size-full button-main--type-outlined button-main--variant-shade email-login__button"
                        >
                        <svg
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 18 18"
                            class="email-login__icon"
                            width="24px"
                            >
                            <path
                                fill="currentColor"
                                d="M15.6667 8.99996C15.6666 7.51403 15.17 6.07074 14.2558 4.89929C13.3417 3.72783 12.0624 2.89536 10.621 2.53407C9.17968 2.17278 7.65895 2.30338 6.30032 2.90513C4.94168 3.50688 3.82303 4.54528 3.12199 5.85545C2.42095 7.16562 2.17771 8.67244 2.43091 10.1366C2.6841 11.6008 3.41921 12.9385 4.5195 13.9372C5.61979 14.9358 7.02219 15.5383 8.504 15.6489C9.98582 15.7594 11.4621 15.3718 12.6984 14.5475L13.6234 15.9341C12.2553 16.8489 10.6459 17.3359 9.00008 17.3333C4.39758 17.3333 0.666748 13.6025 0.666748 8.99996C0.666748 4.39746 4.39758 0.666626 9.00008 0.666626C13.6026 0.666626 17.3334 4.39746 17.3334 8.99996V10.25C17.3335 10.8739 17.1335 11.4815 16.7628 11.9833C16.392 12.4852 15.8701 12.855 15.2737 13.0383C14.6773 13.2216 14.0378 13.2089 13.4492 13.0019C12.8605 12.7949 12.3538 12.4046 12.0034 11.8883C11.4472 12.4666 10.7359 12.8721 9.95484 13.0559C9.17381 13.2397 8.3563 13.1941 7.60055 12.9246C6.8448 12.6551 6.18294 12.1731 5.6945 11.5365C5.20606 10.8999 4.91182 10.1358 4.84712 9.33604C4.78241 8.53628 4.95001 7.73484 5.32977 7.02802C5.70953 6.3212 6.28529 5.73906 6.98789 5.35154C7.69049 4.96403 8.49003 4.78762 9.29046 4.84351C10.0909 4.89941 10.8582 5.18522 11.5001 5.66663H13.1667V10.25C13.1667 10.5815 13.2984 10.8994 13.5329 11.1338C13.7673 11.3683 14.0852 11.5 14.4167 11.5C14.7483 11.5 15.0662 11.3683 15.3006 11.1338C15.5351 10.8994 15.6667 10.5815 15.6667 10.25V8.99996ZM9.00008 6.49996C8.33704 6.49996 7.70115 6.76335 7.23231 7.23219C6.76347 7.70103 6.50008 8.33692 6.50008 8.99996C6.50008 9.663 6.76347 10.2989 7.23231 10.7677C7.70115 11.2366 8.33704 11.5 9.00008 11.5C9.66312 11.5 10.299 11.2366 10.7678 10.7677C11.2367 10.2989 11.5001 9.663 11.5001 8.99996C11.5001 8.33692 11.2367 7.70103 10.7678 7.23219C10.299 6.76335 9.66312 6.49996 9.00008 6.49996Z"
                                ></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="w-full flex mt-5 justify-center items-center">
                <button
                    type="button"
                    class="px-6 lg:px-8 py-2.5 text-base lg:text-lg font-medium lg:font-sembold text-center text-white bg-blue-1 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-1 dark:focus:ring-blue-800"
                    >
                Login
                </button>
            </div>
        </div>
    </div>
    <div class="bg-white flex-col gap-4 p-4">
        <h2 class="font-medium text-[30px] tracking-[1.2px]">
            Bet on the Future
        </h2>
        <p class="text-lg tracking-tight text-neutral-5 leading-6">
            Make money with what you know and help generate better
            forecasts for everyone.
        </p>
        <a
            href="#"
            class="px-3 py-2.5 block mt-4 text-sm font-medium text-center text-white bg-blue-1 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-1 dark:focus:ring-blue-800"
            >Start Forecasting</a
            >
        <div class="w-full my-7 border border-neutral-2"></div>
        <div class="flex flex-col gap-4">
            <a href="#" class="flex text-blue-1 items-center gap-4">
            <span>▶</span>
            <span class="font-bold uppercase">Watch Now</span>
            </a>
            <a href="#" class="flex text-blue-1 items-center gap-4">
            <span>▶</span>
            <span class="font-bold uppercase">How it works</span>
            </a>
        </div>
    </div>
</div>