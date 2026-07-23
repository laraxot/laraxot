@php
declare(strict_types=1);

@props([
    'title' => 'Hai Domande sulla Radioprotezione?',
    'subtitle' => 'I nostri esperti sono pronti ad aiutarti con consulenze personalizzate',
    'primary_cta_label' => 'Prenota una Consulenza',
    'primary_cta_url' => '/contatti',
    'secondary_cta_label' => 'Chiama Ora',
    'secondary_cta_url' => 'tel:+390212345678',
    'testimonials' => [
        [
            'name' => 'Dr. Maria Rossi',
            'role' => 'Responsabile RPQ',
            'text' => 'Un servizio impeccabile e professionale. Consigliatissimo!',
            'avatar' => 'https://i.pravatar.cc/150?img=5',
            'rating' => 5
        ],
        [
            'name' => 'Ing. Luca Bianchi',
            'role' => 'Tecnico di Radiologia',
            'text' => 'Supporto completo per adeguamento normativo. Grazie!',
            'avatar' => 'https://i.pravatar.cc/150?img=6',
            'rating' => 5
        ]
    ]
])
?>

{{-- CTA Consultation Section --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Content --}}
            <div>
                <div class="inline-flex items-center justify-center w-12 h-12 mb-6 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $title }}
                </h2>
                
                <p class="text-lg text-gray-600 mb-8">
                    {{ $subtitle }}
                </p>
                
                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ $primary_cta_url }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $primary_cta_label }}
                    </a>
                    
                    <a href="{{ $secondary_cta_url }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-white border-2 border-blue-600 text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $secondary_cta_label }}
                    </a>
                </div>
                
                {{-- Trust Indicators --}}
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 text-gray-600">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Consulenze personalizzate</span>
                    </div>
                    <div class="flex items-center space-x-3 text-gray-600">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Supporto completo</span>
                    </div>
                    <div class="flex items-center space-x-3 text-gray-600">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Risposte entro 24 ore</span>
                    </div>
                </div>
            </div>
            
            {{-- Testimonials --}}
            <div class="space-y-6">
                @foreach($testimonials as $testimonial)
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <img src="{{ $testimonial['avatar'] }}" 
                                 alt="{{ $testimonial['name'] }}" 
                                 class="w-12 h-12 rounded-full object-cover">
                            
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $testimonial['name'] }}</h4>
                                    <span class="text-sm text-gray-500">· {{ $testimonial['role'] }}</span>
                                </div>
                                
                                <div class="flex mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" 
                                             viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                
                                <p class="text-gray-600 italic">"{{ $testimonial['text'] }}"</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>