@props(['contacts' => []])

@php
    $sprite ??= '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
    $phone = trim((string) ($contacts['phone'] ?? '05 0505'));
    $phoneUrl = (string) ($contacts['phone_url'] ?? '#');
    $faqUrl = (string) ($contacts['faq'] ?? '#');
    $assistenzaUrl = (string) ($contacts['assistenza'] ?? '#');
    $appointmentUrl = (string) ($contacts['appointment'] ?? '#');
@endphp

{{-- 🏛 Contacts block — Design Comuni reference parity
     Single reusable component. Replace all copies of:
       <div class="bg-grey-card shadow-contacts">...</div>
     with:
       <x-pub_theme::cmp-contacts :contacts="$contacts" />

     Docs: Themes/Sixteen/docs/design-comuni/reusable-blocks.md
--}}
<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ __('fixcity::segnalazione.contact.heading.label') }}</h2>
                            <ul class="contact-list p-0">
                                <li><a class="list-item" href="{{ $faqUrl }}">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-help-circle"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.faq.label') }}</span>
                                </a></li>
                                <li><a class="list-item" href="{{ $assistenzaUrl }}" data-element="contacts">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-mail"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.assistance.label') }}</span>
                                </a></li>
                                <li><a class="list-item" href="{{ $phoneUrl }}">
                                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-hearing"></use></svg>
                                    <span>{{ __('fixcity::segnalazione.contact.phone.label', ['phone' => $phone]) }}</span>
                                </a></li>
                                <li><a class="list-item" href="{{ $appointmentUrl }}" data-element="appointment-booking">
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
