{{--
    Contacts Card — Reusable Design Comuni Block
    Reference: cmp-contacts in Bootstrap Italia Comuni
    Usage: <x-bootstrap-italia.contacts-card :contacts="$contacts" />
--}}
@props(['contacts' => []])

@php
    $phone = trim((string) ($contacts['phone'] ?? '05 0505'));
    $faqUrl = $contacts['faq'] ?? '#';
    $assistenzaUrl = $contacts['assistenza'] ?? '#';
    $phoneUrl = $contacts['phone_url'] ?? ($phone !== '' ? 'tel:'.$phone : '#');
    $appointmentUrl = $contacts['appointment'] ?? '#';
    $heading = $contacts['heading'] ?? __('fixcity::segnalazione.contact.heading.label');
    $showFaq = ($contacts['show_faq'] ?? true);
    $showAssistenza = ($contacts['show_assistenza'] ?? true);
    $showPhone = ($contacts['show_phone'] ?? true);
    $showAppointment = ($contacts['show_appointment'] ?? true);
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ $heading }}</h2>
                            <ul class="contact-list p-0">
                                @if($showFaq)
                                <li><a class="list-item" href="{{ $faqUrl }}">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-help-circle"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.faq.label') }}</span>
                                </a></li>
                                @endif
                                @if($showAssistenza)
                                <li><a class="list-item" href="{{ $assistenzaUrl }}" data-element="contacts">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-mail"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.assistance.label') }}</span>
                                </a></li>
                                @endif
                                @if($showPhone)
                                <li><a class="list-item" href="{{ $phoneUrl }}">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-hearing"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.phone.label', ['phone' => $phone]) }}</span>
                                </a></li>
                                @endif
                                @if($showAppointment)
                                <li><a class="list-item" href="{{ $appointmentUrl }}" data-element="appointment-booking">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-calendar"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.appointment.label') }}</span>
                                </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
