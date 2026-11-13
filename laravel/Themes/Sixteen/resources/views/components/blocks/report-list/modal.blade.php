{{-- Modale di dettaglio segnalazione (JSON: modal.id, title, fields[label, value], images{label, file, alt}, close_label, close_button). --}}
<div class="it-example-modal">
    <div class="modal fade" tabindex="-1" role="dialog" id="{{ $modalId }}" aria-labelledby="{{ $d('modal.title_id') }}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title title-small-semi-bold" id="{{ $d('modal.title_id') }}">{{ $d('modal.title') }}</h2>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ $d('modal.close_label') }}">
                        <svg class="icon"><use href="{{ $icon('it-close') }}"></use></svg></button>
                </div>
                <div class="modal-body text-black">
                    @foreach ($d('modal.fields', []) as $field)
                        <div class="border-bottom border-light">
                            <h3 class="title-xsmall border-light pt-2">{{ $field['label'] ?? '' }}</h3>
                            <p class="subtitle-small pb-2">{{ $field['value'] ?? '' }}</p>
                        </div>
                    @endforeach
                    <h3 class="title-xsmall border-light pt-2">{{ $d('modal.images.label') }}</h3>
                    <img src="{{ $assets }}/images/{{ $d('modal.images.file') }}" class="w-100 mb-2" alt="{{ $d('modal.images.alt') }}">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary w-100 btn-sm" type="button" data-bs-dismiss="modal" aria-label="{{ $d('modal.close_label') }}">{{ $d('modal.close_button') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
