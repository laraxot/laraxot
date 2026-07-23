@php
    $mapId = $id ?? 'ticket-map';
    $mapDataUrl = $dataUrl ?? asset('data/tickets.json');
    $mapHeight = $height ?? 'clamp(360px,58vh,560px)';
    $mapAriaLabel = $ariaLabel ?? __('fixcity::ticket.map.image.alt');
@endphp

<map-lit
    id="{{ $mapId }}"
    data-url="{{ $mapDataUrl }}"
    height="{{ $mapHeight }}"
    style="height:{{ $mapHeight }};display:block;width:100%"
    aria-label="{{ $mapAriaLabel }}"
></map-lit>
