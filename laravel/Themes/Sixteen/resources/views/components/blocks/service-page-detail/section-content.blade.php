@props(['section' => []])
{{--
    Sezione generica del dettaglio servizio: titolo e `blocks[]` scelti per `type` (richtext = paragrafi con a capo e liste,
    paragraph, subtitle, file = allegato scaricabile, button = pulsante primario o outline). `box.padding` (3|4) mette la sezione
    in un riquadro grigio. Un nuovo tipo di blocco = un nuovo `@case`.
--}}
@php
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $padding = $section['box']['padding'] ?? null;
@endphp
<section @class(['it-page-section mb-30', 'has-bg-grey' => filled($padding), 'p-3' => $padding === 3, 'p-4' => $padding === 4]) id="{{ $section['id'] ?? '' }}">
    <h2 class="mb-3">{{ $section['title'] ?? '' }}</h2>
    @foreach ($section['blocks'] ?? [] as $block)
        @switch($block['type'] ?? '')
            @case('richtext')
                <div class="richtext-wrapper lora"@if (filled($block['element'] ?? null)) data-element="{{ $block['element'] }}"@endif>
                    @foreach ($block['nodes'] ?? [] as $node)
                        @if (($node['type'] ?? '') === 'list')
                            <ul>
                                @foreach ($node['items'] ?? [] as $item)
                                    <li><span>{{ $item }}</span></li>
                                @endforeach
                            </ul>
                        @else
                            <p>@foreach ($node['segments'] ?? [] as $segment)@if (! $loop->first) <br> @endif{{ $segment }}@endforeach</p>
                        @endif
                    @endforeach
                </div>
                @break
            @case('paragraph')
                <p @class(['text-paragraph lora', 'mb-4' => ($block['spacing'] ?? '') === 'mb-4', 'mt-4' => ($block['spacing'] ?? '') === 'mt-4'])@if (filled($block['element'] ?? null)) data-element="{{ $block['element'] }}"@endif>{{ $block['text'] ?? '' }}</p>
                @break
            @case('subtitle')
                <h3 class="mb-3 subtitle-medium">{{ $block['text'] ?? '' }}</h3>
                @break
            @case('file')
                <div class="cmp-icon-link">
                    <a class="list-item icon-left d-inline-block" href="{{ $block['url'] ?? '#' }}" aria-label="{{ $block['aria'] ?? '' }}" title="{{ $block['title'] ?? '' }}"@if (filled($block['element'] ?? null)) data-element="{{ $block['element'] }}"@endif>
                        <span class="list-item-title-icon-wrapper">
                            <svg class="icon icon-primary icon-sm me-1" aria-hidden="true"><use href="{{ $icon($block['icon'] ?? 'it-clip') }}"></use></svg>
                            <span class="list-item">{{ $block['label'] ?? '' }}</span>
                        </span>
                    </a>
                </div>
                @break
            @case('button')
                <button type="button" @class(['btn', 'btn-primary' => ($block['style'] ?? '') === 'primary', 'btn-outline-primary t-primary bg-white' => ($block['style'] ?? '') !== 'primary', 'mobile-full']) data-element="{{ $block['element'] ?? '' }}"><span>{{ $block['label'] ?? '' }}</span></button>
                @break
        @endswitch
    @endforeach
</section>
