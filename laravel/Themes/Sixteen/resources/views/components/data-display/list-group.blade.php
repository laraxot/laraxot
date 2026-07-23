{{--
/**
 * List Group Component
 * 
 * Bootstrap Italia compliant list group component with support for:
 * - Multiple variants (default, flush, numbered)
 * - Interactive items with actions
 * - Badge support
 * - Icon support
 * - Custom content slots
 * - Accessibility features
 * 
 * @param string $variant - List group variant (default, flush, numbered)
 * @param bool $interactive - Enable interactive items
 * @param string $size - Size variant (sm, md, lg)
 * @param array $items - Array of list items
 * @param string $class - Additional CSS classes
 * 
 * @example
 * <x-list-group 
 *     :items="[
 *         ['text' => 'Primo elemento', 'badge' => '3', 'active' => true],
 *         ['text' => 'Secondo elemento', 'icon' => 'heroicon-o-check'],
 *         ['text' => 'Terzo elemento', 'href' => '/link']
 *     ]"
 *     interactive
 * />
 */
--}}

@props([
    'variant' => 'default',
    'interactive' => false,
    'size' => 'md',
    'items' => [],
    'class' => ''
])

@php
    $baseClasses = 'list-group';
    
    // Variant classes
    $variantClasses = match($variant) {
        'flush' => 'list-group-flush',
        'numbered' => 'list-group-numbered',
        default => ''
    };
    
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'list-group-sm',
        'lg' => 'list-group-lg',
        default => ''
    };
    
    $classes = trim($baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $class);
@endphp

<ul {{ $attributes->merge(['class' => $classes]) }} role="list">
    @if(empty($items))
        {{ $slot }}
    @else
        @foreach($items as $index => $item)
            @php
                $itemClasses = 'list-group-item';
                $itemAttributes = [];
                
                // Active state
                if (!empty($item['active']) && $item['active']) {
                    $itemClasses .= ' active';
                }
                
                // Disabled state
                if (!empty($item['disabled']) && $item['disabled']) {
                    $itemClasses .= ' disabled';
                    $itemAttributes['aria-disabled'] = 'true';
                }
                
                // Interactive items
                if ($interactive || !empty($item['href']) || !empty($item['action'])) {
                    $itemClasses .= ' list-group-item-action';
                }
                
                // Custom classes
                if (!empty($item['class'])) {
                    $itemClasses .= ' ' . $item['class'];
                }
                
                // Link attributes
                if (!empty($item['href'])) {
                    $itemAttributes['href'] = $item['href'];
                }
                
                if (!empty($item['target'])) {
                    $itemAttributes['target'] = $item['target'];
                }
                
                if (!empty($item['rel'])) {
                    $itemAttributes['rel'] = $item['rel'];
                }
            @endphp
            
            @if(!empty($item['href']))
                <a {{ $attributes->merge($itemAttributes)->merge(['class' => $itemClasses]) }}>
                    @if(!empty($item['icon']))
                        <x-filament::icon icon="heroicon-o-{{" $item['icon'] }} class="me-2" />
                    @endif
                    
                    <span class="list-group-item-text">{{ $item['text'] ?? '' }}</span>
                    
                    @if(!empty($item['badge']))
                        <span class="badge bg-primary rounded-pill ms-auto">{{ $item['badge'] }}</span>
                    @endif
                    
                    @if(!empty($item['secondary']))
                        <small class="text-muted d-block">{{ $item['secondary'] }}</small>
                    @endif
                </a>
            @else
                <li {{ $attributes->merge($itemAttributes)->merge(['class' => $itemClasses]) }}>
                    @if(!empty($item['icon']))
                        <x-filament::icon icon="heroicon-o-{{" $item['icon'] }} class="me-2" />
                    @endif
                    
                    <span class="list-group-item-text">{{ $item['text'] ?? '' }}</span>
                    
                    @if(!empty($item['badge']))
                        <span class="badge bg-primary rounded-pill ms-auto">{{ $item['badge'] }}</span>
                    @endif
                    
                    @if(!empty($item['secondary']))
                        <small class="text-muted d-block">{{ $item['secondary'] }}</small>
                    @endif
                    
                    @if(!empty($item['action']))
                        <button 
                            type="button" 
                            class="btn btn-sm btn-outline-primary ms-auto"
                            wire:click="{{ $item['action'] }}"
                        >
                            Azione
                        </button>
                    @endif
                </li>
            @endif
        @endforeach
    @endif
</ul>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('listGroup', () => ({
        selectedItems: [],
        
        init() {
            // Initialize interactive functionality
            if (this.$el.hasAttribute('data-interactive')) {
                this.setupInteractive();
            }
        },
        
        setupInteractive() {
            const items = this.$el.querySelectorAll('.list-group-item-action');
            
            items.forEach(item => {
                item.addEventListener('click', (e) => {
                    if (item.classList.contains('disabled')) {
                        e.preventDefault();
                        return;
                    }
                    
                    // Handle selection
                    if (this.$el.hasAttribute('data-selectable')) {
                        this.toggleSelection(item);
                    }
                    
                    // Emit event
                    this.$dispatch('list-group-item-clicked', {
                        item: item,
                        text: item.querySelector('.list-group-item-text')?.textContent,
                        index: Array.from(items).indexOf(item)
                    });
                });
            });
        },
        
        toggleSelection(item) {
            if (item.classList.contains('active')) {
                item.classList.remove('active');
                this.selectedItems = this.selectedItems.filter(selected => selected !== item);
            } else {
                item.classList.add('active');
                this.selectedItems.push(item);
            }
            
            this.$dispatch('list-group-selection-changed', {
                selectedItems: this.selectedItems,
                selectedCount: this.selectedItems.length
            });
        },
        
        clearSelection() {
            this.selectedItems.forEach(item => {
                item.classList.remove('active');
            });
            this.selectedItems = [];
            
            this.$dispatch('list-group-selection-cleared');
        }
    }));
});
</script>
@endpush
