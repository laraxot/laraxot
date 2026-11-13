{{--
    Riquadro riepilogo `cmp-info-summary` (JSON: class = modificatori del riquadro, title [opzionale: h4], edit {label, url, hidden},
    rows[label, value | images[file|url, alt]], footer_hidden). `edit.click` [opzionale] = espressione Alpine del click (wizard: passa al passo di modifica). Parametro: $summary. Con `title` l'intestazione ha titolo e link "Modifica" opzionale;
    senza, solo il link a destra.
--}}
@php($hasTitle = filled($summary['title'] ?? null))
<div class="cmp-info-summary bg-white{{ filled($summary['class'] ?? null) ? ' '.$summary['class'] : '' }}">
    <div class="card">
        <div @class(['card-header border-bottom border-light p-0 mb-0', 'pb-2' => ! $hasTitle && ! ($summary['compact'] ?? false), 'd-flex justify-content-between d-flex justify-content-end' => $hasTitle, 'd-flex justify-content-end' => ! $hasTitle])>
            @if ($hasTitle)<h4 class="title-large-semi-bold mb-3">{{ $summary['title'] }}</h4>@endif
            @if (filled($summary['edit'] ?? null))
                <a href="{{ $summary['edit']['url'] ?? '#' }}" @class(['d-none' => $summary['edit']['hidden'] ?? false, 'text-decoration-none'])@if (filled($summary['edit']['click'] ?? null)) x-on:click.prevent="{{ $summary['edit']['click'] }}"@endif>
                    <span class="text-button-sm-semi t-primary">{{ $summary['edit']['label'] ?? '' }}</span></a>
            @endif
        </div>
        <div class="card-body p-0">
            @foreach ($summary['rows'] ?? [] as $row)
                <div class="single-line-info border-light">
                    <div class="text-paragraph-small">{{ $row['label'] ?? '' }}</div>
                    @if (filled($row['images'] ?? null))
                        <div class="border-light border-0">
                            <div class="d-lg-flex gap-2 mt-3">
                                @foreach ($row['images'] as $image)
                                    <div><img src="{{ filled($image['url'] ?? null) ? $image['url'] : $assets.'/images/'.($image['file'] ?? '') }}" alt="{{ $image['alt'] ?? '' }}" @class(['img-fluid w-100', 'mb-3 mb-lg-0' => ! $loop->last])></div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="border-light"><p class="data-text"> {{ $row['value'] ?? '' }} </p></div>
                    @endif
                </div>
            @endforeach
        </div>
        <div @class(['card-footer p-0', 'd-none' => $summary['footer_hidden'] ?? true])></div>
    </div>
</div>
