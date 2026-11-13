{{--
    Modale "Termini e condizioni" aperta dal pulsante "Invia" (JSON: modal.id, title, info, link, confirm, cancel, close_label). Comportamento
    (display, sfondo, chiusura) delle modali `.modal.fade` delle pagine /tests: resources/js/app.js.
--}}
<div class="cmp-modal">
    <div class="modal fade" tabindex="-1" role="dialog" id="{{ $d('modal.id') }}" aria-labelledby="{{ $d('modal.id') }}-modal-title">
        <div class="modal-dialog modal-dialog-centered small" role="document">
            <div class="modal-content modal-dimensions">
                <div class="cmp-modal__header modal-header pb-0">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ $d('modal.close_label') }}"></button>
                    <h2 class="cmp-modal__header-title title-mini" id="{{ $d('modal.id') }}-modal-title">{{ $d('modal.title') }}</h2>
                    <p class="cmp-modal__header-info header-font">{{ $d('modal.info') }}</p>
                    <a href="{{ $d('modal.link_url', '#') }}" class="cmp-modal__header-link text-success underline mt-1">{{ $d('modal.link') }}</a>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer pb-70 pt-0">
                    <button class="btn btn-primary w-100 mx-0 fw-bold mb-4" type="submit" data-bs-toggle="modal" data-bs-target="#" form="">{{ $d('modal.confirm') }}</button>
                    <button class="btn btn-outline-primary w-100 mx-0" data-bs-dismiss="modal" type="button">{{ $d('modal.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
