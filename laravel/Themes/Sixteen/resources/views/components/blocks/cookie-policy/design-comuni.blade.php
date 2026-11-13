@props(['data' => []])
{{--
    Pagina "Cookie policy" (DCP-153). Non ha un testo dell'ente da attendere: elenca i cookie che il server imposta davvero, ricavati da
    Modules\Gdpr\Actions\Cookie\GetCookieInventoryAction (la stessa fonte del banner), quindi non puo' dire una cosa diversa dal banner.
    Titolare, DPO e diritti dell'interessato stanno nell'informativa privacy, a cui questa pagina rimanda.
    Testi in Modules/Gdpr/lang/cookie-consent/{it,en,de,es}/texts.php. Il pulsante con `data-cookie-preferences` riapre le preferenze.
--}}
@php
    use Modules\Gdpr\Enums\CookieCategory;

    $inventory = app(\Modules\Gdpr\Actions\Cookie\GetCookieInventoryAction::class)->execute();
    $t = static fn (string $key): string => (string) trans('cookie-consent::texts.'.$key);
    $title = $t('policy_title');
    $typeLabel = static fn (CookieCategory $category): string => $t(match ($category) {
        CookieCategory::Necessary => 'policy_technical',
        CookieCategory::Analytics => 'policy_analytics',
        CookieCategory::Marketing => 'policy_marketing',
    });
    $privacyUrl = \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/privacy');
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include('pub_theme::components.blocks.content-page.partials.breadcrumb', ['breadcrumb' => ['items' => [['label' => __('pub_theme::legal.home'), 'url' => '/'.app()->getLocale()]], 'current' => $title]])
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                        <h1 class="text-black" data-element="page-name">{{ $title }}</h1>
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
                <div class="col-lg-9" data-element="cookie-policy">
                    <p>{{ $t('policy_intro') }}</p>
                    {{-- Il CSS del tema non definisce .table-responsive e le utility Tailwind sono spente nello scope data-dc: lo scorrimento a 375 px e' inline finche' una regola non entra in 20-dc-components.css (build). --}}
                    <div class="table-responsive" style="overflow-x: auto">
                        <table class="table table-borderless-0 align-middle" data-element="cookie-inventory">
                            <caption>{{ $t('policy_table_caption') }}</caption>
                            <thead>
                                <tr>
                                    <th scope="col">{{ $t('policy_name') }}</th>
                                    <th scope="col">{{ $t('policy_purpose') }}</th>
                                    <th scope="col">{{ $t('policy_duration') }}</th>
                                    <th scope="col">{{ $t('policy_type') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventory->cookies as $cookie)
                                    <tr>
                                        <th scope="row"><code>{{ $cookie->name }}</code></th>
                                        <td>{{ $cookie->purpose }}</td>
                                        <td>{{ $cookie->duration }}</td>
                                        <td>{{ $typeLabel($cookie->category) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <h2 class="h4 mt-5">{{ $t('policy_how_title') }}</h2>
                    <p>{{ $t('policy_how_text') }}</p>
                    <p><button type="button" class="btn btn-primary" data-cookie-preferences data-element="cookie-preferences-open">{{ $t('policy_manage_button') }}</button></p>
                    <h2 class="h4 mt-5">{{ $t('policy_external_title') }}</h2>
                    <p>{{ $t('policy_external_text') }}</p>
                    <p><a href="{{ $privacyUrl }}">{{ $t('policy_privacy_link') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
