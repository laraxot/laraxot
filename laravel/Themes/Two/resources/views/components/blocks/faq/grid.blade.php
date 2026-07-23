@props([
    'title' => 'Risposte rapide ai tuoi dubbi',
    'faqs' => [],
])

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if(!empty($title))
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            </div>
        @endif

        <div class="max-w-4xl mx-auto space-y-4">
            @foreach($faqs as $index => $faq)
                <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900 pr-4">{{ $faq['question'] ?? '' }}</span>
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="px-6 pb-4">
                        <div class="text-gray-700 leading-relaxed">{!! $faq['answer'] ?? '' !!}</div>
                        @if(!empty($faq['category']))
                            <div class="mt-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ ucfirst($faq['category']) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
