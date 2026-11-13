{{-- Sezione "ultime attivita": schede con icona e data (JSON: section.id, class, title, items[title, date], more). --}}
<div class="it-page-section {{ $section['class'] ?? '' }}" id="{{ $section['id'] ?? '' }}">
    <div class="cmp-card">
        <div class="card">
            <div class="card-header border-0 p-0 mb-lg-20 m-0">
                <div class="d-flex"><h2 class="title-xxlarge mb-3">{{ $section['title'] ?? '' }}</h2></div>
            </div>
            <div class="card-body p-0">
                @foreach ($section['items'] ?? [] as $item)
                    <div class="cmp-icon-card mb-3">
                        <div class="card pt-20 pb-4 ps-4 pr-30 drop-shadow">
                            <div class="cmp-card-title d-flex">
                                <svg class="icon icon-sm me-2" aria-hidden="true"><use href="{{ $icon($section['icon'] ?? 'it-files') }}"></use></svg>
                                <h3 class="t-primary mb-2 underline title-small-semi-bold"><a href="#">{{ $item['title'] ?? '' }}</a></h3>
                            </div>
                            <div class="cmp-icon-card__description"><date class="date-xsmall ml-30">{{ $item['date'] ?? '' }}</date></div>
                        </div>
                    </div>
                @endforeach
                <button type="button" class="btn btn-xs btn-me btn-label t-primary px-0"><span class="">{{ $section['more'] ?? '' }}</span></button>
            </div>
        </div>
    </div>
</div>
