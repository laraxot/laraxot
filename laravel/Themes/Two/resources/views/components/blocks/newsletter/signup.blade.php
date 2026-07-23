{{--
/**
 * Newsletter Signup Block - Theme Two
 * Professional newsletter form with gradient background, icon, and disclaimer
 */
--}}

<section class="py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a5a6f] to-emerald-700 rounded-3xl p-10 lg:p-16 text-center text-white shadow-xl">
            {{-- Mail Icon --}}
            <div class="w-16 h-16 mx-auto mb-6 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
            </div>

            @if(isset($title))
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ $title }}</h2>
            @endif

            @if(isset($description))
                <p class="text-lg text-white/70 mb-8 max-w-xl mx-auto">{{ $description }}</p>
            @endif

            <form action="{{ $form_action ?? '#' }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
                @csrf
                <input type="email" name="email" required
                       placeholder="{{ $placeholder ?? 'La tua email' }}"
                       class="flex-1 px-5 py-4 rounded-xl bg-white border-0 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 text-base shadow-inner">
                <button type="submit"
                        class="px-8 py-4 bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    {{ $button_label ?? 'Iscriviti' }}
                </button>
            </form>

            @if(isset($disclaimer))
                <p class="text-sm text-white/40 mt-4">{{ $disclaimer }}</p>
            @endif
        </div>
    </div>
</section>
