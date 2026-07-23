{{-- Header Slim - Pure Tailwind CSS (Design Comuni Style) --}}
{{-- Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html --}}
{{-- NO Bootstrap Italia classes - ALL Tailwind CSS --}}

<div class="bg-[#007a52] py-2">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center gap-4">
            {{-- Left: Region Name --}}
            <a href="#" class="text-white text-sm font-semibold hover:underline no-underline">
                Nome della Regione
            </a>
            
            {{-- Right: Language + Login --}}
            <div class="flex items-center gap-4">
                {{-- Language Switcher --}}
                <div class="text-white text-sm flex items-center gap-2">
                    <span class="opacity-90">Lingua attiva:</span>
                    <span class="font-bold">ITA</span>
                    <span class="opacity-70">/</span>
                    <a href="#" class="opacity-70 hover:opacity-100 no-underline">ENG</a>
                </div>
                
                {{-- Login Button --}}
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 bg-white text-[#007a52] px-3 py-1.5 rounded text-sm font-semibold no-underline hover:bg-[#F0F0F0] hover:text-[#005c40] transition-all">
                    <svg class="w-4 h-4" aria-hidden="true">
                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use>
                    </svg>
                    <span>Accedi all'area personale</span>
                </a>
            </div>
        </div>
    </div>
</div>
