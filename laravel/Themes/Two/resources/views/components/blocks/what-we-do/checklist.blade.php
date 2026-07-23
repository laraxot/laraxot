@props([
    'title' => '',
    'subtitle' => '',
    'checklist' => [],
    'id' => 'controlli',
    'callout_title' => 'Perché è fondamentale?',
    'callout_text' => 'Un controllo accurato previene guasti, sovraesposizioni e sanzioni, proteggendo il tuo investimento e la tua reputazione professionale.',
])

<section id="{{ $id }}" class="py-20 bg-gray-50 scroll-mt-20">
    <div class="container mx-auto px-4">
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
            <p class="text-lg text-gray-600 leading-relaxed mb-8 max-w-4xl mx-auto">{{ $subtitle }}</p>

            <div class="bg-orange-50 border-l-4 border-orange-600 p-6 rounded-r-xl max-w-3xl mx-auto text-left">
                <h3 class="font-bold text-orange-700 mb-2">{{ $callout_title }}</h3>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $callout_text }}</p>
            </div>
        </div>

        {{-- Grid: White cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($checklist as $i => $item)
                @php
                    $colors = ['bg-brand-blue', 'bg-brand-green', 'bg-brand-orange', 'bg-purple-600', 'bg-gray-700'];
                    $ci = $i % 5;
                    $bgClass = $colors[$ci];
                    $cardSpan = ($i == 3 && count($checklist) == 5) ? 'md:col-span-2 lg:col-span-2' : '';
                @endphp
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 {{ $cardSpan }}">
                    <div class="w-12 h-12 {{ $bgClass }} rounded-lg flex items-center justify-center mb-6">
                        @if (str_contains($item['icon'] ?? '', 'chart'))
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        @elseif (str_contains($item['icon'] ?? '', 'shield'))
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @elseif (str_contains($item['icon'] ?? '', 'cpu') || str_contains($item['icon'] ?? '', 'chip') || str_contains($item['icon'] ?? '', 'bolt'))
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @elseif (str_contains($item['icon'] ?? '', 'light') || str_contains($item['icon'] ?? '', 'bulb') || str_contains($item['icon'] ?? '', 'bell'))
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @elseif (str_contains($item['icon'] ?? '', 'document'))
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $item['title'] ?? '' }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $item['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>