{{-- Modale del messaggio (JSON: modal.id, date, title, subtitle, text, link{label, url}, close_label); aperta dalle schede messaggio. --}}
<div class="cmp-modal">
    <div class="modal fade it-dialog-scrollable" tabindex="-1" role="dialog" id="{{ $d('modal.id') }}" aria-labelledby="{{ $d('modal.id') }}-modal-title">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-dimensions h-100">
                <div class="cmp-modal__header modal-header pb-0">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ $d('modal.close_label') }}"></button>
                    <div class="cmp-modal__header mt-30 mt-lg-50"><date class="date-regular w-100">{{ $d('modal.date') }}</date>
                        <h2 id="{{ $d('modal.id') }}-modal-title" class="title-xxxlarge mt-2 mb-0">{{ $d('modal.title') }}</h2></div>
                </div>
                <div class="modal-body">
                    <div class="mb-60 mb-lg-80">
                        <h5 class="subtitle-large">{{ $d('modal.subtitle') }}</h5>
                        <p class="text-paragraph mb-4 fw-normal">{{ $d('modal.text') }}</p>
                        <a href="{{ $d('modal.link.url', '#') }}" class="text-paragraph t-primary text-decoration-underline">{{ $d('modal.link.label') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
