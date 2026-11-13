{{--
    Stato vuoto utile (DCP-110): dice perche' non c'e' nulla e cosa fare, con un'azione vera.
    Parametri: $ns (practices|followed), $ctaUrl. Stesse classi della scheda `cmp-card` del riferimento.
--}}
<div class="it-page-section mb-40 mb-lg-60">
    <div class="cmp-card">
        <div class="card">
            <div class="card-body p-4 p-lg-5">
                <h2 class="title-xxlarge mb-3">{{ __('fixcity::personal_area.'.$ns.'.empty.title') }}</h2>
                <p class="text-paragraph mb-4">{{ __('fixcity::personal_area.'.$ns.'.empty.text') }}</p>
                <a class="btn btn-primary" href="{{ $ctaUrl }}"><span>{{ __('fixcity::personal_area.'.$ns.'.empty.cta') }}</span></a>
            </div>
        </div>
    </div>
</div>
