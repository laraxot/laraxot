{{-- 
/**
 * Accordion Component - Bootstrap Italia Compliant
 * 
 * Vertically collapsible accordion component based on Bootstrap's Collapse
 * Used for organizing content in expandable sections
 * 
 * @param string $accordionId Unique ID for the accordion group
 * @param array $items Array of accordion items with 'id', 'title', 'content', 'expanded', 'headingLevel'
 * @param bool $flush Whether to render flush accordion (no borders)
 * @param string $headingLevel Default heading level for accordion headers (h2, h3, etc.)
 */
--}}

@props([
    'accordionId' => 'accordion-' . uniqid(),
    'items' => [],
    'flush' => false,
    'headingLevel' => 'h2'
])

@php
    $accordionClasses = collect(['accordion']);
    if ($flush) {
        $accordionClasses->push('accordion-flush');
    }
    $classes = $accordionClasses->implode(' ');
@endphp

<div class="{{ $classes }}" id="{{ $accordionId }}">
    @if(!empty($items))
        @foreach($items as $index => $item)
            @php
                $itemId = $item['id'] ?? 'item-' . $index;
                $collapseId = 'collapse' . ucfirst($itemId);
                $headingId = 'heading' . ucfirst($itemId);
                $isExpanded = $item['expanded'] ?? ($index === 0);
                $itemHeadingLevel = $item['headingLevel'] ?? $headingLevel;
            @endphp
            
            <div class="accordion-item">
                <{{ $itemHeadingLevel }} class="accordion-header" id="{{ $headingId }}">
                    <button 
                        class="accordion-button {{ $isExpanded ? '' : 'collapsed' }}" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#{{ $collapseId }}" 
                        aria-expanded="{{ $isExpanded ? 'true' : 'false' }}" 
                        aria-controls="{{ $collapseId }}"
                    >
                        {{ $item['title'] }}
                    </button>
                </{{ $itemHeadingLevel }}>
                
                <div 
                    id="{{ $collapseId }}" 
                    class="accordion-collapse collapse {{ $isExpanded ? 'show' : '' }}" 
                    role="region" 
                    aria-labelledby="{{ $headingId }}"
                    data-bs-parent="#{{ $accordionId }}"
                >
                    <div class="accordion-body">
                        {!! $item['content'] !!}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        {{-- Slot-based single accordion item --}}
        <div class="accordion-item">
            {{ $slot }}
        </div>
    @endif
</div>

{{-- 
Usage Examples:

1. Array-based accordion (recommended for multiple items):
<x-pub_theme::accordion
    :items="[
        [
            'id' => 'item1',
            'title' => 'Elemento Richiudibile #1',
            'content' => 'Vestibulum hendrerit ultrices nibh, sed pharetra lacus ultrices eget.',
            'expanded' => true
        ],
        [
            'id' => 'item2', 
            'title' => 'Elemento Richiudibile #2',
            'content' => 'Morbi et ipsum et sapien dapibus facilisis. Integer eget semper nibh.',
            'expanded' => false
        ],
        [
            'id' => 'item3',
            'title' => 'Elemento Richiudibile #3', 
            'content' => 'Proin enim nulla, egestas ac rutrum eget, ullamcorper nec turpis.',
            'expanded' => false
        ]
    ]"
    accordion-id="collapseExample" />

2. Flush accordion (no borders):
<x-pub_theme::accordion
    :flush="true"
    :items="$accordionData" />

3. Custom heading level:
<x-pub_theme::accordion
    :items="$items"
    heading-level="h3" />

4. Slot-based accordion (for custom content):
<x-pub_theme::accordion accordion-id="customAccordion">
    <h2 class="accordion-header" id="headingCustom">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCustom">
            Custom Accordion Item
        </button>
    </h2>
    <div id="collapseCustom" class="accordion-collapse collapse show" data-bs-parent="#customAccordion">
        <div class="accordion-body">
            <p>Custom accordion content with full control over markup.</p>
        </div>
    </div>
</x-pub_theme::accordion>

Bootstrap Italia Classes Reference:
- .accordion: Base accordion container
- .accordion-flush: Removes borders and rounded corners
- .accordion-item: Individual accordion item
- .accordion-header: Header container for accordion button
- .accordion-button: Clickable button to toggle accordion
- .accordion-collapse: Collapsible content container  
- .accordion-body: Content wrapper inside collapsed area
- .collapsed: Class for collapsed state
- .show: Class for expanded state
--}}