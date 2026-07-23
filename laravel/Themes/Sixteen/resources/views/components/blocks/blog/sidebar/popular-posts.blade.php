@props(['title' => 'Articoli Popolari', 'posts' => []])

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
    <h4 class="font-bold text-gray-900 mb-4 text-lg border-l-4 border-blue-500 pl-3">{{ $title }}</h4>
    <div class="space-y-4">
        {{-- Fallback content if empty --}}
        @if(empty($posts))
            <p class="text-sm text-gray-500 italic">Nessun articolo popolare al momento.</p>
        @else
            @foreach($posts as $post)
                <a href="{{ $post['url'] ?? '#' }}" class="flex gap-4 group">
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 relative">
                        <img 
                            src="{{ $post['image'] ?? 'https://images.unsplash.com/photo-1551286808-ce0c2b25a736?w=150&q=80' }}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                            alt="{{ $post['title'] ?? 'Articolo' }}"
                        >
                    </div>
                    <div class="flex-1 min-w-0">
                        <h5 class="font-medium text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors text-sm leading-snug mb-1">
                            {{ $post['title'] ?? 'Titolo Articolo' }}
                        </h5>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $post['date'] ?? now()->format('d M Y') }}
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
