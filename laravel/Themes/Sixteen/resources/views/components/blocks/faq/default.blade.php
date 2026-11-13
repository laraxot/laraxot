@props(['blocks' => []])

@php
    $faqData = [];
    foreach ($blocks as $block) {
        if (isset($block['data']) && is_array($block['data'])) {
            $faqData = array_merge($faqData, $block['data']);
        }
    }
    $questions = $faqData['questions'] ?? [
        ['question' => 'Come posso inviare una segnalazione?', 'answer' => 'Puoi inviare una segnalazione compilando il modulo online, inviando un email o recandoti di persona presso un ufficio comunale.'],
        ['question' => 'Quali sono i servizi attivi sul territorio?', 'answer' => 'I servizi attivi includono anagrafe, stato civile, sportello unico, trasporti, cultura e molto altro.'],
        ['question' => 'Come posso contattare il Comune?', 'answer' => 'Puoi contattarci tramite modulo online, email, numero verde o recandoti presso l\'Ufficio Relazioni con il Pubblico.'],
        ['question' => 'Quali sono gli orari di apertura?', 'answer' => 'Gli uffici sono aperti dal lunedì al venerdì dalle 8:30 alle 13:30 e il sabato dalle 8:30 alle 13:00.'],
    ];
@endphp

<section aria-label="{{ __('pub_theme::blocks_content.default.faq.label') }}" class="py-12 bg-base-100">
    <div class="max-w-3xl mx-auto px-6">
        <h2 class="text-2xl font-bold mb-8">{{ __('pub_theme::blocks_content.default.frequently_asked_questions.label') }}</h2>
        <div class="space-y-4" x-data="{ open: [] }">
            @foreach($questions as $i => $q)
                <div class="border border-base-200 rounded-lg overflow-hidden">
                    <button
                        type="button"
                        @click="open.includes({{ $i }}) ? open = open.filter(k => k !== {{ $i }}) : open.push({{ $i }})"
                        class="w-full flex items-center justify-between px-6 py-4 text-left font-medium text-base-800 bg-white hover:bg-base-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-expanded="false"
                        aria-controls="faq-{{ $i }}"
                    >
                        <span>{{ $q['question'] }}</span>
                        <svg class="w-5 h-5 text-base-600 transition-transform" :class="{ 'rotate-180': open.includes({{ $i }}) }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div
                        id="faq-{{ $i }}"
                        x-show="open.includes({{ $i }})"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="px-6 py-4 text-base-700 bg-base-50 border-t border-base-200"
                    >
                        {{ $q['answer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>