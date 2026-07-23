@props(['source_url' => ''])

@if($source_url)
<div class="mt-4">
    <a href="{{ $source_url }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium" target="_blank" rel="noopener noreferrer">
        <span>{{ __('sixteen::common.labels.source.label', ['default' => 'Vedi la pagina di riferimento']) }}</span>
        <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4 ml-2" />
    </a>
</div>
@endif
