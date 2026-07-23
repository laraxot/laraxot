@auth
<div>
    <button data-dropdown-toggle="dropdown-notification" class="grid px-4 py-3 text-sm font-semibold transition rounded-lg place-items-center">
        <div class="flex items-center space-x-1">
            <x-heroicon-o-bell class="size-5"/>
        </div>
    </button>
    <div id="dropdown-notification" class="absolute z-10 hidden p-2 space-y-2 overflow-hidden text-sm border border-white rounded-lg bg-gray-50/85 backdrop-blur">
        <div>
            <ul class="flex space-x-1">
                <li><button type="button" class="p-2 font-semibold border-b-2 border-blue-500">Your activities</button></li>
                <li><button type="button" class="p-2 font-semibold hover:border-b-2 hover:border-gray-200">Forecasters you follow</button></li>
            </ul>
        </div>
        <div>
            {{-- TAB - ACTIVITY --}}
            <ul>
                <li class="flex p-2 space-x-2 transition-colors bg-white rounded cursor-pointer hover:ring-1 hover:ring-blue-500">
                    <div class="grid text-xs font-semibold text-blue-600 bg-blue-100 rounded-full place-items-center size-10">+1K</div>
                    <div class="max-w-xs shrink">
                        <p>Your career as a forecaster has begun! 1,000 have been credited to your account. Click here to review the basics of My-Company.</p>
                        <small class="block text-gray-400">March 05, 2024 • 10:15 UTC</small>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endauth