{{--
    Tabs Map/List Block - Bootstrap Italia Exact Replica with Modals
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
--}}
@props(['data' => []])

@php
    $tabs = $data['tabs'] ?? [];
    $cta = $data['cta'] ?? [];
    $items = $data['items'] ?? [];
    $resultsCount = $data['results_count'] ?? 645;
    $filters = $data['filters'] ?? [];
@endphp

<div class="row align-items-start gx-4">
    <div class="col-lg-3 d-none d-lg-block">
        <fieldset>
            <legend class="h6 text-uppercase category-list__title">categoria</legend>
            <div class="categoy-list pb-4">
                <ul>
                    @foreach(array_filter($filters ?? []) as $filter)
                    <li>
                        <div class="form-check">
                            <div class="checkbox-body border-light py-1">
                                <input type="checkbox" id="{{ $filter['id'] }}" name="category" value="{{ $filter['value'] ?? '' }}">
                                <label for="{{ $filter['id'] }}" class="subtitle-small_semi-bold mb-0 category-list__list">
                                    {{ $filter['label'] }} ({{ $filter['count'] ?? 0 }})
                                </label>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </fieldset>
    </div>

    <div class="col-lg-9">

    <div class="d-flex justify-content-between border-bottom border-light pb-3 mt-5">
        <span class="search-results">{{ $resultsCount }} Risultati</span>

        <button type="button" data-bs-toggle="modal" data-bs-target="#modal-categories" class="btn p-0 pe-2 d-lg-none">
            <span class="rounded-icon">
                <svg class="icon icon-primary icon-xs" aria-hidden="true">
                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-funnel"></use>
                </svg>
            </span>
            <span class="t-primary title-xsmall-semi-bold ms-1">Filtra</span>
        </button>
        <button type="button" class="btn p-0 pe-2 d-none d-lg-block">
            <span class="title-xsmall-semi-bold ms-1">Rimuovi tutti i filtri</span>
        </button>
    </div>

    <ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none" id="tabDisservizio" role="tablist">
        @foreach($tabs as $tab)
        <li class="nav-item w-100" role="tab" aria-selected="{{ $tab['active'] ? 'true' : 'false' }}" tabindex="-1">
            <a class="nav-link {{ $tab['active'] ? 'active' : '' }} title-medium-semi-bold pt-0" 
               href="#data-ex-disservizio{{ $loop->iteration }}" 
               aria-current="page" 
               data-bs-toggle="tab" 
               role="button" 
               aria-controls="disservizio{{ $loop->iteration }}" 
               aria-selected="{{ $tab['active'] ? 'true' : 'false' }}" 
               tabindex="-1">
                {{ $tab['label'] }}
            </a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        {{-- Map Tab --}}
        <div class="tab-pane fade show active" id="data-ex-disservizio1" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="map-box">
                        <img src="/themes/Sixteen/design-comuni/assets/images/map-placeholder.svg" alt="Mappa" class="w-100">
                        <button type="button" class="pin" data-bs-toggle="modal" data-bs-target="#modal-disservizio">
                            <img src="/themes/Sixteen/design-comuni/assets/images/map-pin.svg" alt="Pin di geolocalizzazione" title="Pin di geolocalizzazione">
                        </button>
                    </div>
                </div>
                @if($cta)
                <div class="col-lg-6 mt-50 mb-4 mb-lg-0">
                    <div class="cmp-text-button mt-0">
                        <h2 class="title-xxlarge mb-0">{{ $cta['title'] ?? 'Fai una segnalazione' }}</h2>
                        <div class="text-wrapper">
                            <p class="subtitle-small mb-3 mt-3">{{ $cta['text'] ?? 'Se vuoi aggiungere una segnalazione, puoi farlo dopo esserti autenticato con le tue credenziali SPID o CIE.' }}</p>
                        </div>
                        <div class="button-wrapper">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modal-disservizio" class="btn btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0">
                                <span class="">{{ $cta['button_text'] ?? 'Segnala disservizio' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- List Tab --}}
        <div class="tab-pane fade" id="data-ex-disservizio2" role="tabpanel">
            <div class="row">
                @foreach($items as $index => $item)
                <div class="cmp-card mb-4 mb-lg-30">
                    <div class="card has-bkg-grey shadow-sm">
                        <div class="card-body p-0">
                            <div class="cmp-info-button-card">
                                <div class="card p-3 p-lg-4">
                                    <div class="card-body p-0">
                                        <h3 class="medium-title mb-0">{{ $item['title'] ?? 'Segnalazione' }}</h3>
                                        <p class="card-info">Tipologia di segnalazione <br>
                                            <span>{{ $item['type'] ?? '' }}</span>
                                        </p>
                                        <div class="accordion-item">
                                            <div class="accordion-header">
                                                <button class="collapsed accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                                                    <div class="button-wrapper">
                                                        <span class="title-xsmall-semi-bold">Dettagli</span>
                                                        <div class="icon-wrapper">
                                                            <svg class="icon icon-xs icon-primary">
                                                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#accordion-segnalazioni">
                                                <div class="accordion-body">
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Data</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $item['date'] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Luogo</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $item['location'] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Stato</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $item['status'] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Dettaglio</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $item['description'] ?? 'Descrizione della segnalazione...' }}</p>
                                                        </div>
                                                    </div>
                                                    @if(isset($item['images']) && count($item['images']) > 0)
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Immagini</div>
                                                        <div class="border-light border-0">
                                                            <div class="d-lg-flex gap-2 mt-3">
                                                                @foreach($item['images'] as $img)
                                                                <div>
                                                                    <img src="{{ $img }}" alt="Immagine della segnalazione" class="img-fluid w-100 mb-3 mb-lg-0">
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info Summary Section --}}
                                        <div class="cmp-info-summary bg-white border-0">
                                            <div class="card">
                                                <div class="card-header border-bottom border-light p-0 mb-0 d-flex justify-content-end">
                                                    <a href="{{ $item['edit_url'] ?? '#' }}" class="d-none text-decoration-none"><span class="text-button-sm-semi t-primary">Modifica</span></a>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Indirizzo</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $item['location'] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Dettaglio</div>
                                                        <div class="border-light">
                                                            <p class="data-text">{{ $item['description'] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                    @if(isset($item['images']) && count($item['images']) > 0)
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">Immagini</div>
                                                        <div class="border-light border-0">
                                                            <div class="d-lg-flex gap-2 mt-3">
                                                                @foreach($item['images'] as $img)
                                                                <div>
                                                                    <img src="{{ $img }}" alt="Immagine della mappa di dove si trova il disservizio" class="img-fluid w-100 mb-3 mb-lg-0">
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="card-footer p-0 d-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
{{-- Modal Disservizio --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal-disservizio" aria-labelledby="modalDisservizioTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title title-small-semi-bold" id="modalDisservizioTitle">{{ $items[0]['title'] ?? 'Segnalazione' }}</h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Chiudi finestra modale">
                    <svg class="icon">
                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-close"></use>
                    </svg>
                </button>
            </div>
            <div class="modal-body text-black">
                <div class="border-bottom border-light">
                    <h3 class="title-xsmall border-light pt-2">Titolo</h3>
                    <p class="subtitle-small pb-2">{{ $items[0]['title'] ?? '' }}</p>
                </div>
                <div class="border-bottom border-light">
                    <h3 class="title-xsmall border-light pt-2">Tipologia di segnalazione</h3>
                    <p class="subtitle-small pb-2">{{ $items[0]['type'] ?? '' }}</p>
                </div>
                <div class="border-bottom border-light">
                    <h3 class="title-xsmall border-light pt-2">Indirizzo</h3>
                    <p class="subtitle-small pb-2">{{ $items[0]['location'] ?? '' }}</p>
                </div>
                <div class="border-bottom border-light">
                    <h3 class="title-xsmall border-light pt-2">Dettaglio</h3>
                    <p class="subtitle-small pb-2">{{ $items[0]['description'] ?? '' }}</p>
                </div>
                <h3 class="title-xsmall border-light pt-2">Immagini</h3>
                @if(isset($items[0]['images']) && count($items[0]['images']) > 0)
                    @foreach($items[0]['images'] as $img)
                    <img src="{{ $img }}" class="w-100 mb-2" alt="immagine della mappa di dove si trova il disservizio">
                    @endforeach
                @else
                    <img src="/themes/Sixteen/design-comuni/assets/images/modal-disservizio-placeholder.png" class="w-100 mb-2" alt="immagine della mappa di dove si trova il disservizio">
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary w-100 btn-sm" type="button" data-bs-dismiss="modal" aria-label="Chiudi finestra modale">Chiudi</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Categories (Mobile Filter) --}}
<div class="modal fade" id="modal-categories" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title title-small-semi-bold">Filtra per categoria</h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Chiudi">
                    <svg class="icon">
                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-close"></use>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <fieldset>
                    <legend class="h6 text-uppercase category-list__title">categoria</legend>
                    <div class="categoy-list pb-4">
                        <ul>
                            <li>
                                <div class="form-check">
                                    <div class="checkbox-body border-light py-1">
                                        <input type="checkbox" id="modal-water" name="category" value="acqua,allagamenti,fogne">
                                        <label for="modal-water" class="subtitle-small_semi-bold mb-0 category-list__list">Acqua, allagamenti, problemi fognari (21)</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <div class="checkbox-body border-light py-1">
                                        <input type="checkbox" id="modal-enviroment" name="category" value="inquinamneto">
                                        <label for="modal-enviroment" class="subtitle-small_semi-bold mb-0 category-list__list">Ambiente, inquinamento, protezione ambientale (14)</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <div class="checkbox-body border-light py-1">
                                        <input type="checkbox" id="modal-waste" name="category" value="igiene">
                                        <label for="modal-waste" class="subtitle-small_semi-bold mb-0 category-list__list">Igiene urbana, rifiuti, pulizia e decoro (321)</label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary w-100 btn-sm" type="button" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

{{-- Rating Section --}}
<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0">
                                <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">Quanto sono chiare le informazioni su questa pagina?</h2>
                            </div>
                            <div class="card-body">
                                <fieldset class="rating">
                                    <legend class="visually-hidden">Valuta da 1 a 5 stelle la pagina</legend>
                                    <input type="radio" id="star5a" name="ratingA" value="5">
                                    <label class="full rating-star active" for="star5a" data-element="feedback-rate-5">
                                        <svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"></path>
                                        </svg>
                                        <span class="visually-hidden">Valuta 5 stelle su 5</span>
                                    </label>
                                    <input type="radio" id="star4a" name="ratingA" value="4">
                                    <label class="full rating-star active" for="star4a" data-element="feedback-rate-4">
                                        <svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"></path>
                                        </svg>
                                        <span class="visually-hidden">Valuta 4 stelle su 5</span>
                                    </label>
                                    <input type="radio" id="star3a" name="ratingA" value="3">
                                    <label class="full rating-star active" for="star3a" data-element="feedback-rate-3">
                                        <svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"></path>
                                        </svg>
                                        <span class="visually-hidden">Valuta 3 stelle su 5</span>
                                    </label>
                                    <input type="radio" id="star2a" name="ratingA" value="2">
                                    <label class="full rating-star active" for="star2a" data-element="feedback-rate-2">
                                        <svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"></path>
                                        </svg>
                                        <span class="visually-hidden">Valuta 2 stelle su 5</span>
                                    </label>
                                    <input type="radio" id="star1a" name="ratingA" value="1">
                                    <label class="full rating-star active" for="star1a" data-element="feedback-rate-1">
                                        <svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"></path>
                                        </svg>
                                        <span class="visually-hidden">Valuta 1 stella su 5</span>
                                    </label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="d-none" data-step="2">
                            <div class="cmp-steps-rating">
                                <fieldset>
                                    <legend class="iscrizioni-header w-100">
                                        <h3 class="step-title d-flex flex-column flex-lg-row flex-wrap align-items-lg-center justify-content-between drop-shadow">
                                            <span class="d-block text-wrap">Cosa non hai trovato chiaro?</span>
                                            <span class="step">1/2</span>
                                        </h3>
                                    </legend>
                                    <div class="cmp-steps-rating__body">
                                        <div class="cmp-radio-list">
                                            <ul class="list-unstyled">
                                                <li class="radio-body border-bottom border-light cmp-radio-list__item">
                                                    <input name="rating2" type="radio" id="radio-6">
                                                    <label for="radio-6" class="active" data-element="feedback-rating-answer">Non ho capito quali erano i documenti necessari</label>
                                                </li>
                                                <li class="radio-body border-bottom border-light cmp-radio-list__item">
                                                    <input name="rating2" type="radio" id="radio-7">
                                                    <label for="radio-7" class="active" data-element="feedback-rating-answer">A volte le indicazioni non erano complete</label>
                                                </li>
                                                <li class="radio-body border-bottom border-light cmp-radio-list__item">
                                                    <input name="rating2" type="radio" id="radio-8">
                                                    <label for="radio-8" class="active" data-element="feedback-rating-answer">A volte non capivo se stavo procedendo correttamente</label>
                                                </li>
                                                <li class="radio-body border-bottom border-light cmp-radio-list__item">
                                                    <input name="rating2" type="radio" id="radio-9">
                                                    <label for="radio-9" class="active" data-element="feedback-rating-answer">Ho avuto problemi tecnici</label>
                                                </li>
                                                <li class="radio-body border-bottom border-light cmp-radio-list__item">
                                                    <input name="rating2" type="radio" id="radio-10">
                                                    <label for="radio-10" class="active" data-element="feedback-rating-answer">Altro</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="d-none" data-step="3">
                            <div class="cmp-steps-rating">
                                <fieldset>
                                    <legend class="iscrizioni-header w-100">
                                        <h3 class="step-title d-flex flex-column flex-lg-row flex-wrap align-items-lg-center justify-content-between drop-shadow">
                                            <span class="d-block text-wrap">Vuoi aggiungere altri dettagli?</span>
                                            <span class="step">2/2</span>
                                        </h3>
                                    </legend>
                                    <div class="cmp-steps-rating__body">
                                        <div class="form-group">
                                            <label for="formGroupExampleInputWithHelp" class="">Dettaglio</label>
                                            <input type="text" class="form-control" id="formGroupExampleInputWithHelp" aria-describedby="formGroupExampleInputWithHelpDescription" maxlength="200" data-element="feedback-input-text">
                                            <small id="formGroupExampleInputWithHelpDescription" class="form-text">Inserire massimo 200 caratteri</small>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="d-flex flex-nowrap pt-4 w-100 justify-content-center button-shadow">
                            <button class="btn btn-outline-primary fw-bold me-4 btn-back" type="button">Indietro</button>
                            <button class="btn btn-primary fw-bold btn-next" type="submit" form="rating">Avanti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
