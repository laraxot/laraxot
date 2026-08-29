<x-filament-panels::page>
    @php($split = [\App\Filament\Timber\Pages\TimberTraceabilityRegisterPage::class, 'splitDate'])

    <div class="flex justify-end">
        <x-filament::button wire:click="exportCsv" icon="heroicon-o-arrow-down-tray">
            Esporta Excel (CSV)
        </x-filament::button>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow dark:border-slate-700 dark:bg-slate-800">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-100 text-center font-semibold dark:bg-slate-900">
                    <th colspan="5" class="border-r border-slate-200 px-2 py-2 dark:border-slate-700">Ingresso materia prima</th>
                    <th colspan="3" class="border-r border-slate-200 px-2 py-2 dark:border-slate-700">Inizio lavorazione</th>
                    <th colspan="5" class="px-2 py-2">Confezionamento</th>
                </tr>
                <tr class="bg-slate-50 text-left text-xs text-slate-500 dark:bg-slate-900/60">
                    <th class="px-2 py-1">Nome</th>
                    <th class="px-2 py-1">Cod. forn.</th>
                    <th class="px-2 py-1">Anno</th>
                    <th class="px-2 py-1">DDT/FT</th>
                    <th class="border-r border-slate-200 px-2 py-1 dark:border-slate-700">Quintali</th>
                    <th class="px-2 py-1">Mese</th>
                    <th class="px-2 py-1">Anno</th>
                    <th class="border-r border-slate-200 px-2 py-1 dark:border-slate-700">Giorno</th>
                    <th class="px-2 py-1">Cod. articolo</th>
                    <th class="px-2 py-1">Mese</th>
                    <th class="px-2 py-1">Anno</th>
                    <th class="px-2 py-1">Giorno</th>
                    <th class="px-2 py-1">Bancali</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    @php([$lm, $ly, $ld] = $split($row->workStartDate))
                    @php([$cm, $cy, $cd] = $split($row->packagingDate))
                    <tr class="border-t border-slate-100 dark:border-slate-700/60">
                        <td class="px-2 py-1">{{ $row->supplierName }}</td>
                        <td class="px-2 py-1">{{ $row->supplierCode }}</td>
                        <td class="px-2 py-1">{{ $row->ingressYear }}</td>
                        <td class="px-2 py-1">{{ $row->ddtNumber }}</td>
                        <td class="border-r border-slate-100 px-2 py-1 text-right dark:border-slate-700/60">{{ number_format($row->quantity, 2, ',', '.') }}</td>
                        <td class="px-2 py-1">{{ $lm }}</td>
                        <td class="px-2 py-1">{{ $ly }}</td>
                        <td class="border-r border-slate-100 px-2 py-1 dark:border-slate-700/60">{{ $ld }}</td>
                        <td class="px-2 py-1 font-medium">{{ $row->articleCode }}</td>
                        <td class="px-2 py-1">{{ $cm }}</td>
                        <td class="px-2 py-1">{{ $cy }}</td>
                        <td class="px-2 py-1">{{ $cd }}</td>
                        <td class="px-2 py-1 text-right">{{ $row->bancali > 0 ? $row->bancali : '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="13" class="px-2 py-4 text-center text-slate-400">Nessun dato di tracciabilita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
