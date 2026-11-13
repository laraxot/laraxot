@props(['data' => []])

@php
    $requiredNote = $data['required_note'] ?? 'I campi contraddistinti dal simbolo asterisco sono obbligatori';
    $steps = $data['steps'] ?? ['Informativa sulla privacy', 'Dati di segnalazione', 'Riepilogo'];
    $step = $data['step'] ?? 2;
    $placeLabel = $data['place_label'] ?? 'Cerca un luogo*';
    $typeLabel = $data['type_label'] ?? 'Tipo di disservizio**';
    $titleLabel = $data['title_field_label'] ?? 'Titolo*';
    $detailsLabel = $data['details_label'] ?? 'Dettagli**';
    $imagesLabel = $data['images_label'] ?? 'Immagini';
    $uploadLabel = $data['upload_label'] ?? 'Carica file';
    $backLabel = $data['back_label'] ?? 'Indietro';
    $nextLabel = $data['next_label'] ?? 'Avanti';
    $saveLabel = $data['save_label'] ?? 'Salva';
    $authorName = $data['author_name'] ?? 'Giulia Bianchi';
    $authorRole = $data['author_role'] ?? 'Responsabile del procedimento';
    $inefficiencyTypes = $data['inefficiency_types'] ?? [
        'Verde pubblico', 'Illuminazione pubblica', 'Strade e marciapiedi', 'Rifiuti e igiene', 'Acqua e fognature', 'Altro',
    ];
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
            <p class="title-xsmall my-5 text-sm text-[#5d7083] lg:hidden">{{ $requiredNote }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-3 mb-4 hidden lg:block">
            <div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
                <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="{{ __('pub_theme::flow_report.dati.required_information.label') }}">
                    <div class="navbar-custom" id="navbarNavProgress">
                        <div class="menu-wrapper">
                            <div class="link-list-wrapper">
                                <div class="accordion">
                                    <div class="accordion-item">
                                        <span class="accordion-header" id="accordion-title-one">
                                            <button class="accordion-button pb-10 px-3" type="button" aria-expanded="true" aria-controls="collapse-one" x-data="{ open: true }" @click="open = !open" :aria-expanded="open">
                                                {{ __('pub_theme::flow_report.dati.required_information.label') }}
                                                <svg class="icon icon-xs right h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </span>
                                        <div class="progress mt-1 h-1 w-full bg-[#d9e2e8]"><div class="it-navscroll-progressbar h-full bg-[#007a52]" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>
                                        <div id="collapse-one" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-one" x-show="open">
                                            <div class="accordion-body">
                                                <ul class="link-list mt-2 space-y-1" data-element="page-index">
                                                    <li class="nav-item"><a class="nav-link block py-1 text-[#007a52] hover:underline" href="#report-place"><span>{{ __('pub_theme::blocks_common.shared.place.label') }}</span></a></li>
                                                    <li class="nav-item"><a class="nav-link block py-1 text-[#007a52] hover:underline" href="#report-info"><span>{{ __('pub_theme::blocks_common.shared.service_issue.label') }}</span></a></li>
                                                    <li class="nav-item"><a class="nav-link block py-1 text-[#007a52] hover:underline" href="#report-author"><span>{{ __('pub_theme::blocks_common.shared.report_author.label') }}</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="col-12 col-lg-8 offset-lg-1">
            <div class="steppers-content" aria-live="polite">
                <div class="it-page-sections-container">
                    <section class="it-page-section" id="report-place">
                        <div class="cmp-card mb-40">
                            <div class="card has-bkg-grey rounded-lg border border-[#d9e2e8] bg-white p-4 p-lg-4 shadow-sm">
                                <div class="card-header mb-2 border-0 p-0">
                                    <div class="flex">
                                        <h2 class="title-xxlarge mb-1 text-2xl font-bold text-[#17324d]">{{ __('pub_theme::blocks_common.shared.place.label') }}</h2>
                                    </div>
                                    <p class="subtitle-small mb-0 text-sm text-[#5d7083]">{{ __('pub_theme::flow_report.dati.indicate_the_place_of_the_service.label') }}</p>
                                </div>
                                <div class="card-body p-0">
                                    <div class="cmp-input-autocomplete">
                                        <div class="form-group bg-white p-3 mb-0 mt-3">
                                            <label class="label-input mb-2 block text-sm font-semibold text-[#17324d]" for="autocomplete-place">{{ $placeLabel }}</label>
                                            <input type="search" class="autocomplete mt-1 w-full rounded-md border-2 border-[#5d7083] bg-white px-4 py-3 text-[#17324d] focus:border-[#007a52] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" placeholder="{{ $placeLabel }}" id="autocomplete-place" name="autocomplete-place" required data-element="place-input" x-data="{ query: '' }" x-model="query">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="it-page-section" id="report-info">
                        <div class="cmp-card mb-40">
                            <div class="card has-bkg-grey rounded-lg border border-[#d9e2e8] bg-white p-4 p-lg-4 shadow-sm">
                                <div class="card-header mb-2 border-0 p-0">
                                    <div class="flex">
                                        <h2 class="title-xxlarge mb-1 text-2xl font-bold text-[#17324d]">{{ __('pub_theme::blocks_common.shared.service_issue.label') }}</h2>
                                    </div>
                                    <p class="subtitle-small mb-0 text-sm text-[#5d7083]">{{ __('pub_theme::flow_report.dati.describe_the_service_issue_you_encountered.label') }}</p>
                                </div>
                                <div class="card-body p-0">
                                    <div class="mb-4">
                                        <label class="label-input mb-2 block text-sm font-semibold text-[#17324d]" for="inefficiency-type">{{ $typeLabel }}</label>
                                        <select class="mt-1 w-full rounded-md border-2 border-[#5d7083] bg-white px-4 py-3 text-[#17324d] focus:border-[#007a52] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" id="inefficiency-type" x-model="tipoDisservizio" data-element="inefficiency-type-select">
                                            <option value="" disabled selected>{{ __('pub_theme::flow_report.dati.select_the_type_of_service_issue.label') }}</option>
                                            @foreach($inefficiencyTypes as $type)
                                                <option value="{{ strtolower(str_replace(' ', '-', $type)) }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="label-input mb-2 block text-sm font-semibold text-[#17324d]" for="report-title">{{ $titleLabel }}</label>
                                        <input type="text" id="report-title" placeholder="{{ __('pub_theme::flow_report.dati.report_title.placeholder') }}" class="mt-1 w-full rounded-md border-2 border-[#5d7083] bg-white px-4 py-3 text-[#17324d] focus:border-[#007a52] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" x-model="titolo" data-element="report-title-input">
                                    </div>
                                    <div class="mb-4">
                                        <label class="label-input mb-2 block text-sm font-semibold text-[#17324d]" for="report-details">{{ $detailsLabel }}</label>
                                        <textarea class="mt-1 w-full rounded-md border-2 border-[#5d7083] bg-white px-4 py-3 text-[#17324d] focus:border-[#007a52] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" id="report-details" rows="4" placeholder="{{ __('pub_theme::flow_report.dati.describe_the_service_issue.placeholder') }}" x-model="dettagli" maxlength="200" data-element="report-details-input"></textarea>
                                        <p class="mt-1 text-xs text-[#5d7083]">{{ __('pub_theme::flow_report.dati.max_200_characters.label') }}</p>
                                    </div>
                                    <div>
                                        <span class="label-input mb-2 block text-sm font-semibold text-[#17324d]">{{ $imagesLabel }}</span>
                                        <div class="flex items-center gap-3 rounded-md border-2 border-dashed border-[#5d7083] bg-white p-4">
                                            <svg class="h-6 w-6 text-[#007a52]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><circle cx="8.5" cy="8.5" r="1.5" stroke-width="2"/><path d="M21 15l-5-5L5 21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            <span class="text-[#334e68]">{{ __('pub_theme::flow_report.dati.no_file_selected.label') }}</span>
                                            <button type="button" class="ml-auto inline-flex items-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-semibold text-[#007a52] shadow-sm hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" data-element="upload-file">{{ $uploadLabel }}</button>
                                        </div>
                                        <p class="mt-1 text-xs text-[#5d7083]">{{ __('pub_theme::flow_report.dati.supported_formats_jpg_png_pdf_max.message') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="it-page-section" id="report-author">
                        <div class="cmp-card mb-40">
                            <div class="card has-bkg-grey rounded-lg border border-[#d9e2e8] bg-white p-4 p-lg-4 shadow-sm">
                                <div class="card-header mb-2 border-0 p-0">
                                    <div class="flex">
                                        <h2 class="title-xxlarge mb-1 text-2xl font-bold text-[#17324d]">{{ __('pub_theme::blocks_common.shared.report_author.label') }}</h2>
                                    </div>
                                    <p class="subtitle-small mb-0 text-sm text-[#5d7083]">{{ __('pub_theme::flow_report.dati.the_author_s_details_are_pre.label') }}</p>
                                </div>
                                <div class="card-body p-0">
                                    <h3 class="title-medium mb-2 text-lg font-semibold text-[#17324d]">{{ $authorName }}</h3>
                                    <p class="text-sm text-[#5d7083]">{{ $authorRole }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="flex flex-wrap justify-between gap-3">
                        <a href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'segnalazione-01-privacy'], false) }}" class="inline-flex min-h-12 min-w-[120px] items-center justify-center rounded-md border-2 border-[#007a52] px-5 py-3 font-bold text-[#007a52] transition-colors hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad] focus:ring-offset-2" data-element="step-prev">
                            <span>{{ $backLabel }}</span>
                        </a>
                        <div class="flex gap-3">
                            <button type="button" class="inline-flex min-h-12 min-w-[120px] items-center justify-center rounded-md bg-white px-5 py-3 font-bold text-[#007a52] shadow-sm transition-colors hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" data-element="save-draft">
                                <span>{{ $saveLabel }}</span>
                            </button>
                            <a href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'segnalazione-03-riepilogo'], false) }}" class="inline-flex min-h-12 min-w-[120px] items-center justify-center rounded-md bg-[#007a52] px-5 py-3 font-bold text-white transition-colors hover:bg-[#005c3d] focus:outline-none focus:ring-4 focus:ring-[#80c8ad] focus:ring-offset-2" data-element="step-next">
                                <span>{{ $nextLabel }}</span>
                            </a>
                        </div>
                    </div>
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
