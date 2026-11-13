{{--
    Sezione "select" (JSON: select_id, select_label, placeholder, options[], radio_cards{legend, name, items[id, title, rows[label, value]]}):
    menu a tendina e, se presenti, schede ufficio con radio sotto.
--}}
<div class="select-wrapper p-0 select-partials">
    <label for="{{ $section['select_id'] ?? '' }}" class="visually-hidden">{{ $section['select_label'] ?? '' }}</label>
    <select id="{{ $section['select_id'] ?? '' }}" class="">
        <option selected="selected" value="">{{ $section['placeholder'] ?? '' }}</option>
        @foreach ($section['options'] ?? [] as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
@if (filled($section['radio_cards']['items'] ?? null))
    <fieldset>
        <legend class="visually-hidden">{{ $section['radio_cards']['legend'] ?? '' }}</legend>
        @foreach ($section['radio_cards']['items'] as $card)
            <div class="cmp-info-radio radio-card">
                <div class="card p-3 p-lg-4">
                    <div class="card-header mb-0 p-0">
                        <div class="form-check m-0">
                            <input class="radio-input" name="{{ $section['radio_cards']['name'] ?? '' }}" type="radio" id="{{ $card['id'] ?? '' }}">
                            <label for="{{ $card['id'] ?? '' }}"><h3 class="big-title">{{ $card['title'] ?? '' }}</h3></label>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($card['rows'] ?? [] as $row)
                            <div class="info-wrapper">
                                <span class="info-wrapper__label">{{ $row['label'] ?? '' }}</span>
                                <p class="info-wrapper__value">{{ $row['value'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </fieldset>
@endif
