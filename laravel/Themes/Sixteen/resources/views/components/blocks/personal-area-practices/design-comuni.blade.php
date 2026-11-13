@props(['data' => []])
{{--
    Area personale / Le mie segnalazioni (pagina reale area-personale.pratiche, DCP-110). Struttura del riferimento
    sito/segnalazione-area-personale (blocco report-area, variante `list`): testata, schede di navigazione, indice laterale e sezioni
    a fisarmonica. I valori sono veri: BuildPersonalAreaPracticesDataAction legge solo le segnalazioni di cui l'utente e'
    PROPRIETARIO. Testi in fixcity::personal_area (it, en, de, es), nessun testo nel JSON. Accesso: `"middleware": ["auth"]` nel JSON.
--}}
@php
    $user = auth()->user();
    abort_unless($user instanceof \Modules\Xot\Contracts\UserContract, 403);
    $locale = app()->getLocale();
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $localize = static fn (string $path): string => (string) \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($locale, $path);
    $practices = app(\Modules\Fixcity\Actions\BuildPersonalAreaPracticesDataAction::class)->execute($user, $locale);
    $groups = array_filter([
        'in_progress' => $practices['in_progress'],
        'concluded' => $practices['concluded'],
    ]);
    $title = __('fixcity::personal_area.practices.title');
@endphp
<div class="container" id="main-container">
    @include('pub_theme::components.blocks.personal-area-practices.heading', [
        'active' => 'practices',
        'title' => $title,
        'subtitle' => __('fixcity::personal_area.practices.subtitle'),
        'cta' => $groups === [] ? null : ['label' => __('fixcity::personal_area.new_report'), 'url' => $localize('/tickets/create')],
    ])
    <div class="it-page-sections-container">
        <div class="row justify-content-center">
            @if ($groups === [])
                <div class="col-12 col-lg-10">
                    @include('pub_theme::components.blocks.personal-area-practices.empty', ['ns' => 'practices', 'ctaUrl' => $localize('/tickets/create')])
                </div>
            @else
                @if (count($groups) > 1)
                    <div class="d-none d-lg-block col-lg-3">
                        @include('pub_theme::components.blocks.personal-area-practices.navscroll', ['nav' => [
                            'suffix' => 'Practices',
                            'title' => __('fixcity::personal_area.index_title'),
                            'items' => array_map(
                                static fn (string $group): array => ['anchor' => 'practices-'.$group, 'label' => __('fixcity::personal_area.groups.'.$group).' ('.count($groups[$group]).')'],
                                array_keys($groups),
                            ),
                        ]])
                    </div>
                @endif
                {{-- con l'indice laterale la colonna e' quella della variante `list` del riferimento (px-0 px-sm-3); senza, resta allineata a titolo e briciole --}}
                <div @class(['col-12', 'col-lg-8 offset-lg-1 px-0 px-sm-3' => count($groups) > 1, 'col-lg-10' => count($groups) === 1])>
                    @foreach ($groups as $group => $rows)
                        @include('pub_theme::components.blocks.personal-area-practices.section', [
                            'id' => 'practices-'.$group,
                            'heading' => __('fixcity::personal_area.groups.'.$group),
                            'rows' => $rows,
                            'trackKey' => 'track_url',
                        ])
                    @endforeach
                    @if ($practices['truncated'])
                        <p class="text-paragraph-small mb-40">{{ __('fixcity::personal_area.truncated', ['shown' => count($practices['in_progress']) + count($practices['concluded']), 'total' => $practices['total']]) }}</p>
                    @endif
                    <p class="text-paragraph mb-50 mb-lg-90">{{ __('fixcity::personal_area.practices.notifications.text') }} <a class="t-primary text-decoration-underline" href="{{ $localize('/area-personale/impostazioni') }}">{{ __('fixcity::personal_area.practices.notifications.link') }}</a></p>
                </div>
            @endif
        </div>
    </div>
</div>
