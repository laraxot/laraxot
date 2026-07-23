@props(['sprite' => '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg'])

@php
    $ns = 'fixcity::ticket';
    $placeholderImage = '/themes/Sixteen/design-comuni/assets/images/img-disservizio-thumbnail.png';
@endphp

<div class="it-example-modal">
    <div
        class="modal fade"
        id="modal-disservizio"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modal2Title"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title title-small-semi-bold" id="modal2Title">
                        {{ __($ns . '.modal.detail.title.label') }}
                    </h2>
                    <button
                        class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="{{ __('Chiudi') }}"
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="{{ $sprite }}#it-close"></use>
                        </svg>
                    </button>
                </div>
                <div class="modal-body text-black">
                    <div class="border-bottom border-light">
                        <h3 class="title-xsmall border-light pt-2">{{ __($ns . '.modal.detail.fields.title.label') }}</h3>
                        <p class="subtitle-small pb-2" data-element="modal-ticket-title">—</p>
                    </div>
                    <div class="border-bottom border-light">
                        <h3 class="title-xsmall border-light pt-2">{{ __($ns . '.modal.detail.fields.type.label') }}</h3>
                        <p class="subtitle-small pb-2" data-element="modal-ticket-type">—</p>
                    </div>
                    <div class="border-bottom border-light">
                        <h3 class="title-xsmall border-light pt-2">{{ __($ns . '.modal.detail.fields.address.label') }}</h3>
                        <p class="subtitle-small pb-2" data-element="modal-ticket-address">—</p>
                    </div>
                    <div class="border-bottom border-light">
                        <h3 class="title-xsmall border-light pt-2">{{ __($ns . '.modal.detail.fields.detail.label') }}</h3>
                        <p class="subtitle-small pb-2" data-element="modal-ticket-detail">—</p>
                    </div>
                    <h3 class="title-xsmall border-light pt-2">{{ __($ns . '.modal.detail.fields.images.label') }}</h3>
                    <img
                        src="{{ $placeholderImage }}"
                        class="w-100 mb-2"
                        alt="{{ __($ns . '.modal.detail.image.alt') }}"
                        loading="lazy"
                    >
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-outline-primary w-100 btn-sm"
                        type="button"
                        data-bs-dismiss="modal"
                    >
                        {{ __($ns . '.modal.detail.cta.label') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
