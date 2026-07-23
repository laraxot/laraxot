@php
declare(strict_types=1);

@props([
    'title' => 'Rimani Aggiornato',
    'subtitle' => 'Ricevi ogni settimana i migliori articoli e aggiornamenti normativi direttamente nella tua casella di posta',
    'placeholder' => 'Inserisci la tua email',
    'button_text' => 'Iscriviti Ora',
    'subscriber_count' => 2500,
    'privacy_text' => 'Iscrivendoti accetti la nostra Privacy Policy. Puoi cancellarti in qualsiasi momento.'
])
?>

{{-- Newsletter Section with Gradient Background --}}
<section class="py-20 bg-gradient-to-br from-blue-600 via-blue-700 to-purple-700 relative overflow-hidden">
    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-60 h-60 bg-purple-400 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-400 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-8 md:p-12">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-white/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    {{ $title }}
                </h2>
                
                <p class="text-lg text-blue-100 mb-6">
                    {{ $subtitle }}
                </p>
                
                {{-- Social Proof --}}
                <div class="flex items-center justify-center mb-6">
                    <div class="flex -space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <img src="https://i.pravatar.cc/40?img={{ $i + 10 }}" 
                                 alt="Subscriber" 
                                 class="w-10 h-10 rounded-full border-2 border-white">
                        @endfor
                    </div>
                    <div class="ml-4 text-white">
                        <span class="font-bold text-lg">{{ number_format($subscriber_count) }}</span>
                        <span class="text-blue-100 text-sm"> iscritti attivi</span>
                    </div>
                </div>
            </div>
            
            {{-- Newsletter Form --}}
            <form action="/api/newsletter/subscribe" method="POST" class="max-w-xl mx-auto">
                @csrf
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email" 
                           name="email" 
                           placeholder="{{ $placeholder }}" 
                           required
                           class="flex-1 px-6 py-4 rounded-xl text-gray-900 bg-white border border-gray-200 focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-300 placeholder-gray-400">
                    
                    <button type="submit" 
                            class="px-8 py-4 bg-gradient-to-r from-white to-blue-50 text-blue-700 font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 whitespace-nowrap">
                        {{ $button_text }}
                    </button>
                </div>
                
                {{-- Privacy Text --}}
                <p class="mt-4 text-sm text-blue-100 text-center">
                    {{ $privacy_text }}
                </p>
            </form>
            
            {{-- Benefits --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center justify-center space-x-2 text-white">
                    <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm">Articoli esclusivi</span>
                </div>
                <div class="flex items-center justify-center space-x-2 text-white">
                    <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm">Aggiornamenti normativi</span>
                </div>
                <div class="flex items-center justify-center space-x-2 text-white">
                    <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm">Niente spam</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes pulse-glow {
        0%, 100% {
            opacity: 0.3;
        }
        50% {
            opacity: 0.6;
        }
    }
    
    .blur-3xl {
        filter: blur(3rem);
    }
</style>