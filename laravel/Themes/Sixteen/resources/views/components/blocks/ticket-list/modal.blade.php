{{-- Pannello filtri su schermo piccolo (pulsante "Filtra" dei risultati): stesso form della colonna desktop, id con prefisso `tlm`. --}}
<div class="modal fade d-lg-none" id="modal-categories" role="dialog" tabindex="-1" aria-labelledby="modal-categories-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title title-small-semi-bold" id="modal-categories-title">{{ $t('filters.modal_title') }}</h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ $t('filters.close') }}">
                    <svg class="icon" aria-hidden="true"><use href="{{ $icon('it-close') }}"></use></svg></button>
            </div>
            <div class="modal-body text-black">
                @include('pub_theme::components.blocks.ticket-list.filters', ['idp' => 'tlm', 'collapsible' => false, 'showReset' => true])
            </div>
        </div>
    </div>
</div>
