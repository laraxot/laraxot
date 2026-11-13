{{--
    Scheda `cmp-info-button-card` con dettagli apribili (accordion). Parametro: $card = [class (modificatore, es. mt-3), title,
    title_class (big-title | medium-title), info {label, value}, header_id, toggle_id, toggle_label, collapse_class, summary (vedi info-summary)].
--}}
<div class="cmp-info-button-card{{ filled($card['class'] ?? null) ? ' '.$card['class'] : '' }}">
    <div class="card p-3 p-lg-4">
        <div class="card-body p-0">
            <h3 class="{{ $card['title_class'] ?? 'big-title' }} mb-0">{{ $card['title'] ?? '' }}</h3>
            <p class="card-info">{{ $card['info']['label'] ?? '' }}@if (filled($card['info']['value'] ?? null)) <br>
                <span>{{ $card['info']['value'] }}</span>@endif</p>
            <div class="accordion-item">
                <div class="accordion-header"@if (filled($card['header_id'] ?? null)) id="{{ $card['header_id'] }}"@endif>
                    <button class="collapsed accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $card['toggle_id'] ?? '' }}" aria-expanded="false" aria-controls="{{ $card['toggle_id'] ?? '' }}">
                        <span class="d-flex align-items-center"> {{ $card['toggle_label'] ?? '' }} <svg class="icon icon-primary icon-sm"><use href="{{ $icon('it-expand') }}"></use></svg></span></button>
                </div>
                <div id="{{ $card['toggle_id'] ?? '' }}" class="accordion-collapse collapse{{ filled($card['collapse_class'] ?? null) ? ' '.$card['collapse_class'] : '' }}" role="region">
                    <div class="accordion-body p-0">
                        @include('pub_theme::components.blocks.report-partials.info-summary', ['summary' => $card['summary'] ?? []])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
