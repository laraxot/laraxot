@php
    /**
     * Contacts Card - Design Comuni
     *
     * Blocco riutilizzabile per la sezione contatti nelle pagine Design Comuni.
     * Usato in: segnalazione-crea, segnalazione-01-privacy, segnalazione-02-dati, ecc.
     *
     * Props attesi:
     * - $contacts: array con chiavi 'faq', 'assistenza', 'phone', 'phone_url', 'appointment'
     * - $sprite: string percorso SVG sprites (default: Design Comuni path)
     */
    $contacts ??= [];
    $sprite ??= '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
    $phoneLabel = trim((string) ($contacts['phone'] ?? '05 0505'));
@endphp

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ __('fixcity::segnalazione.contact.heading.label') }}</h2>
                            <ul class="contact-list p-0">
                                <li><a class="list-item" href="{{ $contacts['faq'] ?? '#' }}">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-help-circle"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.faq.label') }}</span>
                                </a></li>
                                <li><a class="list-item" href="{{ $contacts['assistenza'] ?? '#' }}" data-element="contacts">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-mail"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.assistance.label') }}</span>
                                </a></li>
                                <li><a class="list-item" href="{{ $contacts['phone_url'] ?? '#' }}">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-hearing"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.phone.label', ['phone' => $phoneLabel]) }}</span>
                                </a></li>
                                <li><a class="list-item" href="{{ $contacts['appointment'] ?? '#' }}" data-element="appointment-booking">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-calendar"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.appointment.label') }}</span>
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
