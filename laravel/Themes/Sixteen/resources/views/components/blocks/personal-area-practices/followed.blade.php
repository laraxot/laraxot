@props(['data' => []])
{{--
    Area personale / Segnalazioni che seguo (pagina reale area-personale.seguite, DCP-110). Come design-comuni.blade.php (le mie
    segnalazioni) ma con un solo elenco. Il tracciamento e' SEMPRE per codice (`/tickets/track?code=...`), mai per id del database;
    se il codice manca (ticket storici) la riga lo dice onestamente invece di inventare un link.
--}}
@php
    $user = auth()->user();
    abort_unless($user instanceof \Modules\Xot\Contracts\UserContract, 403);
    $locale = app()->getLocale();
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $localize = static fn (string $path): string => (string) \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($locale, $path);
    $followed = app(\Modules\Fixcity\Actions\BuildPersonalAreaFollowedDataAction::class)->execute($user, $locale);
@endphp
<div class="container" id="main-container">
    @include('pub_theme::components.blocks.personal-area-practices.heading', [
        'active' => 'followed',
        'title' => __('fixcity::personal_area.followed.title'),
        'subtitle' => __('fixcity::personal_area.followed.subtitle'),
    ])
    <div class="it-page-sections-container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                @if ($followed['rows'] === [])
                    @include('pub_theme::components.blocks.personal-area-practices.empty', ['ns' => 'followed', 'ctaUrl' => $localize('/tickets')])
                @else
                    @include('pub_theme::components.blocks.personal-area-practices.section', [
                        'id' => 'followed',
                        'heading' => __('fixcity::personal_area.followed.section'),
                        'rows' => $followed['rows'],
                        'trackKey' => 'track_query_url',
                        'class' => 'mb-40 mb-lg-60',
                    ])
                    @if ($followed['truncated'])
                        <p class="text-paragraph-small mb-50 mb-lg-90">{{ __('fixcity::personal_area.truncated', ['shown' => count($followed['rows']), 'total' => $followed['total']]) }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
