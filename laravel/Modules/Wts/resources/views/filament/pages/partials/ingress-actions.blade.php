@php
    /** @var \Modules\Wts\Models\WtsInboundRecord $ingress */
    /** @var \Modules\Wts\Models\WtsInboundLot|null $lot */
    $compact = $compact ?? false;
    $primaryClass = $compact
        ? 'inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-blue-700 px-2.5 py-1 text-[0.7rem] font-semibold text-white transition hover:bg-blue-600'
        : 'inline-flex w-full items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600';
    $disabledClass = $compact
        ? 'inline-flex cursor-not-allowed items-center justify-center whitespace-nowrap rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 text-[0.7rem] font-medium text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400'
        : 'inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-medium text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400';
    $secondaryClass = $compact
        ? 'inline-flex items-center justify-center whitespace-nowrap rounded-lg border border-slate-300 px-2.5 py-1 text-[0.7rem] font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800'
        : 'inline-flex w-full items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800';
@endphp

<div class="flex {{ $compact ? 'flex-wrap items-center gap-1.5' : 'flex-col gap-2' }}">
    @if ($lot === null)
        {{-- Finche' ECOS non espone formulario e quantita' il camion non e' --}}
        {{-- arrivato: la presa in carico non e' confermabile (RB-10), quindi --}}
        {{-- il tasto resta bloccato invece di dirottare l'operatore altrove. --}}
        @if (\Modules\Wts\Datas\WtsTakeChargeDefaults::fromRecord($ingress)->isReadyForTakeCharge())
            <button type="button"
                    wire:click="takeChargeAndOpenLot({{ $ingress->id }})"
                    wire:loading.attr="disabled"
                    wire:target="takeChargeAndOpenLot({{ $ingress->id }})"
                    class="{{ $primaryClass }}">
                Prendi in carico
            </button>
        @else
            <span class="{{ $disabledClass }}" title="Il formulario e la quantita arrivano da ECOS quando il camion e stato registrato e pesato.">
                In attesa di arrivo
            </span>
        @endif
    @elseif ($unitsCount === 0)
        <a href="{{ \Modules\Wts\Filament\Pages\WtsLotDetailPage::getUrl(panel: 'wts', parameters: ['lot' => $lot->id]) }}"
           class="{{ $primaryClass }}">
            Genera colli
        </a>
    @else
        <a href="{{ \Modules\Wts\Filament\Pages\WtsLotDetailPage::getUrl(panel: 'wts', parameters: ['lot' => $lot->id]) }}"
           class="{{ $primaryClass }}">
            Etichette ({{ $unitsCount }})
        </a>
        <a href="{{ \Modules\Wts\Filament\Pages\WtsWorkQueuePage::getUrl(panel: 'wts') }}"
           class="{{ $secondaryClass }}">
            Giacenza
        </a>
    @endif
</div>
