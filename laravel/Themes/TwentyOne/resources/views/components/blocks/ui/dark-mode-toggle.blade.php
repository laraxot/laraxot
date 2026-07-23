@props([
    'size' => 'md',
    'position' => 'header-right',
    'show_label' => false,
    'variant' => 'icon-only'
])

{{-- Block version for CMS integration --}}
<div class="dark-mode-toggle-block">
    <x-ui.dark-mode-toggle 
        :size="$size"
        :position="$position" 
        :show_label="$show_label"
        :variant="$variant"
    />
</div>