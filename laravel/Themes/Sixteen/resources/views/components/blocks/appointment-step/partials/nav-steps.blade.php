{{--
    Pulsanti del passo (JSON: nav.aria_label, back, save + save_short [opzionali], next, next_class, next_target, next_icon, saved_message).
    Senza `nav.save` niente "Salva"; `nav.next_icon = false` e `nav.next_class = send` danno il pulsante "Invia" del riepilogo.
--}}
<div class="cmp-nav-steps">
    <nav class="steppers-nav" aria-label="{{ $d('nav.aria_label') }}">
        <button type="button" class="btn btn-sm steppers-btn-prev p-0">
            <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon('it-chevron-left') }}"></use></svg>
            <span class="text-button-sm t-primary">{{ $d('nav.back') }}</span>
        </button>
        @if (filled($d('nav.save')))
            <button type="button" class="btn btn-outline-primary bg-white btn-sm steppers-btn-save d-none d-lg-block saveBtn">
                <span class="text-button-sm t-primary">{{ $d('nav.save') }}</span>
            </button>
            <button type="button" class="btn btn-outline-primary bg-white btn-sm steppers-btn-save d-block d-lg-none saveBtn center">
                <span class="text-button-sm t-primary">{{ $d('nav.save_short') }}</span>
            </button>
        @endif
        <button type="button" class="btn btn-primary btn-sm steppers-btn-confirm{{ filled($d('nav.next_class')) ? ' '.$d('nav.next_class') : '' }}" data-bs-toggle="modal" data-bs-target="{{ $d('nav.next_target', '#') }}">
            <span class="text-button-sm">{{ $d('nav.next') }}</span>
            @if ($d('nav.next_icon', true))
                <svg class="icon icon-white icon-sm" aria-hidden="true"><use href="{{ $icon('it-chevron-right') }}"></use></svg>
            @endif
        </button>
    </nav>
    <div id="alert-message" class="alert alert-success cmp-disclaimer rounded d-none" role="alert">
        <span class="d-inline-block text-uppercase cmp-disclaimer__message">{{ $d('nav.saved_message') }}</span>
    </div>
</div>
