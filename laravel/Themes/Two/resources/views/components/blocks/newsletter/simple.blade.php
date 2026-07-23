@props([
    'title' => 'Rimani Aggiornato',
    'description' => 'Iscriviti alla nostra newsletter per ricevere aggiornamenti.',
    'placeholder' => 'La tua email',
    'button_label' => 'Iscriviti',
    'privacy_text' => '',
    'bg_color' => 'gradient',
])

@php
    $bgClass = match($bg_color) {
        'gradient' => 'bg-gradient-to-br from-[#1e3a5f] via-[#1a5a6f] to-emerald-700',
        'brand-blue' => 'bg-gradient-to-br from-[#1E5A96] to-[#164575]',
        'brand-green' => 'bg-gradient-to-r from-[#2D8659] to-[#2D8659]/90',
        default => 'bg-gradient-to-br from-[#1E5A96] to-[#164575]',
    };
@endphp

<section class="py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="{{ $bgClass }} rounded-3xl p-10 lg:p-16 text-center text-white shadow-xl">
            {{-- Mail Icon --}}
            <div class="w-16 h-16 mx-auto mb-6 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
            </div>

            @if($title)
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ $title }}</h2>
            @endif

            @if($description)
                <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">{{ $description }}</p>
            @endif

            <form class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto" @submit.prevent>
                <input type="email"
                       placeholder="{{ $placeholder }}"
                       class="flex-1 px-5 py-4 rounded-xl bg-white border-0 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#E67E22] text-base shadow-inner"
                       required>
                <button type="submit"
                        class="px-8 py-4 bg-[#E67E22] hover:bg-[#d35400] text-white rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    {{ $button_label }}
                </button>
            </form>

            @if($privacy_text)
                <p class="text-sm text-white/50 mt-5">{{ $privacy_text }}</p>
            @endif
        </div>
    </div>
</section>
