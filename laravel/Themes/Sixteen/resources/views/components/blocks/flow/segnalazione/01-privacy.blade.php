@props(['data' => []])

@php
    $title = $data['title'] ?? __('fixcity::segnalazione.privacy.title.label');
    $description = $data['description'] ?? __('fixcity::segnalazione.privacy.description.text');
    $nextLabel = $data['next_label'] ?? __('fixcity::segnalazione.buttons.next.label');
    $nextUrl = $data['next_url'] ?? '/it/tests/segnalazione-02-dati';
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 pb-40 pb-lg-80">
            @if($description)
                <p class="text-paragraph mb-lg-4">
                    {{ $description }}
                </p>
            @endif

            <p class="text-paragraph mb-0">
                {{ __('fixcity::segnalazione.privacy.details.text') }}
                <a href="#" class="t-primary">{{ __('fixcity::segnalazione.privacy.details.link.label') }}</a>
            </p>

            <div class="form-check mt-4 mb-3 mt-md-40 mb-lg-40" x-data="{ checked: false }">
                <div class="checkbox-body d-flex align-items-center">
                    <input type="checkbox" id="privacy" name="privacy-field" value="privacy-field"
                           x-model="checked" data-element="privacy-consent">
                    <label class="title-small-semi-bold pt-1" for="privacy">
                        {{ __('fixcity::segnalazione.privacy.accept.label') }}
                    </label>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <a href="{{ $nextUrl }}"
                   class="btn btn-primary mobile-full"
                   x-data="{ checked: false }"
                   :class="{ 'disabled': !checked }"
                   :disabled="!checked"
                   data-element="step-next">
                    <span>{{ $nextLabel }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                @include('components.blocks.contacts.faq')
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button 
            type="button"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!consensoTrattamento"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>
