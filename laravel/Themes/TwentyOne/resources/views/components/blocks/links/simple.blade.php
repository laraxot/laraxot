<ul class="flex flex-wrap gap-2">
    @foreach($links as $link)
        <li>
            <a href="{{ $link['url'] }}"  class="grid transition-colors rounded-full size-10 bg-white/5 place-items-center hover:text-blue-500" target="_blank">
               @svg($link['icon'], 'size-6')
            </a>
        </li>
    @endforeach
</ul>
