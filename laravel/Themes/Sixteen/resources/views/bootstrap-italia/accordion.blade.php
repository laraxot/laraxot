{{-- Bootstrap Italia Accordion Component --}}
@props([
    'items' => [],
    'id' => 'accordion-' . uniqid(),
    'firstOpen' => false,
])

<div class="accordion accordion-italia" id="{{ $id }}">
    @foreach($items as $index => $item)
    @php
        $itemId = $item['id'] ?? $id . '-item-' . $index;
        $isOpen = $firstOpen && $index === 0;
    @endphp
    <div class="accordion-item">
        <h2 class="accordion-header" id="{{ $itemId }}-heading">
            <button class="accordion-button {{ $isOpen ? '' : 'collapsed' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $itemId }}-collapse"
                    aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                    aria-controls="{{ $itemId }}-collapse"
                    role=&quot;button&quot;>
                {{ $item['title'] }}
            </button>
        </h2>
        <div id="{{ $itemId }}-collapse"
             class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}"
             aria-labelledby="{{ $itemId }}-heading"
             data-bs-parent="#{{ $id }}">
            <div class="accordion-body">
                {{ $item['content'] }}
            </div>
        </div>
    </div>
    @endforeach
</div>
