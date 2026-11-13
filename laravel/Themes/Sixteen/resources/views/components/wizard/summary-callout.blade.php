{{-- Avviso "Attenzione" del riepilogo (dati: $d('callout.variant|icon|title|text|emphasis')); assente se manca il testo. --}}
@if (filled($d('callout.text')))
    <div class="callout callout-highlight ps-3 {{ $d('callout.variant', 'warning') }}">
        <div class="callout-title mb-20 d-flex align-items-center">
            <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $icon($d('callout.icon', 'it-horn')) }}"></use></svg>
            <span>{{ $d('callout.title') }}</span>
        </div>
        <p class="titillium text-paragraph">{{ $d('callout.text') }}<span class="d-lg-block"> {{ $d('callout.emphasis') }}</span></p>
    </div>
@endif
