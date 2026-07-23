<section class="rounded-lg py-10 px-14 bg-white grid grid-cols-1 lg:grid-cols-[40%,60%]">
    <div>
        <h2 class="text-2xl text-neutral-5 mb-2 leading-10 font-semibold">
            FAQ
        </h2>
        <a class="flex items-center gap-1 text-blue-1 hover:text-blue-3" href="#faqs">
            Go to My_Company documentation
            <svg class="inline-block w-2.5" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M13.0309 6.99992L7.66689 1.63592L9.08089 0.221924L16.8589 7.99992L9.08089 15.7779L7.66689 14.3639L13.0309 8.99992H0.858887V6.99992H13.0309Z"
                    fill="currentColor"></path>
            </svg>
        </a>
    </div>
    <div>
        <ul class="flex flex-col gap-4" x-data="{selected:1}">
            <li class="relative border-b border-gray-200">
                <button type="button"
                    class="flex items-center justify-between w-full pb-4 text-left font-semibold text-neutral-3"
                    @click="selected !== 1 ? selected = 1 : selected = null">
                    <span> What is My_Company? </span>
                    <svg class="w-4 transition-transform duration-200 fill-neutral-5" :class="selected == 1 && 'rotate-180' "
                        viewBox="0 0 12 8" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.99999 4.97667L10.125 0.851669L11.3033 2.03L5.99999 7.33334L0.696655 2.03L1.87499 0.851669L5.99999 4.97667Z"
                            fill="currentColor"></path>
                    </svg>
                </button>
                <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container1"
                    x-bind:style="selected == 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                    <div class="mb-2 text-neutral-3 text-base">
                        <p>
                            My_Company is a prediction market: a crowd-powered platform for
                            generating more accurate predictions about future events.
                            Forecasters make predictions by betting on them using their
                            choice of play money, or crypto. Our mission is to aggregate
                            the wisdom of the crowd to generate better predictions in a
                            chaotic world. You can find out more about My_Company on our
                            <a class="text-sm text-blue-1 font-semibold inline-block">About page</a>.
                        </p>
                    </div>
                </div>
            </li>
            <li class="relative border-b border-gray-200">
                <button type="button"
                    class="flex items-center justify-between w-full pb-4 text-left font-semibold text-neutral-3"
                    @click="selected !== 2 ? selected = 2 : selected = null">
                    <span>How are prices determined?</span>
                    <svg class="w-4 transition-transform duration-200 fill-neutral-5" :class="selected == 2 && 'rotate-180' "
                        viewBox="0 0 12 8" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.99999 4.97667L10.125 0.851669L11.3033 2.03L5.99999 7.33334L0.696655 2.03L1.87499 0.851669L5.99999 4.97667Z"
                            fill="currentColor"></path>
                    </svg>
                </button>
                <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container2"
                    x-bind:style="selected == 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''">
                    <div class="mb-2 text-neutral-3 text-base">
                        <p>
                            My_Company uses an Automated Market Maker (AMM), an algorithm
                            that allows us to provide a buy or sell price for any
                            outcome at any time. This is in contrast with a traditional
                            “order book” approach used, for instance, in a stock market
                            or financial exchange, which requires that both sides of a
                            trade are present at the same time in order for a trade to
                            occur. The AMM approach has the advantage of instant,
                            always-on liquidity for all of our markets, while at the
                            same time maintaining the benefits of dynamic,
                            exchange-based pricing.
                        </p>
                    </div>
                </div>
            </li>
            <li class="relative border-b border-gray-200">
                <button type="button"
                    class="flex items-center justify-between w-full pb-4 text-left font-semibold text-neutral-3"
                    @click="selected !== 3 ? selected = 3 : selected = null">
                    <span>Do you have a public API?</span>
                    <svg class="w-4 transition-transform duration-200 fill-neutral-5" :class="selected == 3 && 'rotate-180' "
                        viewBox="0 0 12 8" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.99999 4.97667L10.125 0.851669L11.3033 2.03L5.99999 7.33334L0.696655 2.03L1.87499 0.851669L5.99999 4.97667Z"
                            fill="currentColor"></path>
                    </svg>
                </button>
                <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container3"
                    x-bind:style="selected == 3 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : ''">
                    <div class="mb-2 text-neutral-3 text-base">
                        <p>
                            Yes! You can view our
                            <a class="text-sm text-blue-1 font-semibold inline-block" href="https://api.My_Company.com/docs/">API
                            documentation</a>
                            To generate a private key and begin using the API, please go
                            to your Settings page.If you have questions, suggestions, or
                            any other feedback regarding the API, please contact
                            <span class="text-sm text-blue-1 font-semibold inline-block">support@My_Company.com</span>
                        </p>
                    </div>
                </div>
            </li>
            <li class="relative border-b border-gray-200">
                <button type="button"
                    class="flex items-center justify-between w-full pb-4 text-left font-semibold text-neutral-3"
                    @click="selected !== 4 ? selected = 4 : selected = null">
                    <span>I have an idea for a market. Where can I suggest it?</span>
                    <svg class="w-4 transition-transform duration-200 fill-neutral-5" :class="selected == 4 && 'rotate-180' "
                        viewBox="0 0 12 8" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.99999 4.97667L10.125 0.851669L11.3033 2.03L5.99999 7.33334L0.696655 2.03L1.87499 0.851669L5.99999 4.97667Z"
                            fill="currentColor"></path>
                    </svg>
                </button>
                <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container4"
                    x-bind:style="selected == 4 ? 'max-height: ' + $refs.container4.scrollHeight + 'px' : ''">
                    <div class="mb-2 text-neutral-3 text-base">
                        <p>
                            We are always keen to add new and interesting markets to
                            My_Company. If you have a suggestion for a new market, please
                            let us know here:
                            <a class="text-sm text-blue-1 font-semibold inline-block"
                                href="https://My_Company.com/i/feedback/suggestion">
                            suggest a market.</a>
                        </p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</section>
