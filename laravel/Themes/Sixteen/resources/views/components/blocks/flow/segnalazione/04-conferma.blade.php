@props(['data' => []])

@php
    $steps = $data['steps'] ?? ['Autorizzazioni e condizioni', 'Dati di segnalazione', 'Riepilogo', 'Conferma'];
    $step = $data['step'] ?? 4;
    $title = $data['title'] ?? 'Segnalazione inviata';
    $receiptCode = $data['receipt_code'] ?? null;
    $summaryText = $data['summary_text'] ?? 'Grazie, abbiamo ricevuto la tua segnalazione';
    $visibilityText = $data['visibility_text'] ?? 'Sarà visibile sulla lista di tutte segnalazioni una volta presa in carico dall\u2019amministrazione.';
    $emailIntro = $data['email_intro'] ?? 'Abbiamo inviato il riepilogo all\u2019email:';
    $email = $data['email'] ?? null;
    $receiptLabel = $data['receipt_label'] ?? 'Scarica la ricevuta (PDF 100KB)';
    $reservedText = $data['reserved_text'] ?? 'Consulta la richiesta';
    $reservedSuffix = $data['reserved_suffix'] ?? 'nella tua area riservata';
    $relatedTitle = $data['related_title'] ?? 'Servizi correlati';
    $relatedServices = $data['related_services'] ?? [];
    $ratingTitle = $data['rating_title'] ?? 'Quanto è stato facile usare questo servizio?';
    $positiveTitle = $data['positive_title'] ?? 'Quali sono stati gli aspetti che hai preferito?';
    $negativeTitle = $data['negative_title'] ?? 'Dove hai incontrato le maggiori difficoltà?';
    $detailsTitle = $data['details_title'] ?? 'Vuoi aggiungere altri dettagli?';
    $successMessage = $data['success_message'] ?? 'Grazie, il tuo parere ci aiuterà a migliorare il servizio!';
    $positiveOptions = $data['positive_options'] ?? [
        'Le indicazioni erano chiare', 'Le indicazioni erano complete', 'Capivo sempre che stavo procedendo correttamente', 'Non ho avuto problemi tecnici', 'Altro',
    ];
    $negativeOptions = $data['negative_options'] ?? [
        'A volte le indicazioni non erano chiare', 'A volte le indicazioni non erano complete', 'A volte non capivo se stavo procedendo correttamente', 'Ho avuto problemi tecnici', 'Altro',
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
            <div class="cmp-heading p-0">
                <div class="categoryicon-top flex items-center gap-2">
                    <svg class="icon icon-success mr-10 h-10 w-10 text-[#007a52]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke-width="2"/>
                        <path d="M8.5 12.5l2.5 2.5 4.5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h1 class="title-xxxlarge text-4xl font-bold leading-tight text-[#17324d] md:text-5xl">{{ $title }}</h1>
                </div>
                <p class="subtitle-small mt-4 text-base leading-7 text-[#334e68]">{{ $summaryText }} <strong>{{ $receiptCode }}.</strong></p>
                <p class="subtitle-small text-base leading-7 text-[#334e68]">{{ $visibilityText }}</p>
                <p class="subtitle-small pt-3 pt-lg-4 text-base leading-7 text-[#334e68]">{{ $emailIntro }}<br><strong>{{ $email }}</strong></p>

                <div class="mt-6">
                    <button type="button" class="btn btn-outline-primary bg-white fw-bold inline-flex min-h-12 items-center gap-3 rounded-md border-2 border-[#007a52] px-5 py-3 font-bold text-[#007a52] transition-colors hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad] focus:ring-offset-2" data-element="download-receipt">
                        <span class="rounded-icon flex h-8 w-8 items-center justify-center rounded-full bg-[#007a52] text-white">
                            <svg class="icon icon-primary icon-sm h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="">{{ $receiptLabel }}</span>
                    </button>
                </div>
            </div>
            <p class="mt-3">
                <a href="{{ route('container0.view', ['container0' => 'tests', 'slug0' => 'segnalazione-area-personale'], false) }}" class="t-primary text-paragraph font-semibold text-[#007a52] underline underline-offset-2">{{ $reservedText }}</a>
                <span class="text-paragraph text-[#334e68]"> {{ $reservedSuffix }}</span>
            </p>
        </div>
    </div>

    <hr class="d-none d-lg-block mt-10 mb-0 border-[#d9e2e8]">
    <div class="row justify-content-center mb-3 mb-md-5">
        <div class="col-12 col-lg-10">
            <div class="cmp-icon-list">
                <h2 class="title-xxlarge mt-10 mb-2 mb-lg-4 text-2xl font-bold text-[#17324d]" id="related-service">{{ $relatedTitle }}</h2>
                <div class="link-list-wrapper">
                    <ul class="link-list space-y-4">
                        @foreach($relatedServices as $service)
                            <li class="shadow mb-4">
                                <a class="list-item icon-left t-primary title-small-semi-bold flex items-center gap-3 rounded-md bg-white p-4 text-[#007a52] hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" href="{{ $service['url'] ?? '#' }}">
                                    <span class="list-item-title-icon-wrapper flex items-center gap-3">
                                        <svg class="icon icon-sm align-self-start icon-color h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2" stroke-width="2"/><line x1="3" y1="9" x2="21" y2="9" stroke-width="2"/><line x1="8" y1="2" x2="8" y2="6" stroke-width="2" stroke-linecap="round"/><line x1="16" y1="2" x2="16" y2="6" stroke-width="2" stroke-linecap="round"/></svg>
                                        <span class="list-item-title title-small-semi-bold font-semibold">{{ $service['label'] ?? '' }}</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-[#007a52]">
    <div class="container">
        <div class="row d-flex justify-content-center bg-[#007a52]">
            <div class="col-12 col-lg-6 p-lg-0 px-4">
                <div class="cmp-rating py-10 lg:py-20" id="rating" x-data="{ step: 1, rating: 0, hover: 0, feedbackType: '', selectedOption: '', textFeedback: '' }">
                    <div class="card shadow card-wrapper rounded-lg bg-white p-6" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0 p-0">
                                <h2 class="title-medium-2-semi-bold mb-0 text-xl font-semibold text-[#17324d]" data-element="feedback-title">{{ $ratingTitle }}</h2>
                            </div>
                            <div class="card-body p-0 pt-4">
                                <template x-if="step === 1">
                                    <div>
                                        <fieldset class="rating">
                                            <legend class="visually-hidden">{{ __('pub_theme::blocks_common.shared.rate_the_page_from_1_to.label') }}</legend>
                                            <div class="flex gap-1" role="radiogroup" aria-label="{{ __('pub_theme::flow_report.conferma.rate_the_service.label') }}">
                                                <template x-for="star in [5,4,3,2,1]" :key="star">
                                                    <div>
                                                        <input type="radio" :id="'star' + star + 'a'" name="ratingA" :value="star" x-model="rating" class="hidden">
                                                        <label class="full rating-star cursor-pointer text-2xl" :for="'star' + star + 'a'" :data-element="'feedback-rate-' + star"
                                                            :class="rating >= star || hover >= star ? 'text-yellow-400' : 'text-slate-300/30'"
                                                            x-on:click="rating = star; feedbackType = star >= 4 ? 'positive' : 'negative'; step = 2"
                                                            x-on:mouseenter="hover = star" x-on:mouseleave="hover = 0">
                                                            <svg class="icon icon-sm h-6 w-6" role="img" :aria-labelledby="'star' + star + '-label'" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" :class="rating >= star || hover >= star ? 'fill-yellow-400 text-yellow-400' : 'fill-slate-300/30 text-slate-300/30'">
                                                                <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"/>
                                                                <path fill="none" d="M0 0h24v24H0z"/>
                                                            </svg>
                                                            <span class="visually-hidden" :id="'star' + star + '-label'" x-text="{{ \Illuminate\Support\Js::from(collect(range(1, 5))->mapWithKeys(fn (int $n): array => [$n => __('pub_theme::navigation.homepage.rate_'.$n)])) }}[star]"></span>
                                                        </label>
                                                    </div>
                                                </template>
                                            </div>
                                        </fieldset>
                                    </div>
                                </template>

                                <template x-if="step === 2">
                                    <div>
                                        <fieldset class="fieldset-rating-one" data-element="feedback-rating-positive" x-show="feedbackType === 'positive'">
                                            <legend class="sr-only">{{ $positiveTitle }}</legend>
                                            <h3 class="step-title text-lg font-bold text-[#17324d]">{{ $positiveTitle }}</h3>
                                            <div class="mt-4 space-y-2">
                                                <template x-if="feedbackType === 'positive'">
                                                    <div>
                                                        @foreach($positiveOptions as $i => $opt)
                                                            @php $optVal = $i + 1; @endphp
                                                            <div class="radio-body border-b border-[#d9e2e8] pb-3 last:border-0">
                                                                <input name="rating1" type="radio" id="radio-{{ $optVal }}" value="{{ $optVal }}" x-model="selectedOption" class="h-5 w-5 border-2 border-[#5d7083] text-[#007a52] focus:ring-2 focus:ring-[#80c8ad]">
                                                                <label for="radio-{{ $optVal }}" class="ml-2 cursor-pointer font-semibold text-[#17324d]">{{ $opt }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </template>
                                            </div>
                                        </fieldset>
                                        <fieldset class="fieldset-rating-one mt-4" data-element="feedback-rating-negative" x-show="feedbackType === 'negative'">
                                            <legend class="sr-only">{{ $negativeTitle }}</legend>
                                            <h3 class="step-title text-lg font-bold text-[#17324d]">{{ $negativeTitle }}</h3>
                                            <div class="mt-4 space-y-2">
                                                <template x-if="feedbackType === 'negative'">
                                                    <div>
                                                        @foreach($negativeOptions as $i => $opt)
                                                            @php $optVal = $i + 1; @endphp
                                                            <div class="radio-body border-b border-[#d9e2e8] pb-3 last:border-0">
                                                                <input name="rating2" type="radio" id="radio-n-{{ $optVal }}" value="{{ $optVal }}" x-model="selectedOption" class="h-5 w-5 border-2 border-[#5d7083] text-[#007a52] focus:ring-2 focus:ring-[#80c8ad]">
                                                                <label for="radio-n-{{ $optVal }}" class="ml-2 cursor-pointer font-semibold text-[#17324d]">{{ $opt }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </template>
                                            </div>
                                        </fieldset>
                                        <template x-if="selectedOption === '5'">
                                            <div class="cmp-steps-rating mt-4">
                                                <div class="cmp-steps-rating__body">
                                                    <div class="form-group">
                                                        <label for="rating-step3-text" class="mb-2 block text-sm font-bold text-[#17324d]">{{ $detailsTitle }}</label>
                                                        <textarea id="rating-step3-text" class="w-full rounded-md border-2 border-[#5d7083] bg-white px-4 py-3 text-[#17324d] focus:border-[#007a52] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" rows="3" maxlength="200" x-model="textFeedback" placeholder="{{ __('pub_theme::flow_report.conferma.enter_a_maximum_of_200_characters.placeholder') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <div class="mt-6 flex justify-between gap-3">
                                            <button type="button" class="inline-flex min-h-12 items-center justify-center rounded-md border-2 border-[#007a52] px-5 py-3 font-bold text-[#007a52] transition-colors hover:bg-[#e6f3ee] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" x-on:click="step = 1; rating = 0; selectedOption = ''; textFeedback = ''">{{ __('pub_theme::blocks_common.shared.back.label') }}</button>
                                            <button type="button" class="inline-flex min-h-12 items-center justify-center rounded-md bg-[#007a52] px-5 py-3 font-bold text-white transition-colors hover:bg-[#005c3d] focus:outline-none focus:ring-4 focus:ring-[#80c8ad]" x-on:click="step = 3" :class="!selectedOption ? 'opacity-50 cursor-not-allowed' : ''" :disabled="!selectedOption">{{ __('pub_theme::blocks_common.shared.next.label') }}</button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="step === 3">
                                    <p class="mt-6 font-semibold text-[#007a52]">{{ $successMessage }}</p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-[#f5f7f9] py-12">
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
