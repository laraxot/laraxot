@props([
    'title' => '',
    'description' => '',
    'cta_label' => 'Iscriviti',
    'privacy_note' => '',
])

<section id="contatti" class="py-20 bg-gradient-to-r from-brand-green to-brand-green/90 text-white">
            {{-- Icon --}}
            <div class="w-16 h-16 bg-white/15 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
            </div>

            <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ $title }}</h2>
            <p class="text-lg text-white/85 max-w-2xl mx-auto mb-8">{{ $description }}</p>

            {{-- Form --}}
            <form class="max-w-xl mx-auto" @submit.prevent>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="email"
                           placeholder="La tua email professionale"
                           class="flex-1 px-5 py-3.5 rounded-lg text-gray-900 placeholder-gray-400 bg-white focus:outline-none focus:ring-2 focus:ring-[#E67E22]"
                           required>
                    <button type="submit"
                            class="px-8 py-3.5 bg-[#E67E22] hover:bg-[#d35400] text-white font-bold rounded-lg transition-colors duration-200 shadow-lg">
                        {{ $cta_label }}
                    </button>
                </div>
            </form>

            @if($privacy_note)
                <p class="text-sm text-white/70 mt-5">{{ $privacy_note }}</p>
            @endif
        </div>
    </div>
</section>