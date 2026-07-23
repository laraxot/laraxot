<div class="grid min-h-screen md:grid-cols-2">
    <div class="p-16 text-white bg-[#0F2734] hidden md:block">
        <div class="flex flex-col justify-between h-full">
            <a href="/" class="">
                <img src="{{ asset('assets/predict/img/logo-ft.svg') }}" alt="{{ config('app.name') }}" class="h-8 w-auto" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </a>
            {{-- <a class="block" href="{{ route('home') }}">
                <svg width="97" height="40" viewBox="0 0 141 64" class="fill-white" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M60.8984 24C61.0584 24 61.1886 24.1343 61.1886 24.2995V33.7036C61.1886 35.983 62.4708 37.5516 64.9173 37.5516C67.3623 37.5516 68.6212 35.983 68.6212 33.7036V24.2995C68.6212 24.1343 68.7521 24 68.9121 24H71.7753C71.9353 24 72.0655 24.1343 72.0655 24.2995V33.8019C72.0655 37.8458 69.7375 40.64 64.9173 40.64C60.0955 40.64 57.769 37.8458 57.769 33.8019V24.2995C57.769 24.1343 57.8992 24 58.0592 24H60.8984ZM94.1259 24C94.2859 24 94.416 24.1343 94.416 24.2995V33.7036C94.416 35.983 95.6982 37.5516 98.1447 37.5516C100.589 37.5516 101.849 35.983 101.849 33.7036V24.2995C101.849 24.1343 101.979 24 102.139 24H105.002C105.163 24 105.292 24.1343 105.292 24.2995V33.8019C105.292 37.8458 102.965 40.64 98.1447 40.64C93.3222 40.64 90.9965 37.8458 90.9965 33.8019V24.2995C90.9965 24.1343 91.1266 24 91.2866 24H94.1259ZM112.34 24C112.5 24 112.63 24.1343 112.63 24.2995V33.7036C112.63 35.983 113.912 37.5516 116.359 37.5516C118.803 37.5516 120.063 35.983 120.063 33.7036V24.2995C120.063 24.1343 120.193 24 120.353 24H123.216C123.377 24 123.507 24.1343 123.507 24.2995V33.8019C123.507 37.8458 121.179 40.64 116.359 40.64C111.537 40.64 109.211 37.8458 109.211 33.8019V24.2995C109.211 24.1343 109.341 24 109.501 24H112.34ZM134.703 24.0002C138.004 24.0002 140.047 26.2292 140.047 29.2681C140.047 32.1359 138.266 33.7038 136.556 34.1203L139.88 39.8926C139.995 40.0923 139.856 40.3459 139.63 40.3459H136.418C136.313 40.3459 136.215 40.2859 136.164 40.1906L133.136 34.5368H130.666V40.0457C130.666 40.2116 130.536 40.3459 130.376 40.3459H127.584C127.423 40.3459 127.293 40.2116 127.293 40.0457V24.2996C127.293 24.1337 127.423 24.0002 127.584 24.0002H134.703ZM53.7713 24.0002C53.932 24.0002 54.0614 24.1338 54.0614 24.2997V26.7629C54.0614 26.9288 53.932 27.0631 53.7713 27.0631H46.1721V30.4938H53.7713C53.932 30.4938 54.0614 30.6282 54.0614 30.794V33.2573C54.0614 33.4231 53.932 33.5567 53.7713 33.5567H46.1721V40.0458C46.1721 40.2117 46.0419 40.3453 45.8812 40.3453H43.0892C42.9293 40.3453 42.7991 40.2117 42.7991 40.0458V24.3973C42.7991 24.1773 42.9714 24.0002 43.1838 24.0002H53.7713ZM87.7348 24.0002C87.8955 24.0002 88.0257 24.1338 88.0257 24.2997V26.7629C88.0257 26.9288 87.8955 27.0631 87.7348 27.0631H83.4185V40.0458C83.4185 40.2117 83.2883 40.3453 83.1283 40.3453H80.3131C80.1538 40.3453 80.0229 40.2117 80.0229 40.0458V27.0631H75.6819C75.5219 27.0631 75.3917 26.9288 75.3917 26.7629V24.2997C75.3917 24.1338 75.5219 24.0002 75.6819 24.0002H87.7348ZM134.229 27.0631H130.666V31.4739H134.229C135.558 31.4739 136.58 30.6408 136.58 29.2441C136.58 27.8962 135.558 27.0631 134.229 27.0631Z"
                        fill="var(--color-Neutral-1)"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0C0 0.195157 0.193484 0 0 0V16V32V48V64C0 63.8048 0.193484 64 0 64H16.0054C15.8123 64 16.0057 63.8048 16.0054 64V48C16.0057 48.0517 16.1999 47.8558 16.0054 48H32.0108C31.818 47.8558 32.0108 47.6607 32.0108 48V32C31.818 31.7124 32.0108 31.9075 32.0108 32H1.33378C1.1212 31.7124 0.9602 31.3199 1.33378 30.6667L30.677 1.33333C31.5451 0.470417 31.3531 0 30.677 0H0Z" fill="var(--color-Neutral-1)"></path>
                </svg>
            </a> --}}
            <h1 class="text-[64px] leading-tight font-semibold">Make your<br/>prediction</h1>
        </div>
    </div>
    <div class="bg-[#ECEFED] relative">
        <button class="absolute start-5 top-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20">
                <path fill="currentColor" fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10" clip-rule="evenodd" />
            </svg>
        </button>
        <main class="container max-w-sm px-5 mx-auto">
            <div class="py-16 space-y-6">
                <h4 class="text-3xl font-semibold text-center">Reset password</h4>
                {{-- <div class="space-y-4">
                    <button class="flex items-center w-full px-4 py-4 font-semibold transition-colors duration-500 border rounded-lg border-slate-400 hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.18em" height="1.2em" viewBox="0 0 256 262">
                            <path fill="#4285f4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622l38.755 30.023l2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" />
                            <path fill="#34a853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055c-34.523 0-63.824-22.773-74.269-54.25l-1.531.13l-40.298 31.187l-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" />
                            <path fill="#fbbc05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82c0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602z" />
                            <path fill="#eb4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0C79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" />
                        </svg>
                        <span class="block tracking-widest text-center grow">Enter with Google</span>
                    </button>
                    <button class="flex items-center w-full px-4 py-4 font-semibold transition-colors duration-500 border rounded-lg border-slate-400 hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.4em" height="1.4em" viewBox="0 0 24 24">
                            <path class="fill-[#1977F2]" d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4z" />
                        </svg>
                        <span class="block tracking-widest text-center grow">Enter with Facebook</span>
                    </button>
                </div>
                <div class="flex items-center py-2">
                    <div class="flex-1 border-t border-slate-400"></div>
                    <span class="px-3 text-sm text-slate-800">or</span>
                    <div class="flex-1 border-t border-slate-400"></div>
                </div> --}}
                <form class="space-y-4" wire:submit.prevent="sendResetPasswordLink">
                    <div class="space-y-1">
                        <label for="email" class="block font-semibold">E-mail</label>
                        <input wire:model.lazy="email" id="email" type="email" class="block w-full p-4 transition bg-transparent border rounded-lg h-14 border-slate-400 hover:bg-slate-300 hover:border-slate-300 focus:bg-transparent focus:border-slate-400" placeholder="example@email.com"/>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button class="flex bg-[#0027cc] text-white items-center w-full px-4 py-4 font-semibold transition-colors duration-500 border rounded-lg border-slate-400 hover:bg-[#061989]">
                            <span class="block tracking-widest text-center grow">Send password reset link</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>