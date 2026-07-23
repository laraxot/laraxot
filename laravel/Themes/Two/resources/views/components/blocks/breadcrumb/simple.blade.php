<nav class="flex items-center space-x-2 text-sm py-4" aria-label="Breadcrumb">
    @foreach($items as $index => $item)
        @if($index > 0)
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-400">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
        @endif
        
        @if($item['url'])
            <a href="{{ $item['url'] }}" class="flex items-center text-gray-600 hover:text-brand-blue transition-colors">
                @if($index === 0)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    </svg>
                @else
                    {{ $item['label'] }}
                @endif
            </a>
        @else
            <span class="text-gray-900 font-medium">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
