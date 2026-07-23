<?php

use function Livewire\Volt\{state, computed};
use Modules\Cms\Models\Post;
use Modules\Cms\Models\Category;

state([
    'search' => '',
    'selectedCategory' => '',
    'selectedTag' => '',
    'currentPage' => 1,
    'perPage' => 9
]);

// Mount the component
$mount = function () {
    $this->search = request()->get('search', '');
    $this->selectedCategory = request()->get('category', '');
    $this->selectedTag = request()->get('tag', '');
};

// Get all categories
$categories = computed(fn() => Category::withCount('posts')->orderBy('name')->get());

// Get all tags
$tags = computed(fn() => Post::whereHas('tags')->with('tags')->get()->flatMap->tags->unique('name')->sortBy('name'));

// Filter and paginate posts
$posts = computed(function () {
    $query = Post::with(['category', 'author', 'tags'])
        ->where('published_at', '<=', now())
        ->where('is_published', true)
        ->orderBy('published_at', 'desc');

    // Apply search filter
    if ($this->search) {
        $query->where(function ($q) {
            $q->where('title', 'like', '%' . $this->search . '%')
              ->orWhere('content', 'like', '%' . $this->search . '%')
              ->orWhere('excerpt', 'like', '%' . $this->search . '%');
        });
    }

    // Apply category filter
    if ($this->selectedCategory) {
        $query->where('category_id', $this->selectedCategory);
    }

    // Apply tag filter
    if ($this->selectedTag) {
        $query->whereHas('tags', function ($q) {
            $q->where('name', $this->selectedTag);
        });
    }

    return $query->paginate($this->perPage, ['*'], 'page', $this->currentPage);
});

// Update search with debounced input
$updatedSearch = function () {
    $this->resetPage();
};

// Update category filter
$updatedSelectedCategory = function () {
    $this->resetPage();
};

// Update tag filter  
$updatedSelectedTag = function () {
    $this->resetPage();
};

// Reset all filters
$resetFilters = function () {
    $this->search = '';
    $this->selectedCategory = '';
    $this->selectedTag = '';
    $this->currentPage = 1;
};

// Reset pagination
$resetPage = function () {
    $this->currentPage = 1;
};

// Go to specific page
$goToPage = function ($page) {
    $this->currentPage = $page;
};

// Subscribe to newsletter
$subscribeNewsletter = function () {
    $this->validate(['email' => 'required|email']);
    
    // Here you would implement the newsletter subscription logic
    // For now, we'll just show a success message
    session()->flash('newsletter_success', 'Iscrizione alla newsletter completata con successo!');
    
    $this->email = '';
};

?>

<div>
    {{-- Search and Filters Section --}}
    <section class="py-8 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                {{-- Main Search --}}
                <div class="lg:col-span-3">
                    <div class="relative">
                        <label for="blog-search-input-{{ uniqid() }}" class="sr-only">Cerca articoli</label>
                        <input 
                            type="text" 
                            id="blog-search-input-{{ uniqid() }}"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cerca articoli per titolo, contenuto o argomento..."
                            autocomplete="off"
                            aria-label="Cerca articoli per titolo, contenuto o argomento"
                            class="w-full px-6 py-4 pr-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent"
                        >
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
                    @if($this->search)
                        <div class="mt-2 text-sm text-gray-600">
                            Risultati per: <strong>"{{ $this->search }}"</strong>
                        </div>
                    @endif
                </div>
                
                {{-- Quick Filters --}}
                <div class="flex flex-wrap gap-2 items-center">
                    @if($this->selectedCategory || $this->selectedTag || $this->search)
                        <button 
                            wire:click="resetFilters"
                            aria-label="Rimuovi tutti i filtri di ricerca"
                            class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md"
                        >
                            Reset Filtri
                        </button>
                    @endif
                </div>
            </div>
            
            {{-- Filter Pills --}}
            <div class="flex flex-wrap gap-4 mt-6">
                {{-- Category Filter --}}
                <div class="flex items-center gap-2">
                    <label for="blog-category-filter-{{ uniqid() }}" class="text-sm font-medium text-gray-700">Categoria:</label>
                    <select 
                        id="blog-category-filter-{{ uniqid() }}"
                        wire:model.live="selectedCategory"
                        autocomplete="off"
                        aria-describedby="blog-category-filter-help-{{ uniqid() }}"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96]"
                    >
                        <option value="">Tutte</option>
                        @foreach($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->posts_count }})</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Tag Filter --}}
                <div class="flex items-center gap-2">
                    <label for="blog-tag-filter-{{ uniqid() }}" class="text-sm font-medium text-gray-700">Tag:</label>
                    <select 
                        id="blog-tag-filter-{{ uniqid() }}"
                        wire:model.live="selectedTag"
                        autocomplete="off"
                        aria-describedby="blog-tag-filter-help-{{ uniqid() }}"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96]"
                    >
                        <option value="">Tutti</option>
                        @foreach($this->tags as $tag)
                            <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    {{-- Results Summary --}}
    @if($this->posts->count() > 0)
        <div class="container mx-auto px-4 py-4">
            <p class="text-gray-600">
                Trovati {{ $this->posts->total() }} articoli
                @if($this->selectedCategory) nella categoria "{{ $this->categories->find($this->selectedCategory)?->name }}"@endif
                @if($this->selectedTag) con tag "{{ $this->selectedTag }}"@endif
            </p>
        </div>
    @endif

    {{-- Blog Grid --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            @if($this->posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($this->posts as $post)
                        <article class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                            {{-- Featured Image --}}
                            @if($post->featured_image)
                                <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                                    <img 
                                        src="{{ $post->featured_image }}" 
                                        alt="{{ $post->title }}"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                </div>
                            @endif
                            
                            <div class="p-6">
                                {{-- Category Badge --}}
                                @if($post->category)
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mb-3">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                
                                {{-- Title --}}
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-brand-blue transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                
                                {{-- Excerpt --}}
                                @if($post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif
                                
                                {{-- Meta --}}
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    @if($post->published_at)
                                        <span>{{ $post->published_at->format('d M Y') }}</span>
                                    @endif
                                    @if($post->author)
                                        <span>{{ $post->author->name }}</span>
                                    @endif
                                </div>
                                
                                {{-- Tags --}}
                                @if($post->tags->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($post->tags->take(3) as $tag)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                @if($this->posts->hasPages())
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center space-x-2">
                            {{-- Previous --}}
                            @if($this->posts->currentPage() > 1)
                    <button 
                        wire:click="goToPage({{ $this->posts->currentPage() - 1 }})"
                        aria-label="Vai alla pagina precedente"
                        class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-[#1E5A96]"
                    >
                                    Precedente
                                </button>
                            @endif
                            
                            {{-- Page Numbers --}}
                            @foreach($this->posts->links()->elements[0] as $page)
                                @if($page['url'])
                                    <button 
                                        wire:click="goToPage({{ $page['page'] }})"
                                        aria-label="Vai alla pagina {{ $page['label'] }}"
                                        @if($page['active']) aria-current="page" @endif
                                        class="px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E5A96] {{ $page['active'] ? 'bg-brand-blue text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' }} rounded-lg transition-colors"
                                    >
                                        {{ $page['label'] }}
                                    </button>
                                @endif
                            @endforeach
                            
                            {{-- Next --}}
                            @if($this->posts->hasMorePages())
                                <button 
                                    wire:click="goToPage({{ $this->posts->currentPage() + 1 }})"
                                    aria-label="Vai alla pagina successiva"
                                    class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-[#1E5A96]"
                                >
                                    Successiva
                                </button>
                            @endif
                        </nav>
                    </div>
                @endif
            @else
                {{-- No Results --}}
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Nessun articolo trovato</h3>
                    <p class="text-gray-600 mb-6">
                        Prova a modificare i criteri di ricerca o i filtri applicati.
                    </p>
                    <button 
                        wire:click="resetFilters"
                        aria-label="Rimuovi tutti i filtri e mostra tutti gli articoli"
                        class="px-6 py-2 bg-brand-blue text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E5A96]"
                    >
                        Resetta tutti i filtri
                    </button>
                </div>
            @endif
        </div>
    </section>
</div>