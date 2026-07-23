@php
declare(strict_types=1);

@props([
    'title' => '',
    'subtitle' => '',
    'description' => '',
    'backgroundImage' => '',
    'stats' => []
])
?>

{{-- Enhanced hero with glassmorphism and animations --}}
<section class="relative min-h-[85vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
    {{--
    Animated background with gradient and particles
--}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 via-purple-600 to-pink-600 opacity-30"></div>
        
        <div class="absolute inset-0 overflow-hidden">
            @for($i = 1; $i <= 20; $i++)
                <div class="absolute rounded-full bg-white/10 backdrop-blur-sm animate-pulse" style="left: {{ rand(10, 90) }}%; top: {{ rand(10, 90) }}%; animation-delay: {{ $i * 0.1 }}s; animation-duration: 3s;"></div>
            @endfor
        </div>
    </div>

    {{--
    Glass effect overlay for modern depth
--}}
    <div class="absolute inset-0 bg-black/20 backdrop-blur-xl"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
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
            
            @if($description)
                <p class="text-lg text-gray-300 mb-12 max-w-4xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.4s;">
                    {{ $description }}
                </p>
            @endif
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fade-in-up" style="animation-delay: 0.6s;">
                @if(isset($ctaPrimary['label']))
                    <a href="{{ $ctaPrimary['url'] ?? '#' }}" 
                       class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-2xl shadow-2xl transform transition-all duration-500 hover:scale-105 hover:shadow-blue-500/50 hover:-translate-y-1">
                        
                        {{--
                            Shine effect on hover
                        --}}
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        {{--
                            Animated icon with bounce effect
                        --}}
                        <div class="relative z-10 flex items-center">
                            <svg class="w-6 h-6 text-white animate-bounce" style="animation-duration: 2s; animation-delay: 0.8s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7m0 0l-7-7m7 7H3"/>
                            </svg>
                        </div>
                        
                        <span class="ml-3">{{ $ctaPrimary['label'] ?? 'Inizia Ora' }}</span>
                    </a>
                @endif
                
                @if(isset($ctaSecondary['label']))
                    <a href="{{ $ctaSecondary['url'] ?? '#' }}" 
                       class="group relative px-8 py-4 bg-white/10 backdrop-blur-sm text-gray-900 font-semibold rounded-2xl border border-gray-200 shadow-xl transform transition-all duration-500 hover:scale-105 hover:shadow-gray-200/50 hover:-translate-y-1">
                        <span class="ml-3">{{ $ctaSecondary['label'] ?? 'Scopri di Più' }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($stats && count($stats) > 0)
        <div class="relative z-10 mt-auto">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                    <div class="grid grid-cols-2 md:grid-cols-{{ count($stats) }} gap-6">
                        @foreach($stats as $index => $stat)
                            <div class="text-center p-4 animate-fade-in-up" style="animation-delay: {{ ($index + 1) * 0.1 }}s;">
                                @if(isset($stat['number']))
                                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stat['number'] }}</div>
                                @endif
                                
                                @if(isset($stat['label']))
                                    <div class="text-lg font-semibold text-gray-900">{{ $stat['label'] }}</div>
                                @endif
                                
                                @if(isset($stat['description']))
                                    <div class="text-sm text-gray-600">{{ $stat['description'] }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

{{--
    Custom animations for modern UI
--}}
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
        
        @keyframes bounce {
            0% {
                transform: translateY(-10%);
            }
            50% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-10%);
            }
        }
        
        @keyframes pulse {
            0% {
                opacity: 0.3;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0.3;
            }
        }
        
        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }
        
        .animate-bounce {
            animation: bounce 2s infinite;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
    </style>
@endonce