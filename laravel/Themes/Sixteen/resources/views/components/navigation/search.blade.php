{{--
    Site Search Bar (AGID compliant)
    Props:
    - action: string (default '/search')
    - method: string (default 'GET')
    - name: string (default 'q')
    - value: ?string
    - placeholder: string (default 'Cerca nel sito')
    - label: string (default 'Cerca nel sito')
    - submitLabel: string (default 'Cerca')
    - size: sm|md|lg (default 'md')
--}}
@props([
    'action' => '/search',
    'method' => 'GET',
    'name' => 'q',
    'value' => null,
    'placeholder' => __('Cerca nel sito'),
    'label' => __('Cerca nel sito'),
    'submitLabel' => __('Cerca'),
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'py-2 px-3 text-sm',
        'md' => 'py-3 px-4 text-base',
        'lg' => 'py-4 px-5 text-lg',
    ];
    $inputSize = $sizes[$size] ?? $sizes['md'];
@endphp

<form action="{{ $action }}" method="{{ strtolower($method) === 'post' ? 'POST' : 'GET' }}" role="search" aria-label="{{ $label }}" class="w-full">
    @if (strtolower($method) === 'post')
        @csrf
    @endif
    <div class="flex items-stretch gap-2">
        <label for="site-search-input" class="sr-only">{{ $label }}</label>
        <input
            id="site-search-input"
            name="{{ $name }}"
            type="search"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="w-full border-2 border-gray-300 rounded-md focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 {{ $inputSize }}"
        />
        <button type="submit" class="inline-flex items-center gap-2 bg-italia-blue-500 hover:bg-italia-blue-600 text-white font-semibold rounded-md px-5 {{$size==='lg'?'text-lg':''}}">
            <span aria-hidden="true" class="inline-block">🔍</span>
            <span>{{ $submitLabel }}</span>
        </button>
    </div>
</form>



