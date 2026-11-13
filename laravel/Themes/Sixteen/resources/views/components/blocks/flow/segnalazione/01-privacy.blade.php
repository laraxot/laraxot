@props(['data' => []])

@php
    $description = $data['description'] ?? 'Il Comune di Firenze gestisce i dati personali forniti e liberamente comunicati sulla base dell\u2019articolo 13 del Regolamento (UE) 2016/679 General data protection regulation (Gdpr) e degli articoli 13 e successive modifiche e integrazione del decreto legislativo (di seguito d.lgs) 267/2000 (Testo unico enti locali).';
    $privacyLinkLabel = $data['privacy_link_label'] ?? 'informativa sulla privacy';
    $acceptLabel = $data['accept_label'] ?? 'Ho letto e compreso l\u2019informativa sulla privacy';
    $nextLabel = $data['next_label'] ?? 'Avanti';
    $nextUrl = $data['next_url'] ?? '/it/tests/segnalazione-02-dati';
    $step = $data['step'] ?? 1;
    $steps = $data['steps'] ?? ['Autorizzazioni e condizioni', 'Dati di segnalazione', 'Riepilogo'];
@endphp

<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <nav class="cmp-breadcrumbs mb-6" aria-label="{{ __('pub_theme::blocks_common.shared.breadcrumb.label') }}">
                <ol class="flex flex-wrap items-center gap-2 text-sm text-[#5d7083]">
                    <li><a href="{{ route('container0.index', ['container0' => 'tests'], false) }}" class="text-[#007a52] underline underline-offset-2 hover:text-[#005c3d]">{{ __('pub_theme::blocks_common.shared.home.label') }}</a><span class="mx-1" aria-hidden="true">/</span></li>
                    <li><a href="{{ route('container0.index', ['container0' => 'tests'], false) }}" class="text-[#007a52] underline underline-offset-2 hover:text-[#005c3d]">{{ __('pub_theme::blocks_common.shared.services.label') }}</a><span class="mx-1" aria-hidden="true">/</span></li>
                    <li class="text-[#17324d]" aria-current="page">{{ __('pub_theme::flow_report.shared.service_issue_report.label') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h1 class="title-xxxlarge mb-4 text-4xl font-bold leading-tight text-[#17324d] md:text-5xl">{{ __('pub_theme::flow_report.shared.service_issue_report.label') }}</h1>
        </div>
        <div class="col-12">
            <div class="steppers mt-6" data-element="stepper">
                <div class="steppers-header flex items-center justify-between border-b-2 border-[#d9e2e8] pb-3">
                    <ul class="flex flex-wrap gap-6" role="list">
                        @foreach($steps as $index => $stepLabel)
                            @php $isActive = ($index + 1) === $step; @endphp
                            <li class="flex items-center gap-2 {{ $isActive ? 'text-[#007a52] font-semibold' : 'text-[#5d7083]' }}" {{ $isActive ? 'aria-current="step"' : '' }}>
                                <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 {{ $isActive ? 'border-[#007a52] bg-[#007a52] text-white' : 'border-[#d9e2e8] text-[#5d7083]' }} text-xs font-bold">{{ $index + 1 }}</span>
                                <span class="text-sm">{{ $stepLabel }}</span>
                                @if($isActive)<span class="visually-hidden">{{ __('pub_theme::flow_report.shared.active.label') }}</span>@endif
                            </li>
                        @endforeach
                    </ul>
                    <span class="steppers-index text-sm font-semibold text-[#5d7083]" aria-hidden="true">{{ $step }}/{{ count($steps) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 pb-40 pb-lg-80">
            <p class="text-paragraph mb-lg-4 text-base leading-7 text-[#334e68]">
                {{ $description }}
            </p>
            <p class="text-paragraph mb-0 text-base leading-7 text-[#334e68]">
                {{ __('pub_theme::flow_report.privacy.for_details_on_how_personal_data.message') }}
                <a href="#" class="t-primary font-semibold text-[#007a52] underline underline-offset-2">{{ $privacyLinkLabel }}.</a>
            </p>

            <div class="form-check mt-4 mb-3 mt-md-40 mb-lg-40">
                <div class="checkbox-body flex items-start gap-3">
                    <input type="checkbox" id="privacy" name="privacy-field" value="privacy-field"
                           x-data="{}" data-element="privacy-consent"
                           class="mt-1 h-5 w-5 cursor-pointer rounded border-2 border-[#5d7083] text-[#007a52] focus:ring-2 focus:ring-[#80c8ad]">
                    <label class="title-small-semi-bold pt-1 cursor-pointer font-semibold text-[#17324d]" for="privacy">{{ $acceptLabel }}</label>
                </div>
            </div>
            <a href="{{ $nextUrl }}"
               class="btn btn-primary mobile-full inline-flex min-h-12 min-w-[120px] items-center justify-center rounded-md bg-[#007a52] px-5 py-3 font-bold text-white transition-colors hover:bg-[#005c3d] focus:outline-none focus:ring-4 focus:ring-[#80c8ad] focus:ring-offset-2"
               x-data="{ checked: false }"
               :class="{ 'opacity-50 cursor-not-allowed': !checked }"
               :aria-disabled="!checked"
               @click="if (!checked) { $event.preventDefault(); }"
               data-element="step-next">
                <span class="">{{ $nextLabel }}</span>
            </a>
        </div>
    </div>
</div>

<div class="bg-[#f5f7f9] py-12 shadow-contacts">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                <div class="cmp-contacts">
                    <div class="card w-100 rounded-lg border border-[#d9e2e8] bg-white shadow-sm">
                        <div class="card-body p-6">
                            <h2 class="title-medium-2-semi-bold text-xl font-semibold text-[#17324d]">{{ __('pub_theme::flow_report.shared.contact_the_municipality.label') }}</h2>
                            <ul class="contact-list mt-4 space-y-3 p-0">
                                <li>
                                    <a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'domande-frequenti'], false) }}">
                                        <svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3" stroke-width="2" stroke-linecap="round"/><line x1="12" y1="17" x2="12.01" y2="17" stroke-width="2" stroke-linecap="round"/></svg>
                                        <span>{{ __('pub_theme::blocks_common.shared.read_the_frequently_asked_questions.label') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'assistenza-01-dati'], false) }}" data-element="contacts">
                                        <svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" stroke-width="2"/><path d="M3 7l9 6 9-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span>{{ __('pub_theme::blocks_common.shared.request_assistance.label') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="tel:050505">
                                        <svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5a2 2 0 012-2h2l2 5-2 2a12 12 0 006 6l2-2 5 2v2a2 2 0 01-2 2A17 17 0 013 5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span>{{ __('pub_theme::blocks_common.shared.call_the_toll_free_number_05.label') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'appuntamento-01-ufficio'], false) }}" data-element="appointment-booking">
                                        <svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2" stroke-width="2"/><line x1="3" y1="9" x2="21" y2="9" stroke-width="2"/><line x1="8" y1="2" x2="8" y2="6" stroke-width="2" stroke-linecap="round"/><line x1="16" y1="2" x2="16" y2="6" stroke-width="2" stroke-linecap="round"/></svg>
                                        <span>{{ __('pub_theme::blocks_common.shared.book_an_appointment.label') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
