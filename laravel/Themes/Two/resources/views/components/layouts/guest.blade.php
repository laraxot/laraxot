<x-layouts.base :title="$title ?? 'Accedi'">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-gradient-to-br from-brand-blue via-brand-blue/95 to-[#0d2d4d]">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center space-x-3">
                    <svg class="w-12 h-12 text-white" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="50" cy="60" rx="25" ry="20" fill="currentColor" opacity="0.9"></ellipse>
                        <ellipse cx="65" cy="50" rx="18" ry="22" fill="currentColor"></ellipse>
                        <path d="M 83 50 Q 95 45 97 40 Q 98 35 96 30 Q 94 25 90 28 Q 88 30 88 35 Q 88 40 86 45 Q 85 48 83 50 Z" fill="currentColor"></path>
                        <ellipse cx="75" cy="40" rx="12" ry="15" fill="currentColor" opacity="0.7"></ellipse>
                        <circle cx="70" cy="48" r="3" fill="white"></circle>
                        <circle cx="70" cy="48" r="1.5" fill="currentColor"></circle>
                        <path d="M 87 45 L 90 35 L 87 40 Z" fill="currentColor" opacity="0.8"></path>
                        <ellipse cx="60" cy="75" rx="6" ry="8" fill="currentColor"></ellipse>
                        <ellipse cx="40" cy="75" rx="6" ry="8" fill="currentColor"></ellipse>
                        <path d="M 27 60 Q 20 55 17 50" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"></path>
                        <circle cx="17" cy="50" r="2" fill="currentColor"></circle>
                    </svg>
                    <span class="font-bold text-2xl text-white">Sottana Service</span>
                </a>
            </div>
            
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                {{ $slot }}
            </div>
            
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-sm text-white/80 hover:text-white transition-colors">
                    ← Torna alla home
                </a>
            </div>
        </div>
    </div>
</x-layouts.base>