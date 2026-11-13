{{-- Invito a segnalare (lang ticket_list.invite). Parametro: $headingId (id univoco dell'h2: la pagina ha un invito per scheda). --}}
<div class="col-lg-6 mt-50 mb-4 mb-lg-0">
    <div class="cmp-text-button mt-0">
        <h2 class="title-xxlarge mb-0" id="{{ $headingId }}">{{ $t('invite.title') }}</h2>
        <div class="text-wrapper"><p class="subtitle-small mb-3 mt-3">{{ $t('invite.text') }}</p></div>
        <div class="button-wrapper">
            <a href="{{ $base }}/create" class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0"><span class="">{{ $t('invite.button') }}</span></a>
        </div>
    </div>
</div>
