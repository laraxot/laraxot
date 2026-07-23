@props(['title' => 'Categorie', 'categories' => []])

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
    <h4 class="font-bold text-gray-900 mb-4 text-lg border-l-4 border-green-500 pl-3">{{ $title }}</h4>
    <ul class="space-y-1">
         {{-- Fallback content if empty --}}
        @if(empty($categories))
            <li class="text-sm text-gray-500 italic">Nessuna categoria disponibile.</li>
        @else
            @foreach($categories as $cat)
                <li>
                    <a href="#" class="flex justify-between items-center text-gray-600 hover:text-green-600 hover:bg-green-50 transition-all py-2 px-2 rounded-lg group">
                        <span class="font-medium text-sm">{{ $cat['name'] ?? 'Categoria' }}</span>
                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full group-hover:bg-green-100 group-hover:text-green-700 transition-colors">
                            {{ $cat['count'] ?? 0 }}
                        </span>
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</div>
