{{-- Sezione "ultimi messaggi": schede che aprono la modale (JSON: section.id, class, title, items[id, date, title, excerpt], more). --}}
<div class="it-page-section {{ $section['class'] ?? '' }}" id="{{ $section['id'] ?? '' }}">
    <div class="cmp-card">
        <div class="card">
            <div class="card-header border-0 p-0 mb-lg-20">
                <div class="d-flex"><h2 class="title-xxlarge">{{ $section['title'] ?? '' }}</h2></div>
            </div>
            <div class="card-body p-0">
                @foreach ($section['items'] ?? [] as $item)
                    <div class="cmp-card-latest-messages mb-3" data-bs-toggle="modal" data-bs-target="#{{ $d('modal.id') }}" id="{{ $loop->iteration }}">
                        <div class="card shadow-sm px-4 pt-4 pb-4">
                            <span class="visually-hidden">{{ $section['category_label'] ?? '' }}</span>
                            <div class="card-header border-0 p-0 m-0"><date class="date-xsmall">{{ $item['date'] ?? '' }}</date></div>
                            <div class="card-body p-0 my-2">
                                <h3 class="title-small-semi-bold t-primary m-0 mb-1"><a href="#" class="text-decoration-none">{{ $item['title'] ?? '' }}</a></h3>
                                <p class="text-paragraph text-truncate">{{ $item['excerpt'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="button" class="btn btn-xs btn-me btn-label t-primary px-0"><span class="">{{ $section['more'] ?? '' }}</span></button>
            </div>
        </div>
    </div>
</div>
