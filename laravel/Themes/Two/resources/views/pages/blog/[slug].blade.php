@php
    use function Laravel\Folio\{name, middleware};
    use Livewire\Volt\Component;

    name('blog.show');
    middleware([]);
?>

@volt
<?php

use function Livewire\Volt\{state, computed};
use Modules\Cms\Models\Post;

state(['post' => null, 'relatedPosts' => []]);

$mount = function ($slug) {
    $this->post = Post::with(['category', 'author', 'tags'])
        ->where('slug', $slug)
        ->where('published_at', '<=', now())
        ->where('is_published', true)
        ->firstOrFail();

    // Get related posts based on category and tags
    $this->relatedPosts = Post::with(['category', 'author'])
        ->where('id', '!=', $this->post->id)
        ->where('published_at', '<=', now())
        ->where('is_published', true)
        ->where(function($query) {
            $query->where('category_id', $this->post->category_id)
                  ->orWhereHas('tags', function($tagQuery) {
                      $tagQuery->whereIn('tag_id', $this->post->tags->pluck('id'));
                  });
        })
        ->orderBy('published_at', 'desc')
        ->take(3)
        ->get();
};

// Format reading time
$readingTime = computed(fn() => 
    ceil(str_word_count(strip_tags($this->post?->content ?? '') / 200) . ' min lettura')
);

// Share URLs
$shareUrls = computed(function () {
    if (!$this->post) return [];
    
    $url = route('blog.show', $this->post->slug);
    $title = urlencode($this->post->title);
    
    return [
        'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
        'twitter' => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
        'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
        'whatsapp' => "https://wa.me/?text={$title}%20{$url}",
    ];
});
?>

<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        {{-- Hero Section --}}
        <section class="relative bg-gradient-to-br from-brand-blue to-brand-blue/90 text-white py-16">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="container mx-auto px-4 relative z-10">
                <nav class="flex items-center space-x-4 text-sm mb-8">
                    <a href="/it/blog" class="hover:text-blue-200 transition-colors">Blog</a>
                    <span>/</span>
                    @if($post->category)
                        <span>{{ $post->category->name }}</span>
                    @endif
                </nav>
                
                <div class="max-w-4xl">
                    @if($post->category)
                        <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium mb-4">
                            {{ $post->category->name }}
                        </span>
                    @endif
                    
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                        {{ $post->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-blue-100">
                        @if($post->author)
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-white/30 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span>{{ $post->author->name }}</span>
                            </div>
                        @endif
                        
                        @if($post->published_at)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $post->published_at->format('d F Y') }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $this->readingTime }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Article Content --}}
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                        {{-- Featured Image --}}
                        @if($post->featured_image)
                            <div class="aspect-w-16 aspect-h-9">
                                <img 
                                    src="{{ $post->featured_image }}" 
                                    alt="{{ $post->title }}"
                                    class="w-full h-64 md:h-96 object-cover"
                                >
                            </div>
                        @endif
                        
                        {{-- Article Body --}}
                        <div class="p-8 md:p-12">
                            <div class="prose prose-lg max-w-none">
                                {!! $post->content !!}
                            </div>
                            
                            {{-- Tags --}}
                            @if($post->tags->count() > 0)
                                <div class="mt-12 pt-8 border-t border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tag</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($post->tags as $tag)
                                            <a href="/it/blog?tag={{ $tag->name }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Share Buttons --}}
                            <div class="mt-12 pt-8 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Condividi questo articolo</h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($this->shareUrls as $platform => $url)
                                        <a 
                                            href="{{ $url }}" 
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition-colors"
                                        >
                                            {{ ucfirst($platform) }}
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </article>
                    
                    {{-- Author Bio --}}
                    @if($post->author)
                        <div class="mt-8 bg-white rounded-lg shadow-lg p-8">
                            <div class="flex items-start space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-brand-blue to-brand-blue/80 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                    {{ strtoupper(substr($post->author->name, 0, 2)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $post->author->name }}</h3>
                                    <p class="text-gray-600 mb-4">
                                        Esperto in radioprotezione e sicurezza sanitaria con anni di esperienza nel settore.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="/it/blog?author={{ $post->author->id }}" class="text-brand-blue hover:text-brand-blue/80 font-medium">
                                            Vedi tutti gli articoli
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Related Articles --}}
        @if($relatedPosts->count() > 0)
            <section class="py-16 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Articoli Correlati</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($relatedPosts as $relatedPost)
                                <article class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                    @if($relatedPost->featured_image)
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img 
                                                src="{{ $relatedPost->featured_image }}" 
                                                alt="{{ $relatedPost->title }}"
                                                class="w-full h-48 object-cover"
                                            >
                                        </div>
                                    @endif
                                    
                                    <div class="p-6">
                                        @if($relatedPost->category)
                                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mb-3">
                                                {{ $relatedPost->category->name }}
                                            </span>
                                        @endif
                                        
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="hover:text-brand-blue transition-colors">
                                                {{ $relatedPost->title }}
                                            </a>
                                        </h3>
                                        
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ $relatedPost->published_at->format('d M Y') }}</span>
                                            <span>{{ ceil(str_word_count($relatedPost->content) / 200) }} min lettura</span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Newsletter CTA --}}
        <x-blog.newsletter :data="[
            'title' => 'Rimani Aggiornato',
            'description' => 'Ricevi gli ultimi articoli e aggiornamenti normativi direttamente nella tua casella email.',
            'cta_label' => 'Iscriviti alla Newsletter'
        ]" />
    </div>
</x-layouts.app>
@endvolt
</div>