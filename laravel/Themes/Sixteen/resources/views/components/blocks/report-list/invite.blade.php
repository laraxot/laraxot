{{-- Invito a segnalare (JSON: invite.title, text, button): presente in entrambi i pannelli dei tab. --}}
<div class="col-lg-6 mt-50 mb-4 mb-lg-0">
    <div class="cmp-text-button mt-0">
        <h2 class="title-xxlarge mb-0">{{ $d('invite.title') }}</h2>
        <div class="text-wrapper"><p class="subtitle-small mb-3 mt-3">{{ $d('invite.text') }}</p></div>
        <div class="button-wrapper">
            <button type="button" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" class="btn btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0"><span class="">{{ $d('invite.button') }}</span></button>
        </div>
    </div>
</div>
