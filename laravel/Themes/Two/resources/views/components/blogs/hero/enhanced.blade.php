@php
declare(strict_types=1);

@props([
    'title' => '',
    'subtitle' => '',
    'primary_cta_label' => 'Esplora gli Articoli',
    'primary_cta_url' => '#articles',
    'secondary_cta_label' => 'Contatta un Esperto',
    'secondary_cta_url' => '/contatti',
    'image' => '',
    'overlay_opacity' => 0.7,
    'text_alignment' => 'center'
])
?>

{{-- Blog Hero with background image and overlay --}}
<section class="relative min-h-[85vh] flex items-center justify-center overflow-hidden">
    {{-- Background Image --}}
    @if($image)
        <div class="absolute inset-0 z-0">
            <img src="{{ $image }}" 
                 alt="{{ $title }}" 
                 class="w-full h-full object-cover"
                 loading="lazy">
        </div>
    @endif
    
    {{-- Gradient Overlay --}}
    <div class="absolute inset-0 z-10 bg-gradient-to-br from-slate-900/90 via-blue-900/80 to-slate-800/90"></div>
    
    {{-- Content --}}
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center {{ $text_alignment === 'center' ? '' : 'text-left' }}">
            @if($title)
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 animate-fade-in-up">
                    {{ $title }}
                </h1>
            @endif
            
            @if($subtitle)
                <p class="text-xl md:text-2xl text-blue-200 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                    {{ $subtitle }}
                </p>
            @endif
            
            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fade-in-up" style="animation-delay: 0.4s;">
                <a href="{{ $primary_cta_url }}" 
                   class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-2xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-blue-500/50 hover:-translate-y-1">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <span class="relative z-10 flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m-4 4h-2m-2-2h-4m-4 4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2m2 4v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2h2m2 4v6a2 2 0 0 1 2 2h6a2 2 0 0 1 2 2v6a2 2 0 0 1 2 2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                        </svg>
                        {{ $primary_cta_label }}
                    </span>
                </a>
                
                <a href="{{ $secondary_cta_url }}" 
                   class="group relative px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-2xl border border-white/30 shadow-xl transform transition-all duration-300 hover:scale-105 hover:bg-white/20 hover:text-gray-900 hover:-translate-y-1">
                    <span>{{ $secondary_cta_label }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

@once
@push('styles')
<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
    }
</style>
@endonce