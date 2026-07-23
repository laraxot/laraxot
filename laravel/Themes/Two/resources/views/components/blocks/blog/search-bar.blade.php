@props([
    'title' => '',
    'subtitle' => '',
    'placeholder' => 'Cerca articoli...',
    'show_advanced' => false,
    'show_suggestions' => false,
])

<section class="bg-white py-8 border-b border-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="w-full md:w-1/2 lg:w-2/5">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <input
                        type="text"
                        placeholder="{{ $placeholder }}"
                        class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E5A96]/30 focus:border-[#1E5A96] transition-all"
                    >
                </div>
            </div>
        </div>
    </div>
</section>
