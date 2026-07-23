{{-- Services Catalog List (AGID) --}}
@props([
    'items' => [], // array<array{title:string, href:string, category:?string, icon:?string, description:?string}>
    'empty' => __('Nessun servizio disponibile'),
    'cols' => 3,
])

@php
    $grid = [1 => 'grid-cols-1', 2 => 'grid-cols-2', 3 => 'grid-cols-3', 4 => 'grid-cols-4'][$cols] ?? 'grid-cols-3';
@endphp

<div class="grid {{ $grid }} gap-6">
    @forelse($items as $item)
        <article class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 h-full flex flex-col">
            <div class="text-sm text-gray-500 mb-2 flex items-center gap-2">
                @if(($item['icon'] ?? null))
                    <span aria-hidden="true">🔹</span>
                @endif
                @if(($item['category'] ?? null))
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $item['category'] }}</span>
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                <a href="{{ $item['href'] ?? '#' }}" class="hover:text-italia-blue-600">{{ $item['title'] ?? '' }}</a>
            </h3>
            @if(($item['description'] ?? null))
                <p class="text-gray-700 mb-4">{{ $item['description'] }}</p>
            @endif
            <div class="mt-auto">
                <a href="{{ $item['href'] ?? '#' }}" class="inline-flex items-center gap-2 text-italia-blue-600 hover:underline font-medium">
                    <span>{{ __('Vai al servizio') }}</span>
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </article>
    @empty
        <div class="col-span-full text-gray-600">{{ $empty }}</div>
    @endforelse
    {{ $slot }}
</div>



