{{--
    canonical e hreflang di ogni pagina (Linee guida di design 4.3.9, DCP-166). Un solo punto: tutti i layout passano da main.blade.php.
    Gli indirizzi si costruiscono dal percorso della richiesta (`request()->path()`, che non ha query string) sostituendo la lingua:
    `getLocalizedURL()` dipende da una richiesta memorizzata nel singleton e in un processo che serve piu' richieste (test, Octane)
    perdeva il percorso. Niente query nel canonical: `?code=` e' il codice di tracciamento di una segnalazione (dato personale) e non
    deve finire negli indici dei motori di ricerca. Senza prefisso di lingua nel percorso, o se la localizzazione non e' disponibile, il
    layout resta valido, senza questi link.
    L'unica query che resta e' `page` (dalla 2 in su): ogni pagina di un elenco e' una pagina a se' (FO-6-25, DCP-288); i filtri (`q`, ...)
    tornano alla pagina base.
--}}
@php
    $seoUrls = [];
    $seoDefault = 'it';
    try {
        $seoLocalization = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getFacadeRoot();
        $seoLocales = array_keys($seoLocalization->getSupportedLocales());
        $seoDefault = (string) $seoLocalization->getDefaultLocale();
        [$seoFirst, $seoRest] = array_pad(explode('/', trim(request()->path(), '/'), 2), 2, '');
        if (in_array($seoFirst, $seoLocales, true)) {
            $seoPage = filter_var(request()->query('page'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 2]]);
            $seoQuery = $seoPage === false ? '' : '?page='.$seoPage;
            foreach ($seoLocales as $seoLocale) {
                $seoUrls[$seoLocale] = url('/'.$seoLocale.($seoRest !== '' ? '/'.$seoRest : '')).$seoQuery;
            }
        }
    } catch (\Throwable) {
        $seoUrls = [];
    }
@endphp
@if ($seoUrls !== [])
    <link rel="canonical" href="{{ $seoUrls[app()->getLocale()] ?? reset($seoUrls) }}">
    @foreach ($seoUrls as $seoLocale => $seoUrl)
        <link rel="alternate" hreflang="{{ $seoLocale }}" href="{{ $seoUrl }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $seoUrls[$seoDefault] ?? reset($seoUrls) }}">
@endif
