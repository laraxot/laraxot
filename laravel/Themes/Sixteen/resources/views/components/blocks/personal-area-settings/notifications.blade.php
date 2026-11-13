@props(['data' => []])
{{--
    Pagina "Notifiche" dell'area personale (DCP-111), JSON `area-personale.notifiche`.
    Chrome (briciole, h1, tab) = personal-area/heading — stesso di pratiche/seguite/impostazioni.
    Elenco e "segna come letta" = Livewire PersonalAreaNotificationsWidget + Actions Fixcity.
    Testi: fixcity::personal_area_settings.*. Classi Design Comuni.
--}}
<div class="container" id="main-container">
    @include('pub_theme::components.blocks.personal-area.heading', [
        'active' => 'notifications',
        'title' => __('fixcity::personal_area_settings.notifications.title'),
        'subtitle' => __('fixcity::personal_area_settings.notifications.subtitle'),
    ])
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <p class="text-paragraph mb-3">{{ __('fixcity::personal_area_settings.notifications.settings_hint') }}</p>
            <p class="mb-4"><a class="btn btn-outline-primary" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaImpostazioni() }}">{{ __('fixcity::personal_area_settings.notifications.settings_link') }}</a></p>
            @livewire(\Modules\Fixcity\Livewire\PersonalAreaNotificationsWidget::class)
        </div>
    </div>
</div>
