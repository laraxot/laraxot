{{--
  /it/domande-frequenti — corpo della pagina, non il chrome.
  Grammatica Design Comuni (liste e schede): breadcrumb, titolo, occhiello,
  blocco proprio della pagina, poi valutazione e contatti.
  Qui il blocco proprio è ricerca + accordion. Niente card e niente sidebar:
  quelle appartengono ad argomenti e alle liste risorse.
  Stack: Tailwind + Alpine. Icone inline, niente sprite Bootstrap Italia.
--}}
@php
    $faqTitle = is_string($title ?? null) && $title !== ''
        ? $title
        : (string) __('pub_theme::faq.meta.title');
    $faqIntro = is_string($intro ?? null) && $intro !== ''
        ? $intro
        : (string) __('pub_theme::faq.meta.description');
    $homeUrl = route('home');
    $visibleCount = is_numeric($visible ?? null) ? max(1, (int) $visible) : 5;

    $rawItems = is_array($items ?? null) && $items !== []
        ? $items
        : app(\Modules\Fixcity\Actions\Faq\ListPublishedFaqsAction::class)->execute();
    $faqItems = [];
    foreach ($rawItems as $item) {
        if (! is_array($item)) {
            continue;
        }
        $question = trim((string) ($item['question'] ?? ''));
        $answer = trim((string) ($item['answer'] ?? ''));
        if ($question === '' || $answer === '') {
            continue;
        }
        $faqItems[] = ['question' => $question, 'answer' => $answer];
    }

    // Canon Folio: FrontofficeRoutes (container0.*) — mai localizeUrl né tickets.*.
    $reportUrl = \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::ticketsCreate();
    $trackUrl = \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::ticketsTrack();
    $loginUrl = route('login');
    $defaultContacts = [
        ['group' => 'comune', 'label' => __('pub_theme::faq.contacts.report'), 'href' => $reportUrl, 'icon' => 'pin'],
        ['group' => 'comune', 'label' => __('pub_theme::faq.contacts.track'), 'href' => $trackUrl, 'icon' => 'search'],
        ['group' => 'comune', 'label' => __('pub_theme::faq.contacts.login'), 'href' => $loginUrl, 'icon' => 'user'],
        ['group' => 'citta', 'label' => __('pub_theme::faq.contacts.city_report'), 'href' => $reportUrl, 'icon' => 'pin'],
    ];
    $rawContacts = is_array($contacts ?? null) && $contacts !== [] ? $contacts : $defaultContacts;
    $contactGroups = ['comune' => [], 'citta' => []];
    foreach ($rawContacts as $contact) {
        if (! is_array($contact)) {
            continue;
        }
        $group = ($contact['group'] ?? '') === 'citta' ? 'citta' : 'comune';
        $label = trim((string) ($contact['label'] ?? ''));
        $href = trim((string) ($contact['href'] ?? ''));
        if ($label === '' || $href === '') {
            continue;
        }
        $contactGroups[$group][] = [
            'label' => $label,
            'href' => $href,
            'icon' => (string) ($contact['icon'] ?? 'mail'),
        ];
    }
@endphp

<div
    class="dc-faq"
    x-data="{
        query: '',
        shown: {{ $visibleCount }},
        step: 1,
        rating: 0,
        items: {{ \Illuminate\Support\Js::from($faqItems) }},
        get filtered() {
            const q = this.query.trim().toLowerCase();
            if (q === '') return this.items;
            return this.items.filter((item) => (item.question + ' ' + item.answer).toLowerCase().includes(q));
        },
        get visibleItems() {
            if (this.query.trim() !== '') return this.filtered;
            return this.filtered.slice(0, this.shown);
        }
    }"
>
    <div class="container mx-auto max-w-[950px]">
        <div class="cmp-breadcrumbs" role="navigation">
            <nav class="breadcrumb-container" aria-label="{{ __('fixcity::global.breadcrumb') }}">
                <ol class="breadcrumb p-0" data-element="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ $homeUrl }}">{{ __('pub_theme::faq.navigation.home') }}</a><span class="separator">/</span></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $faqTitle }}</li>
                </ol>
            </nav>
        </div>
        <div class="cmp-hero mx-auto max-w-[950px]">
            <section class="it-hero-wrapper bg-white align-items-start">
                <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                    <h1 class="text-black" data-element="page-name">{{ $faqTitle }}</h1>
                    <div class="hero-text"><p>{{ $faqIntro }}</p></div>
                </div>
            </section>
        </div>
    </div>

    <div id="faq-content" class="container">
        <div class="mx-auto w-full max-w-[760px]">
            <form class="cmp-input-search" role="search" @submit.prevent>
                <div class="input-group relative !flex flex-nowrap items-stretch border-b border-[#5c6f82]">
                    <label for="faq-search" class="sr-only">{{ __('pub_theme::faq.search.label') }}</label>
                    <span class="autocomplete-icon absolute left-0 top-1/2 -translate-y-1/2 text-primary-500" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                    </span>
                    <input id="faq-search" class="form-control min-h-12 min-w-0 flex-1 border-0 bg-transparent px-4 py-3 pl-8 shadow-none outline-none focus:border-0 focus:ring-0" type="search" placeholder="{{ __('pub_theme::faq.search.placeholder') }}" x-model="query">
                    <button class="btn btn-primary inline-flex min-h-12 min-w-24 shrink-0 items-center justify-center rounded-none border-0 bg-primary-500 font-semibold text-white hover:bg-primary-700" type="submit">{{ __('pub_theme::faq.search.submit') }}</button>
                </div>
            </form>

            <div class="cmp-accordion faq">
                <div class="accordion">
                    <template x-for="(item, index) in visibleItems" :key="item.question">
                        <div class="accordion-item border-b border-[#e5e5e5]">
                            <details class="group">
                                <summary class="accordion-button flex w-full cursor-pointer list-none items-center justify-between gap-4 border-t border-[#e5e5e5] px-3 py-4 text-left text-lg font-semibold text-primary-500 hover:text-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500 [&::-webkit-details-marker]:hidden">
                                    <span class="button-wrapper flex w-full items-center justify-between gap-4">
                                        <span x-text="item.question"></span>
                                        <span class="icon-wrapper shrink-0" aria-hidden="true">
                                            <svg class="icon h-4 w-4 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                        </span>
                                    </span>
                                </summary>
                                <div class="accordion-body px-3 pb-3 pt-1 text-base leading-6 text-[#30475f]"><p x-text="item.answer"></p></div>
                            </details>
                        </div>
                    </template>
                    <p x-show="filtered.length === 0" x-cloak>{{ __('pub_theme::faq.search.empty') }}</p>
                </div>
            </div>

            <button
                type="button"
                class="btn btn-outline mt-10 mb-10 block w-full border-2 border-primary-500 bg-white px-4 py-3 text-lg font-bold text-primary-500 hover:bg-primary-50"
                x-show="query.trim() === '' && shown < items.length"
                x-cloak
                @click="shown = items.length"
            >{{ __('pub_theme::faq.search.load_more') }}</button>
        </div>
    </div>

    <section aria-labelledby="faq-feedback-title" data-element="feedback">
        <div class="mx-auto w-full max-w-[720px] px-4">
            <div class="bg-white px-6 py-8 shadow-md sm:px-8">
                <div x-show="step === 1">
                    <h2 id="faq-feedback-title" class="text-xl font-semibold text-[#1a1a1a]">{{ __('pub_theme::faq.feedback.title') }}</h2>
                    <div class="mt-6 flex gap-2" role="group" aria-label="{{ __('pub_theme::faq.feedback.rating_label') }}">
                        <template x-for="star in [5,4,3,2,1]" :key="star">
                            <button
                                type="button"
                                class="text-[#007A52] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#007A52]"
                                :aria-label="'{{ __('pub_theme::faq.feedback.rate') }} ' + star + ' {{ __('pub_theme::faq.feedback.out_of') }}'"
                                @click="rating = star"
                            >
                                <svg class="h-8 w-8" viewBox="0 0 24 24" :fill="rating >= star ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"/></svg>
                            </button>
                        </template>
                    </div>
                    <div class="mt-6 flex justify-center" x-show="rating > 0" x-cloak>
                        <button type="button" class="btn bg-primary-500 px-5 py-2 font-bold text-white hover:bg-primary-700" @click="step = 2">{{ __('pub_theme::faq.feedback.next') }}</button>
                    </div>
                </div>
                <div x-show="step === 2" x-cloak>
                    <h2 class="text-xl font-semibold" x-text="rating >= 4 ? @js(__('pub_theme::faq.feedback.positive_title')) : @js(__('pub_theme::faq.feedback.negative_title'))"></h2>
                    <ul class="mt-4 space-y-3 text-base">
                        <template x-for="label in (rating >= 4 ? @js(__('pub_theme::faq.feedback.positive_options')) : @js(__('pub_theme::faq.feedback.negative_options')) )" :key="label">
                            <li>
                                <label class="flex items-start gap-3">
                                    <input type="checkbox" class="mt-1 h-5 w-5 accent-[#007A52]">
                                    <span x-text="label"></span>
                                </label>
                            </li>
                        </template>
                    </ul>
                    <div class="mt-6 flex justify-center gap-4">
                        <button type="button" class="btn btn-outline border-2 border-primary-500 text-primary-500" @click="step = 1">{{ __('pub_theme::faq.feedback.back') }}</button>
                        <button type="button" class="btn bg-primary-500 font-bold text-white hover:bg-primary-700" @click="step = 3">{{ __('pub_theme::faq.feedback.next') }}</button>
                    </div>
                </div>
                <div x-show="step === 3" x-cloak>
                    <h2 class="text-xl font-semibold">{{ __('pub_theme::faq.feedback.details_title') }}</h2>
                    <label class="mt-4 block text-sm font-semibold" for="faq-feedback-note">{{ __('pub_theme::faq.feedback.details_label') }}</label>
                    <textarea id="faq-feedback-note" rows="4" class="mt-2 w-full border border-[#5c6f82] p-3 text-base focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#007A52]"></textarea>
                    <div class="mt-6 flex justify-center gap-4">
                        <button type="button" class="btn btn-outline border-2 border-primary-500 text-primary-500" @click="step = 2">{{ __('pub_theme::faq.feedback.back') }}</button>
                        <button type="button" class="btn bg-primary-500 font-bold text-white hover:bg-primary-700" @click="step = 4">{{ __('pub_theme::faq.feedback.next') }}</button>
                    </div>
                </div>
                <div x-show="step === 4" x-cloak>
                    <h2 class="text-xl font-semibold">{{ __('pub_theme::faq.feedback.thanks') }}</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-grey-card" aria-label="{{ __('pub_theme::faq.contacts.aria_label') }}">
        <div class="cmp-contacts">
            <div class="card bg-white">
                @if ($contactGroups['comune'] !== [])
                    <h2 class="text-xl font-semibold">{{ __('pub_theme::faq.contacts.title') }}</h2>
                    <ul class="contact-list space-y-2">
                        @foreach ($contactGroups['comune'] as $contact)
                            <li>
                                <a class="list-item inline-flex min-h-11 items-center gap-3 text-primary-500 hover:text-primary-700" href="{{ $contact['href'] }}">
                                    <span aria-hidden="true">
                                        @if ($contact['icon'] === 'search')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                                        @elseif ($contact['icon'] === 'user')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path d="M5 19c1.5-3 4-4.5 7-4.5S17.5 16 19 19"/></svg>
                                        @else
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                        @endif
                                    </span>
                                    <span>{{ $contact['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($contactGroups['citta'] !== [])
                    <h2 class="mt-8 text-xl font-semibold">{{ __('pub_theme::faq.contacts.city_title') }}</h2>
                    <ul class="contact-list space-y-2">
                        @foreach ($contactGroups['citta'] as $contact)
                            <li>
                                <a class="list-item inline-flex min-h-11 items-center gap-3 text-primary-500 hover:text-primary-700" href="{{ $contact['href'] }}">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                    <span>{{ $contact['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>
</div>
