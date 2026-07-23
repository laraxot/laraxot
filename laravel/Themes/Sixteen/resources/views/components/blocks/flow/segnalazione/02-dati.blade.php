@props(['data' => []])

@php
    $title = $data['title'] ?? __('fixcity::segnalazione.dati.title.label');
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 pb-40 pb-lg-80">
            <h2 class="title-xxlarge mb-4">{{ $title }}</h2>

            <!-- Luogo -->
            <div class="card-wrapper bg-grey-card rounded h-auto mb-40">
                <div class="card card-teaser bg-grey-card rounded shadow-sm p-3 p-lg-4">
                    <div class="card-body">
                        <h3 class="title-large mb-3">{{ __('fixcity::segnalazione.dati.place.label') }}</h3>
                        <div class="input-wrapper">
                            <label for="report-place" class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.dati.place.label') }}*</label>
                            <input type="text" class="form-control" id="report-place"
                                   placeholder="{{ __('fixcity::segnalazione.dati.place.placeholder') }}"
                                   x-model="luogo" data-element="place-input">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disservizio -->
            <div class="card-wrapper bg-grey-card rounded h-auto mb-40">
                <div class="card card-teaser bg-grey-card rounded shadow-sm p-3 p-lg-4">
                    <div class="card-body">
                        <h3 class="title-large mb-3">{{ __('fixcity::segnalazione.dati.details.label') }}</h3>

                        <div class="select-wrapper mb-3">
                            <label for="inefficiency-type" class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.dati.type.label') }}</label>
                            <select class="form-select" id="inefficiency-type" x-model="tipoDisservizio" data-element="inefficiency-type-select">
                                <option value="" disabled selected>{{ __('fixcity::segnalazione.dati.type.select') }}</option>
                                <option value="verde">{{ __('fixcity::segnalazione.dati.types.verde') }}</option>
                                <option value="illuminazione">{{ __('fixcity::segnalazione.dati.types.illuminazione') }}</option>
                                <option value="strade">{{ __('fixcity::segnalazione.dati.types.strade') }}</option>
                                <option value="rifiuti">{{ __('fixcity::segnalazione.dati.types.rifiuti') }}</option>
                                <option value="altro">{{ __('fixcity::segnalazione.dati.types.altro') }}</option>
                            </select>
                        </div>

                        <div class="input-wrapper mb-3">
                            <label for="report-title" class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.dati.title.label') }}</label>
                            <input type="text" class="form-control" id="report-title"
                                   placeholder="{{ __('fixcity::segnalazione.dati.title.placeholder') }}"
                                   x-model="titolo" data-element="report-title-input">
                        </div>

                        <div class="text-area-wrapper mb-3">
                            <label for="report-details" class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.dati.description.label') }}</label>
                            <textarea class="form-control" id="report-details" rows="4"
                                      placeholder="{{ __('fixcity::segnalazione.dati.description.placeholder') }}"
                                      x-model="dettagli" maxlength="200"
                                      data-element="report-details-input"></textarea>
                            <p class="title-xsmall u-grey-dark mt-1 mb-0">{{ __('fixcity::segnalazione.max_chars.label', ['max' => 200]) }}</p>
                        </div>

                        <div class="upload-wrapper mt-4">
                            <label class="title-xsmall-bold u-grey-dark mb-2 d-block">{{ __('fixcity::segnalazione.dati.images.label') }}</label>
                            <div class="d-flex align-items-center gap-3 p-3 bg-white rounded border">
                                <svg class="icon icon-primary icon-md" aria-hidden="true">
                                    <use href="{{ $sprite }}#it-upload"></use>
                                </svg>
                                <span class="text-paragraph-small">{{ __('fixcity::segnalazione.dati.images.no_file') }}</span>
                            </div>
                            <p class="title-xsmall u-grey-dark mt-1 mb-0">{{ __('fixcity::segnalazione.dati.images.hint') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="d-flex justify-content-between mt-4">
                <a href="/it/tests/segnalazione-01-privacy" class="btn btn-outline-primary mobile-full" data-element="step-prev">
                    <span>{{ __('fixcity::segnalazione.buttons.back.label') }}</span>
                </a>
                <a href="/it/tests/segnalazione-03-riepilogo" class="btn btn-primary mobile-full" data-element="step-next">
                    <span>{{ __('fixcity::segnalazione.buttons.next.label') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                @include('components.blocks.contacts.faq')
            </div>
        </div>
    </div>
</div>
