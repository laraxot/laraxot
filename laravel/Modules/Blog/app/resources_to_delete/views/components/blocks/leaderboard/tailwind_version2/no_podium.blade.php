<tr>
    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
        <div class="text-gray-900">{{ $pos }}°</div>
    </td>
    <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
        <div class="flex items-center">
        <div class="h-11 w-11 flex-shrink-0">
            <img class="h-11 w-11 rounded-full" 
                {{-- src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"  --}}ù
                src="{{ $profile->getAvatarUrl() }}"
                alt="">
        </div>
        <div class="ml-4">
            <div class="font-medium text-gray-900">{{ $profile->full_name }}</div>
        </div>
        </div>
    </td>
    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
        <span class="text-base leading-none">{{ $profile->credits }}</span>
    </td>
</tr>