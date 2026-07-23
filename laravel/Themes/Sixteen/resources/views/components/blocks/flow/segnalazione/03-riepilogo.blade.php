@props(['data' => []])

@php
    $title = $data['title'] ?? __('fixcity::segnalazione.riepilogo.title.label');
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 pb-40 pb-lg-80">
            <h2 class="title-xxlarge mb-4">{{ $title }}</h2>

            <!-- Callout -->
            <div class="callout warning mb-40">
                <div class="callout-content d-flex gap-3">
                    <svg class="icon icon-md flex-shrink-0" aria-hidden="true">
                        <use href="{{ $sprite }}#it-warning"></use>
                    </svg>
                    <div>
                        <h3 class="title-medium-semi-bold mb-1">{{ __('fixcity::segnalazione.riepilogo.warning.title') }}</h3>
                        <p class="text-paragraph">{{ __('fixcity::segnalazione.riepilogo.warning.text') }}</p>
                    </div>
                </div>
            </div>

            <!-- Disservizio summary -->
            <div class="card-wrapper bg-grey-card rounded h-auto mb-40">
                <div class="card card-teaser bg-grey-card rounded shadow-sm p-3 p-lg-4">
                    <div class="card-body">
                        <h3 class="title-medium-semi-bold mb-3">{{ __('fixcity::segnalazione.riepilogo.disservizio.title') }}</h3>
                        <dl class="summary-list">
                            <div class="summary-item">
                                <dt class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.riepilogo.disservizio.type.label') }}</dt>
                                <dd class="text-paragraph" x-text="tipoDisservizio || 'Verde pubblico'"></dd>
                            </div>
                            <div class="summary-item">
                                <dt class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.riepilogo.disservizio.title.label') }}</dt>
                                <dd class="text-paragraph" x-text="titolo || 'Buca in via Solferino'"></dd>
                            </div>
                            <div class="summary-item">
                                <dt class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.riepilogo.disservizio.description.label') }}</dt>
                                <dd class="text-paragraph" x-text="dettagli || 'Segnalazione di una buca...'"></dd>
                            </div>
                            <div class="summary-item">
                                <dt class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.riepilogo.disservizio.place.label') }}</dt>
                                <dd class="text-paragraph" x-text="luogo || 'Via Solferino, 15'"></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Contacts summary -->
            <div class="card-wrapper bg-grey-card rounded h-auto mb-40">
                <div class="card card-teaser bg-grey-card rounded shadow-sm p-3 p-lg-4">
                    <div class="card-body">
                        <h3 class="title-medium-semi-bold mb-3">{{ __('fixcity::segnalazione.riepilogo.contacts.title') }}</h3>
                        <dl class="summary-list">
                            <div class="summary-item">
                                <dt class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.riepilogo.contacts.email.label') }}</dt>
                                <dd class="text-paragraph" x-text="email || 'mario.rossi@email.it'"></dd>
                            </div>
                            <div class="summary-item">
                                <dt class="title-xsmall-bold u-grey-dark">{{ __('fixcity::segnalazione.riepilogo.contacts.phone.label') }}</dt>
                                <dd class="text-paragraph" x-text="telefono || '333 1234567'"></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Terms checkbox -->
            <div class="form-check mt-4 mb-3" x-data="{ accepted: false }">
                <div class="checkbox-body d-flex align-items-center">
                    <input type="checkbox" id="accetto-termini" x-model="accepted" data-element="terms-accept">
                    <label class="title-small-semi-bold pt-1" for="accetto-termini">
                        {{ __('fixcity::segnalazione.riepilogo.terms.accept.label') }}
                        <a href="#" class="t-primary">{{ __('fixcity::segnalazione.riepilogo.terms.link.label') }}</a>
                    </label>
                </div>
            </div>

            <!-- Navigation -->
            <div class="d-flex justify-content-between mt-4">
                <a href="/it/tests/segnalazione-02-dati" class="btn btn-outline-primary mobile-full" data-element="step-prev">
                    <span>{{ __('fixcity::segnalazione.buttons.back.label') }}</span>
                </a>
                <a href="/it/tests/segnalazione-04-conferma" class="btn btn-primary mobile-full" data-element="step-next">
                    <span>{{ __('fixcity::segnalazione.riepilogo.submit.label') }}</span>
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
