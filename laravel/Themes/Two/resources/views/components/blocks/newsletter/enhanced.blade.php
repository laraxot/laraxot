@props([
    'title' => 'Rimani Aggiornato',
    'subtitle' => '',
    'description' => '',
    'placeholder' => 'La tua email',
    'button_label' => 'Iscriviti',
    'privacy_text' => '',
    'bg_color' => 'gradient',
    'show_social_proof' => false,
    'social_proof_text' => '',
])

@php
    $bgClass = match($bg_color) {
        'gradient' => 'bg-gradient-to-br from-[#1e3a5f] via-[#1a5a6f] to-emerald-700',
        'brand-blue' => 'bg-gradient-to-br from-[#1E5A96] to-[#164575]',
        'brand-green' => 'bg-gradient-to-r from-[#2D8659] to-[#2D8659]/90',
        default => 'bg-gradient-to-br from-[#1E5A96] to-[#164575]',
    };
    $displayText = !empty($subtitle) ? $subtitle : (!empty($description) ? $description : '');
@endphp

<section class="py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="{{ $bgClass }} rounded-3xl p-10 lg:p-16 text-center text-white shadow-xl">
            <div class="w-16 h-16 mx-auto mb-6 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm" aria-hidden="true">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
            </div>

            @if($title)
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ $title }}</h2>
            @endif

            @if($displayText)
                <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">{{ $displayText }}</p>
            @endif

            @if($show_social_proof && !empty($social_proof_text))
                <p class="text-sm text-white/60 mb-6 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    {{ $social_proof_text }}
                </p>
            @endif

            <form class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto" @submit.prevent aria-label="{{ $title }}">
                <label for="newsletter-email" class="sr-only">{{ $placeholder }}</label>
                <input type="email"
                       id="newsletter-email"
                       name="email"
                       placeholder="{{ $placeholder }}"
                       autocomplete="email"
                       class="flex-1 px-5 py-4 rounded-xl bg-white border-0 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#E67E22] text-base shadow-inner"
                       required>
                <button type="submit"
                        class="px-8 py-4 bg-[#E67E22] hover:bg-[#d35400] text-white rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    {{ $button_label }}
                </button>
            </form>

            @if($privacy_text)
                <p class="text-sm text-white/50 mt-5">{{ $privacy_text }}</p>
            @endif
        </div>
    </div>
</section>
