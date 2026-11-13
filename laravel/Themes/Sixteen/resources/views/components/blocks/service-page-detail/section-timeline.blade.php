@props(['section' => []])
{{--
    Sezione "Tempi e scadenze": testo introduttivo (`text.nodes[]`, paragrafi con a capo) e calendario verticale (`dates[]`:
    year, day, month, label).
--}}
<section class="it-page-section mb-30" id="{{ $section['id'] ?? '' }}">
    <div class="cmp-timeline">
        <h2 class="title-xxlarge mb-3">{{ $section['title'] ?? '' }}</h2>
        <div class="richtext-wrapper lora"@if (filled($section['text']['element'] ?? null)) data-element="{{ $section['text']['element'] }}"@endif>
            @foreach ($section['text']['nodes'] ?? [] as $node)
                <p>@foreach ($node['segments'] ?? [] as $segment)@if (! $loop->first) <br> @endif{{ $segment }}@endforeach</p>
            @endforeach
        </div>
        <div class="calendar-vertical mb-3"@if (filled($section['calendar_element'] ?? null)) data-element="{{ $section['calendar_element'] }}"@endif>
            @foreach ($section['dates'] ?? [] as $date)
                <div class="calendar-date">
                    <h3 class="calendar-date-day">
                        <small class="calendar-date-day__year">{{ $date['year'] ?? '' }}</small>
                        <span class="title-xxlarge-regular d-flex justify-content-center">{{ $date['day'] ?? '' }}</span>
                        <small class="calendar-date-day__month">{{ $date['month'] ?? '' }}</small>
                    </h3>
                    <div class="calendar-date-description rounded">
                        <div class="calendar-date-description-content">
                            <h4 class="h5 mb-0">{{ $date['label'] ?? '' }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
