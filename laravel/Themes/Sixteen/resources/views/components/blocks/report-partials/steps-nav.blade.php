{{--
    Pulsanti del passo (JSON: nav.aria_label, back, save + save_short [opzionali], next, next_class, next_target, next_icon, saved_message).
    `back_click` [opzionale] = espressione Alpine di "Indietro" (wizard); `next_click` [opzionale] = espressione Alpine/Livewire del pulsante finale (es. `$wire.submit()`): al posto dell'apertura della modale `next_target`. Senza `nav.save` niente "Salva"; `next_icon = false` e `next_class = send` danno il pulsante "Invia" del riepilogo.
--}}
@php
    // `next_click` = `$wire.metodo(...)`: il pulsante resta disabilitato mentre quel metodo Livewire e' in corso (DCP-286: un doppio clic su "Invia"
    // non deve partire due volte; la garanzia vera e' lato server, SubmitTicketOnceAction).
    $nextWireTarget = preg_match('/^\$wire\.(\w+)\(/', (string) $d('nav.next_click'), $nextWireMatch) === 1 ? $nextWireMatch[1] : null;
@endphp
<div class="cmp-nav-steps">
    <nav class="steppers-nav" aria-label="{{ $d('nav.aria_label') }}">
        <button type="button" class="btn btn-sm steppers-btn-prev p-0"@if (filled($d('nav.back_click'))) x-on:click="{{ $d('nav.back_click') }}"@endif>
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
        <button type="button" class="btn btn-primary btn-sm steppers-btn-confirm{{ filled($d('nav.next_class')) ? ' '.$d('nav.next_class') : '' }}"@if (filled($d('nav.next_click'))) x-on:click="{{ $d('nav.next_click') }}"@if ($nextWireTarget) wire:loading.attr="disabled" wire:target="{{ $nextWireTarget }}"@endif @else data-bs-toggle="modal" data-bs-target="{{ $d('nav.next_target', '#') }}"@endif>
            <span class="text-button-sm">{{ $d('nav.next') }}</span>
            @if ($d('nav.next_icon', true))<svg class="icon icon-white icon-sm" aria-hidden="true"><use href="{{ $icon('it-chevron-right') }}"></use></svg>@endif
        </button>
    </nav>
    <div id="alert-message" class="alert alert-success cmp-disclaimer rounded d-none" role="alert">
        <span class="d-inline-block text-uppercase cmp-disclaimer__message">{{ $d('nav.saved_message') }}</span>
    </div>
</div>
