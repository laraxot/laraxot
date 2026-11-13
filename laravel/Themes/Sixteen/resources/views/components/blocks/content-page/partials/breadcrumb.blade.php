{{-- Briciole di pane del riferimento. Parametro: $breadcrumb = {items[{label,url}], current, class?} (class = classi extra, es. mt-0). --}}
<div class="{{ trim('cmp-breadcrumbs '.data_get($breadcrumb, 'class', '')) }}" role="navigation">
    <nav class="breadcrumb-container" aria-label="{{ __('fixcity::global.breadcrumb') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
        @foreach (data_get($breadcrumb, 'items', []) as $crumb)
            <li class="breadcrumb-item"><a href="{{ $crumb['url'] ?? '#' }}">{{ $crumb['label'] ?? '' }}</a><span class="separator">/</span></li>
        @endforeach
        <li class="breadcrumb-item active" aria-current="page">{{ data_get($breadcrumb, 'current') }}</li>
    </ol></nav>
</div>
