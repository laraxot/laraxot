{{-- Sezione della scheda servizio (JSON: section.type, id, class, title + campi del tipo). Un nuovo tipo = un nuovo `@case`. --}}
<section class="it-page-section{{ filled($section['class'] ?? null) ? ' '.$section['class'] : '' }}" id="{{ $section['id'] ?? '' }}">
    <h2 @class(['title-xxlarge mb-3' => ($section['type'] ?? '') !== 'contacts', 'mb-3' => ($section['type'] ?? '') === 'contacts'])>{{ $section['title'] ?? '' }}</h2>
    @switch($section['type'] ?? '')
        @case('text')
            <p class="text-paragraph lora mb-0"> @foreach ($section['paragraphs'] ?? [] as $paragraph){{ $paragraph }} @if (! $loop->last)<br><br> @endif @endforeach </p>
            @break
        @case('list')
            <p class="text-paragraph lora mb-0">{{ $section['intro'] ?? '' }}</p>
            <ul class="list-wrapper link-list lora">
                @foreach ($section['items'] ?? [] as $item)
                    <li class="list-item"><span>{{ $item }}</span></li>
                @endforeach
            </ul>
            @break
        @case('text-attachment')
            <p class="text-paragraph lora">{{ $section['text'] ?? '' }}</p>
            <div class="cmp-icon-link">
                <a class="list-item icon-left d-inline-block" href="{{ $section['attachment']['url'] ?? '#' }}" aria-label="{{ $section['attachment']['aria'] ?? '' }}" title="{{ $section['attachment']['aria'] ?? '' }}">
                    <span class="list-item-title-icon-wrapper">
                        <svg class="icon icon-primary icon-sm me-1" aria-hidden="true"><use href="{{ $icon('it-clip') }}"></use></svg>
                        <span class="list-item">{{ $section['attachment']['label'] ?? '' }}</span>
                    </span>
                </a>
            </div>
            @break
        @case('cta')
            <p class="text-paragraph lora">{{ $section['text'] ?? '' }}</p>
            <button type="button" class="btn btn-primary mobile-full"><span class="">{{ $section['primary'] ?? '' }}</span></button>
            <p class="text-paragraph mt-4 lora">{{ $section['alternative'] ?? '' }}</p>
            <button type="button" class="btn mobile-full btn-outline-primary t-primary bg-white"><span class="">{{ $section['secondary'] ?? '' }}</span></button>
            @break
        @case('contacts')
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 mb-30">
                    <div class="card-wrapper rounded h-auto pb-0">
                        <div class="card card-teaser card-teaser-info rounded shadow-sm p-3">
                            <div class="card-body pe-3">
                                <span class="title-small"><a class="text-decoration-none" href="#" data-element="service-area">{{ $section['office']['name'] ?? '' }}</a></span>
                                <p class="subtitle-small mb-0">{{ $section['office']['address'] ?? '' }} <br> {{ $section['office']['phone'] ?? '' }} <br>
                                    <a class="" href="mailto:{{ $section['office']['email'] ?? '' }}" aria-label="{{ sprintf($section['office']['email_aria'] ?? '%s', $section['office']['email'] ?? '') }}" title="{{ sprintf($section['office']['email_aria'] ?? '%s', $section['office']['email'] ?? '') }}">{{ $section['office']['email'] ?? '' }}</a></p>
                            </div>
                            <div class="avatar size-xl"><img src="{{ $section['office']['avatar']['src'] ?? '' }}" alt="{{ $section['office']['avatar']['alt'] ?? '' }}"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-30">
                    <span class="text-paragraph-small">{{ $section['topics']['title'] ?? '' }}</span>
                    <ul class="d-flex flex-wrap gap-2 mt-10 mb-3">
                        @foreach ($section['topics']['items'] ?? [] as $topic)
                            <li><a class="chip chip-simple" href="#" data-element="service-topic"><span class="chip-label">{{ $topic }}</span></a></li>
                        @endforeach
                    </ul>
                    <p class="text-paragraph-small mb-0">{{ $section['updated'] ?? '' }}</p>
                </div>
            </div>
            @break
    @endswitch
</section>
