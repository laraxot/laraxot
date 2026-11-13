@props(['data' => []])

@php
    $steps = $data['steps'] ?? ['Autorizzazioni e condizioni', 'Dati di segnalazione', 'Riepilogo'];
    $step = $data['step'] ?? 3;
    $warningTitle = $data['warning_title'] ?? 'Attenzione';
    $warningText = $data['warning_text'] ?? 'Le informazioni che hai fornito hanno valore di dichiarazione. Verifica che siano corrette.';
    $indirizzo = $data['indirizzo'] ?? 'Via Solferino - 50100 Firenze (FI)';
    $tipoDisservizio = $data['tipo_disservizio'] ?? 'Danneggiamento proprietà pubblica';
    $titolo = $data['titolo'] ?? 'Panchina danneggiata';
    $dettagli = $data['dettagli'] ?? 'La seduta della panchina risulta inutilizzabile e pericolosa dato che ci sono molte schegge e parti appuntite';
    $immagini = $data['immagini'] ?? '6yhakandsahm413d8da.jpg';
    $autoreNome = $data['autore_nome'] ?? 'Giulia Bianchi';
    $codiceFiscale = $data['codice_fiscale'] ?? 'GLABNC72H25H501Y';
    $telefono = $data['telefono'] ?? '+39 331 1234567';
    $email = $data['email'] ?? 'giulia.bianchi@gmail.com';
    $backLabel = $data['back_label'] ?? 'Indietro';
    $saveLabel = $data['save_label'] ?? 'Salva Richiesta';
    $saveMobileLabel = $data['save_mobile_label'] ?? 'Salva';
    $sendLabel = $data['send_label'] ?? 'Invia';
    $successMessage = $data['success_message'] ?? 'Richiesta salvata con successo';
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
            <div class="cmp-heading pb-3 pb-lg-4">
                <h1 class="title-xxxlarge text-4xl font-bold leading-tight text-[#17324d] md:text-5xl">{{ __('pub_theme::flow_report.shared.service_issue_report.label') }}</h1>
            </div>
        </div>
        <div class="col-12">
            <div class="steppers mt-6" data-element="stepper">
                <div class="steppers-header flex items-center justify-between border-b-2 border-[#d9e2e8] pb-3">
                    <ul class="flex flex-wrap gap-6" role="list">
                        @foreach($steps as $index => $stepLabel)
                            @php $stepNum = $index + 1; $isActive = $stepNum === $step; $isConfirmed = $stepNum < $step; @endphp
                            <li class="flex items-center gap-2 {{ $isActive ? 'text-[#007a52] font-semibold' : ($isConfirmed ? 'text-[#007a52]' : 'text-[#5d7083]') }}" {{ $isActive ? 'aria-current="step"' : '' }}>
                                @if($isConfirmed)
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#007a52] text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                    <span class="text-sm">{{ $stepLabel }}</span>
                                    <span class="visually-hidden">{{ __('pub_theme::flow_report.shared.confirmed.label') }}</span>
                                @elseif($isActive)
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-[#007a52] bg-[#007a52] text-white text-xs font-bold">{{ $stepNum }}</span>
                                    <span class="text-sm">{{ $stepLabel }}</span>
                                    <span class="visually-hidden">{{ __('pub_theme::flow_report.shared.active.label') }}</span>
                                @else
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-[#d9e2e8] text-[#5d7083] text-xs font-bold">{{ $stepNum }}</span>
                                    <span class="text-sm">{{ $stepLabel }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <span class="steppers-index text-sm font-semibold text-[#5d7083]" aria-hidden="true">{{ $step }}/{{ count($steps) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="callout callout-highlight mb-4 rounded-md border-l-4 border-[#007a52] bg-[#e6f3ee] p-4 ps-3" role="alert">
                <div class="callout-title mb-2 flex items-center gap-2">
                    <svg class="icon icon-sm h-5 w-5 text-[#007a52]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="font-semibold text-[#17324d]">{{ $warningTitle }}</span>
                </div>
                <p class="text-paragraph text-base leading-7 text-[#334e68]">{{ $warningText }}</p>
            </div>

            <h2 class="title-xxlarge mb-4 mt-10 text-2xl font-bold text-[#17324d]">{{ __('pub_theme::flow_report.riepilogo.report.label') }}</h2>

            <div class="cmp-card mb-4">
                <div class="card has-bkg-grey rounded-lg border border-[#d9e2e8] bg-white shadow-sm">
                    <div class="card-header mb-2 border-0 p-0">
                        <div class="flex">
                            <h3 class="subtitle-large mb-4 text-xl font-semibold text-[#17324d]">{{ __('pub_theme::blocks_common.shared.service_issue.label') }}</h3>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="cmp-info-summary bg-white p-3 p-lg-4">
                            <div class="card rounded-md border border-[#d9e2e8]">
                                <div class="card-header mb-0 flex justify-content-end border-b border-[#d9e2e8] p-0 pb-2">
                                    <a href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'segnalazione-02-dati'], false) }}" class="text-decoration-none"><span class="text-button-sm-semi text-sm font-semibold text-[#007a52]">{{ __('pub_theme::flow_report.riepilogo.edit.label') }}</span></a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="single-line-info border-b border-[#d9e2e8] py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::flow_report.riepilogo.address.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $indirizzo }}</p>
                                        </div>
                                    </div>
                                    <div class="single-line-info border-b border-[#d9e2e8] py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::flow_report.riepilogo.type_of_service_issue.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $tipoDisservizio }}</p>
                                        </div>
                                    </div>
                                    <div class="single-line-info border-b border-[#d9e2e8] py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::flow_report.riepilogo.title.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $titolo }}</p>
                                        </div>
                                    </div>
                                    <div class="single-line-info border-b border-[#d9e2e8] py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::blocks_common.shared.details.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $dettagli }}</p>
                                        </div>
                                    </div>
                                    <div class="single-line-info py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::flow_report.riepilogo.images.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $immagini }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="title-xxlarge mb-4 mt-10 text-2xl font-bold text-[#17324d]">{{ __('pub_theme::flow_report.riepilogo.general_data.label') }}</h2>

            <div class="cmp-card mb-4">
                <div class="card has-bkg-grey rounded-lg border border-[#d9e2e8] bg-white shadow-sm">
                    <div class="card-header mb-2 border-0 p-0">
                        <div class="flex">
                            <h3 class="subtitle-large mb-4 text-xl font-semibold text-[#17324d]">{{ __('pub_theme::blocks_common.shared.report_author.label') }}</h3>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="cmp-info-summary mb-4 bg-white p-3 p-lg-4">
                            <div class="card rounded-md border border-[#d9e2e8]">
                                <div class="card-header mb-0 flex justify-content-between border-b border-[#d9e2e8] p-0">
                                    <h4 class="title-large-semi-bold mb-3 text-lg font-semibold text-[#17324d]">{{ $autoreNome }}</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="single-line-info py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::blocks_common.shared.tax_code.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $codiceFiscale }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cmp-info-summary bg-white p-3 p-lg-4">
                            <div class="card rounded-md border border-[#d9e2e8]">
                                <div class="card-header mb-0 flex justify-content-between border-b border-[#d9e2e8] p-0">
                                    <h4 class="title-large-semi-bold mb-3 text-lg font-semibold text-[#17324d]">{{ __('pub_theme::blocks_common.shared.contacts.label') }}</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="single-line-info border-b border-[#d9e2e8] py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::blocks_common.shared.phone.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $telefono }}</p>
                                        </div>
                                    </div>
                                    <div class="single-line-info py-3">
                                        <div class="text-paragraph-small text-sm text-[#5d7083]">{{ __('pub_theme::blocks_common.shared.email.label') }}</div>
                                        <div class="border-light">
                                            <p class="data-text text-[#17324d]">{{ $email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cmp-nav-steps mt-8">
                <nav class="steppers-nav flex flex-wrap items-center justify-between gap-3" aria-label="{{ __('pub_theme::flow_report.riepilogo.step.label') }}">
                    <a href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'segnalazione-02-dati'], false) }}" class="btn btn-sm steppers-btn-prev inline-flex items-center gap-1 p-0 text-[#007a52] hover:underline" data-element="step-prev">
                        <svg class="icon icon-primary icon-sm h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="text-button-sm text-sm font-semibold text-[#007a52]">{{ $backLabel }}</span>
                    </a>
                    <div class="flex gap-3">
                        <button type="button" class="btn btn-outline-primary bg-white btn-sm steppers-btn-save hidden rounded-md border-2 border-[#007a52] px-4 py-2 text-sm font-semibold text-[#007a52] transition-colors hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad] lg:block saveBtn" data-element="save-request">
                            <span class="text-button-sm">{{ $saveLabel }}</span>
                        </button>
                        <button type="button" class="btn btn-outline-primary bg-white btn-sm steppers-btn-save rounded-md border-2 border-[#007a52] px-4 py-2 text-sm font-semibold text-[#007a52] transition-colors hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad] lg:hidden saveBtn" data-element="save-request">
                            <span class="text-button-sm">{{ $saveMobileLabel }}</span>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm steppers-btn-confirm send rounded-md bg-[#007a52] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#005c3d] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" data-bs-toggle="modal" data-bs-target="#modal-terms" data-element="send" @click="$dispatch('open-terms')">
                            <span class="text-button-sm">{{ $sendLabel }}</span>
                        </button>
                    </div>
                </nav>
                <div id="alert-message" class="alert alert-success cmp-disclaimer mt-4 hidden rounded-md bg-[#e6f3ee] p-4 text-[#007a52]" role="alert">
                    <span class="d-inline-block text-uppercase cmp-disclaimer__message text-sm font-semibold">{{ $successMessage }}</span>
                </div>
            </div>
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
                                <li><a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'domande-frequenti'], false) }}"><svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3" stroke-width="2" stroke-linecap="round"/><line x1="12" y1="17" x2="12.01" y2="17" stroke-width="2" stroke-linecap="round"/></svg><span>{{ __('pub_theme::blocks_common.shared.read_the_frequently_asked_questions.label') }}</span></a></li>
                                <li><a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'assistenza-01-dati'], false) }}" data-element="contacts"><svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" stroke-width="2"/><path d="M3 7l9 6 9-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg><span>{{ __('pub_theme::blocks_common.shared.request_assistance.label') }}</span></a></li>
                                <li><a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="tel:050505"><svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5a2 2 0 012-2h2l2 5-2 2a12 12 0 006 6l2-2 5 2v2a2 2 0 01-2 2A17 17 0 013 5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg><span>{{ __('pub_theme::blocks_common.shared.call_the_toll_free_number_05.label') }}</span></a></li>
                                <li><a class="list-item flex items-center gap-3 text-[#007a52] hover:text-[#005c3d]" href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'appuntamento-01-ufficio'], false) }}" data-element="appointment-booking"><svg class="icon icon-primary icon-sm h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2" stroke-width="2"/><line x1="3" y1="9" x2="21" y2="9" stroke-width="2"/><line x1="8" y1="2" x2="8" y2="6" stroke-width="2" stroke-linecap="round"/><line x1="16" y1="2" x2="16" y2="6" stroke-width="2" stroke-linecap="round"/></svg><span>{{ __('pub_theme::blocks_common.shared.book_an_appointment.label') }}</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
