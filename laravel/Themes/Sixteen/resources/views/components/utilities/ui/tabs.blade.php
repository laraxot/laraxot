@props([
    'type' => 'horizontal',
    'active' => null,
    'id' => null,
    'contentClass' => '',
    'navClass' => '',
    'fullWidth' => false,
    'iconPosition' => 'left',
    'responsive' => true,
])

@php
    $orientation = $type === 'vertical' ? 'vertical' : 'horizontal';
    $tabsId = $id ?? 'tabs-' . uniqid();
    $fullWidthClass = $fullWidth ? 'nav-tabs-full-width' : '';
    $responsiveClass = $responsive ? 'nav-tabs-responsive' : '';
    $iconPositionClass = $iconPosition === 'right' ? 'icon-right' : 'icon-left';
@endphp

<div class="sixteen-tabs-container">
    <div class="tabs-wrapper {{ $orientation }} {{ $responsiveClass }}" data-tabs-id="{{ $tabsId }}">
        <ul class="nav nav-tabs {{ $navClass }} {{ $fullWidthClass }} {{ $iconPositionClass }}" role="tablist">
            {{ $header }}
        </ul>
        
        <div class="tab-content {{ $contentClass }}">
            {{ $content }}
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Tab system initialization logic
                const tabsContainer = document.querySelector('[data-tabs-id="{{ $tabsId }}"]');
                if (tabsContainer) {
                    const tabs = new BootstrapItalia.Tabs(tabsContainer);
                    @if($active)
                        tabs.activate('{{ $active }}');
                    @endif
                    
                    // Handle keyboard navigation
                    const tabLinks = tabsContainer.querySelectorAll('[role="tab"]');
                    tabLinks.forEach(tab => {
                        tab.addEventListener('keydown', (e) => {
                            const currentIndex = Array.from(tabLinks).indexOf(e.currentTarget);
                            let nextTab;
                            
                            switch (e.key) {
                                case 'ArrowRight':
                                    nextTab = tabLinks[(currentIndex + 1) % tabLinks.length];
                                    nextTab.focus();
                                    nextTab.click();
                                    e.preventDefault();
                                    break;
                                case 'ArrowLeft':
                                    nextTab = tabLinks[(currentIndex - 1 + tabLinks.length) % tabLinks.length];
                                    nextTab.focus();
                                    nextTab.click();
                                    e.preventDefault();
                                    break;
                                case 'Home':
                                    tabLinks[0].focus();
                                    tabLinks[0].click();
                                    e.preventDefault();
                                    break;
                                case 'End':
                                    tabLinks[tabLinks.length - 1].focus();
                                    tabLinks[tabLinks.length - 1].click();
                                    e.preventDefault();
                                    break;
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
@endonce