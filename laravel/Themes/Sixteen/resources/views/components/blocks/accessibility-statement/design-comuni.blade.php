@props(['data' => []])
{{--
    Dichiarazione di accessibilita' (L. 4/2004 art. 3-quater, DCP-132), a sezioni del modello AgID: stato di conformita', contenuti non
    accessibili, redazione, feedback e procedura dell'art. 3-quinquies. Il codice non contiene valori: stato, date, parti non
    accessibili e casella delle segnalazioni vengono dal JSON `accessibilita.index` (chiave `declaration` del blocco). Senza questi dati,
    o con dati incoerenti, la pagina risponde 200 e un avviso elenca che cosa deve ancora fornire l'ente (AssessAccessibilityStatementAction).
    Il feedback e' il componente Livewire AccessibilityFeedbackForm (email alla casella dell'ente). Markup: breadcrumb e hero del sito.
    Modello della Decisione (UE) 2018/1523: denominazione dell'ente (config ente, GetEnteDataAction), ambito, stato di conformita' con la norma
    di riferimento, contenuti non accessibili, redazione (metodo, date, che cosa e' stato e NON e' stato verificato), recapiti del
    responsabile, procedura di attuazione. Gli elenchi `verified` e `not_verified` sono testo dell'ente nel JSON, mai del codice.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $declaration = array_filter((array) $d('declaration', []), static fn (mixed $value, mixed $key): bool => is_string($key), ARRAY_FILTER_USE_BOTH);
    $issues = app(\Modules\Fixcity\Actions\AssessAccessibilityStatementAction::class)->execute($declaration);
    $statuses = \Modules\Fixcity\Actions\AssessAccessibilityStatementAction::STATUSES;
    $status = in_array($declaration['status'] ?? null, $statuses, true) ? $declaration['status'] : null;
    $when = static fn (string $key): string => isset($issues[$key]) ? __('pub_theme::accessibility.drafting.pending')
        : \Illuminate\Support\Carbon::parse((string) data_get($declaration, $key))->locale(app()->getLocale())->isoFormat('LL');
    $method = data_get($declaration, 'evaluation.method');
    $email = ! isset($issues['feedback_email']) ? (string) ($declaration['feedback_email'] ?? '') : '';
    $procedureUrl = ! isset($issues['procedure_url']) ? (string) ($declaration['procedure_url'] ?? '') : '';
    $agidUrl = filter_var($declaration['agid_declaration_url'] ?? null, FILTER_VALIDATE_URL) !== false ? (string) $declaration['agid_declaration_url'] : '';
    $partLists = ['non_accessible', 'disproportionate_burden', 'out_of_scope'];
    $entity = app(\Modules\Tenant\Actions\Ente\GetEnteDataAction::class)->execute()->name;
    $officer = ! isset($issues['officer']) && ! str_contains((string) ($declaration['officer'] ?? ''), '[[') ? (string) ($declaration['officer'] ?? '') : '';
    $evidence = static fn (string $key): array => array_values(array_filter((array) ($declaration[$key] ?? []), static fn (mixed $line): bool => is_string($line) && trim($line) !== ''));
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include('pub_theme::components.blocks.content-page.partials.breadcrumb', ['breadcrumb' => ['items' => [['label' => __('pub_theme::accessibility.home'), 'url' => '/'.app()->getLocale()]], 'current' => __('pub_theme::accessibility.page.title.text')]])
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                        <h1 class="text-black" data-element="page-name">{{ __('pub_theme::accessibility.page.title.text') }}</h1>
                        <div class="hero-text"><p>{{ __('pub_theme::accessibility.lead') }} <a href="#feedback">{{ __('pub_theme::accessibility.feedback.jump') }}</a></p></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="row">
                <div class="col-lg-9">
            @if ($issues !== [])
                <div class="callout callout-highlight ps-3 warning mb-5" data-element="accessibility-incomplete">
                    <div class="callout-title mb-20 d-flex align-items-center">
                        <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $icon('it-horn') }}"></use></svg>
                        <h2 class="h5 mb-0">{{ __('pub_theme::accessibility.incomplete.title') }}</h2>
                    </div>
                    <p>{{ __('pub_theme::accessibility.incomplete.intro') }}</p>
                    <ul>
                        @foreach ($issues as $key => $reason)
                            <li><strong>{{ __('fixcity::accessibility.issues.'.$key.'.title') }}</strong>: {{ __('fixcity::accessibility.reason.'.$reason) }}. {{ __('fixcity::accessibility.issues.'.$key.'.hint') }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <section class="mb-5" aria-labelledby="a11y-scope" data-element="accessibility-scope">
                <h2 class="h4" id="a11y-scope">{{ __('pub_theme::accessibility.scope.title') }}</h2>
                @if ($entity !== '')
                    <p>{{ __('pub_theme::accessibility.scope.commitment', ['entity' => $entity]) }}</p>
                @endif
                <p>{{ __('pub_theme::accessibility.scope.text', ['url' => request()->getSchemeAndHttpHost()]) }}</p>
            </section>
            <section class="mb-5" aria-labelledby="a11y-status" data-element="accessibility-status">
                <h2 class="h4" id="a11y-status">{{ __('pub_theme::accessibility.status.title') }}</h2>
                <p>{{ __('pub_theme::accessibility.status.'.($status ?? 'pending')) }}</p>
                @if ($status !== null)
                    <p>{{ __('pub_theme::accessibility.status.reference') }}</p>
                @endif
            </section>
            <section class="mb-5" aria-labelledby="a11y-content">
                <h2 class="h4" id="a11y-content">{{ __('pub_theme::accessibility.content.title') }}</h2>
                @php($listed = false)
                @foreach ($partLists as $list)
                    @if (is_array($declaration[$list] ?? null) && $declaration[$list] !== [])
                        @php($listed = true)
                        <h3 class="h5">{{ __('pub_theme::accessibility.content.'.$list) }}</h3>
                        <ul>
                            @foreach ($declaration[$list] as $part)
                                <li>{{ data_get($part, 'content') }}. <strong>{{ __('pub_theme::accessibility.content.reason') }}</strong>: {{ data_get($part, 'reason') }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
                @if (! $listed)
                    <p>{{ __('pub_theme::accessibility.content.'.($status === null || $status !== 'compliant' ? 'pending' : 'none')) }}</p>
                @endif
            </section>
            <section class="mb-5" aria-labelledby="a11y-drafting">
                <h2 class="h4" id="a11y-drafting">{{ __('pub_theme::accessibility.drafting.title') }}</h2>
                <p class="mb-1"><strong>{{ __('pub_theme::accessibility.drafting.method') }}</strong>: {{ isset($issues['evaluation']) ? __('pub_theme::accessibility.drafting.pending') : __('pub_theme::accessibility.drafting.methods.'.$method) }}</p>
                <p class="mb-1"><strong>{{ __('pub_theme::accessibility.drafting.evaluation_date') }}</strong>: {{ isset($issues['evaluation']) ? __('pub_theme::accessibility.drafting.pending') : $when('evaluation.date') }}</p>
                <p class="mb-1"><strong>{{ __('pub_theme::accessibility.drafting.published_at') }}</strong>: {{ $when('published_at') }}</p>
                <p class="mb-1"><strong>{{ __('pub_theme::accessibility.drafting.updated_at') }}</strong>: {{ $when('updated_at') }}</p>
                @foreach (['verified', 'not_verified'] as $list)
                    @if ($evidence($list) !== [])
                        <h3 class="h5 mt-4" data-element="accessibility-{{ str_replace('_', '-', $list) }}">{{ __('pub_theme::accessibility.drafting.'.$list) }}</h3>
                        <ul>
                            @foreach ($evidence($list) as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
                @if ($agidUrl !== '')
                    <p class="mt-3"><a href="{{ $agidUrl }}" rel="noopener">{{ __('pub_theme::accessibility.drafting.agid') }}</a></p>
                @endif
            </section>
            <section class="mb-5" aria-labelledby="feedback" data-element="accessibility-feedback">
                <h2 class="h4" id="feedback">{{ __('pub_theme::accessibility.feedback.title') }}</h2>
                <p>{{ __('pub_theme::accessibility.feedback.intro') }}</p>
                <p>{{ $email !== '' ? __('pub_theme::accessibility.feedback.email') : __('pub_theme::accessibility.feedback.pending') }} @if ($email !== '')<a href="mailto:{{ $email }}">{{ $email }}</a>@endif</p>
                @if ($officer !== '')
                    <p><strong>{{ __('pub_theme::accessibility.feedback.officer') }}</strong>: {{ $officer }}</p>
                @endif
                @livewire(\Modules\Fixcity\Livewire\AccessibilityFeedbackForm::class)
            </section>
            <section class="mb-5" aria-labelledby="a11y-procedure">
                <h2 class="h4" id="a11y-procedure">{{ __('pub_theme::accessibility.procedure.title') }}</h2>
                <p>{{ __('pub_theme::accessibility.procedure.text') }}</p>
                @if ($procedureUrl !== '')
                    <p><a href="{{ $procedureUrl }}" rel="noopener">{{ __('pub_theme::accessibility.procedure.link') }}</a></p>
                @endif
            </section>
                </div>
            </div>
        </div>
    </div>
</div>
