<div class="flex flex-col" {{-- x-show="isloggedIn" --}}>
    <div class="block w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex justify-between w-full px-3 py-3 font-bold text-white bg-blue-1 lg:rounded-t">
            {{ (string) __('predict::bet.place-bet') }}
        </div>
        <form wire:submit="save">
            <div class="flex flex-col items-center justify-center w-11/12 gap-3 mx-auto my-4 border rounded-t">
                <div class="w-full p-1 mx-auto rounded bg-neutral-1">
                    <div class="flex items-center justify-between px-2 py-4">
                        <span class="text-sm">{{ (string) __('predict::bet.your-bet') }}
                            {{ $rating_title }}
                        </span>
                        @if($type == 'show')
                            <div class="">
                                <!-- Dropdown menu -->
                                @include('blog::livewire.article.ratings.for-image.v1.check.dropdown_menu')
                            </div>
                        @endif
                    </div>
                </div>
                <div class="w-full px-4 py-2.5 bg-white inline-flex items-center justify-between">
                    <input
                        class="text-[32px] w-1/2 inline-flex border border-none font-bold text-neutral-3 appearance-none"
                        type="number"
                        value="0"
                        wire:model.live.lazy="import"
                        />
                    {{-- <input
                        class="text-[32px] w-1/2 inline-flex border border-none font-bold text-neutral-3 appearance-none"
                        type="text"
                        wire:model="form_data.credit"
                        /> --}}


                    <div class="flex"></div>
                    <span class="">
                        {{-- <svg
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
                        </svg> --}}
                        <x-heroicon-o-banknotes width="20px" padding="3px" style="color: grey"/>
                    </span>
                    {{-- <span class="ml-1 place-bet__text-field__displayCode">Ooms</span> --}}
                </div>
                @error('import') <span>{{ $message }}</span> @enderror
                @error('rating_title') <span>{{ $message }}</span> @enderror
            </div>
            {{-- <div class="flex items-center justify-center w-full py-3 mb-3">
                <a href="/m/cash-in" class="font-semibold text-blue-1">Add real money</a>
            </div> --}}
            <div class="px-4 mb-3">
                {{-- <div class="flex items-center justify-between py-2 text-base text-neutral-4">
                    <span>Av. price / share</span>
                    <span class="font-bold text-black">0<span> / Share</span></span>
                </div>
                <div class="flex items-center justify-between py-2 text-base text-neutral-4">
                    <span>Bet Amount</span>
                    <span class="font-bold text-black">100<span class="ml-1">Ooms</span></span>
                </div>
                <div class="flex items-center justify-between py-2 text-base text-neutral-4">
                    <span>Bet Amount</span>
                    <span class="font-bold text-green-400">0<span class="ml-1">Ooms</span></span>
                </div> --}}
                <button
                    {{-- wire:click="close()" --}}
                    {{-- wire:click="save()" --}}
                    {{-- wire:click="$refresh" --}}
                    {{-- type="submit" --}}
                    class="flex items-center justify-center w-full px-5 py-3 my-2 mt-4 text-lg font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:bg-opacity-15"
                    {{-- disabled --}}
                    >
                {{ (string) __('predict::bet.select-an-outcome') }}
                </button>
            </div>
        </form>
    </div>
</div>
