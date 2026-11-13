<x-filament-widgets::widget>
@php
    // i partial di blocks/report-partials ereditano $d, $icon e $parts (stesso contratto di report-step/design-comuni)
    $data = $blockData ?? [];
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $parts = 'pub_theme::components.blocks.report-partials';
@endphp
{{-- Design Comuni wrapper --}}
<div
    class="cmp-wizard-widget"
    x-data="{
        privacyErrorFocused: false,
        focusPrivacyError() {
            const input = $el.querySelector('[data-element=privacy-consent]');
            const invalid = input?.classList.contains('fi-invalid') ?? false;

            if (invalid && !this.privacyErrorFocused) {
                input.focus();
            }

            this.privacyErrorFocused = invalid;
        },
    }"
    x-init="
        const observer = new MutationObserver(() => $nextTick(() => focusPrivacyError()));
        observer.observe($el, { attributes: true, attributeFilter: ['class'], childList: true, subtree: true });
    "
>
    {{-- Guscio Design Comuni (DCP-33): container, briciole, h1, wizard (barra dei passi + passo) e contatti, markup di sito/segnalazione-0N. --}}
    <div class="container" id="main-container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                @include($parts.'.breadcrumb')
                <div class="cmp-heading pb-3 pb-lg-4">
                    <h1 class="title-xxxlarge">{{ $pageTitle }}</h1>
                    @if($pageDescription !== '')
                        <p class="subtitle-small">{{ $pageDescription }}</p>
                    @endif
                </div>
            </div>
            <div class="col-12">
                <span id="ticket-privacy-error" class="visually-hidden">
                    {{ isset($errors) ? $errors->first('data.privacyAccepted') : '' }}
                </span>
                @if (isset($errors) && ($errors->has('data.submit') || $errors->has('submit')))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('data.submit') ?: $errors->first('submit') }}
                    </div>
                @endif
                @include('fixcity::filament.widgets.partials.session-expired-alert')
                {{ $this->form }}
            </div>
        </div>
    </div>
    @include($parts.'.contacts')
</div>
<x-filament-actions::modals />
</x-filament-widgets::widget>
