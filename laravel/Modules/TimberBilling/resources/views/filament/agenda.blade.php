<x-filament-panels::page>
    <form wire:submit.prevent="$refresh" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium">Dal</label>
            <input type="date" wire:model="fromDate" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900" />
        </div>
        <div>
            <label class="block text-sm font-medium">Al</label>
            <input type="date" wire:model="toDate" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900" />
        </div>
        <x-filament::button type="submit">Aggiorna</x-filament::button>
    </form>

    <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="py-1">Data</th>
                    <th class="py-1">Tipo</th>
                    <th class="py-1">Descrizione</th>
                    <th class="py-1 text-right">Importo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr class="border-t border-gray-100 dark:border-gray-700">
                        <td class="py-1 whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($event->date)->format('d/m/Y') }}</td>
                        <td class="py-1">
                            <span class="rounded px-2 py-0.5 text-xs
                                @class([
                                    'bg-success-100 text-success-700' => $event->type === 'Vendita',
                                    'bg-warning-100 text-warning-700' => str_contains($event->type, 'Scadenz') || $event->type === 'Pagamento',
                                    'bg-gray-100 text-gray-700' => $event->type === 'Documento',
                                ])">
                                {{ $event->type }}
                            </span>
                        </td>
                        <td class="py-1">{{ $event->title }}</td>
                        <td class="py-1 text-right">{{ $event->amount ? '€ '.number_format($event->amount->asFloat(), 2, ',', '.') : '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-3 text-gray-400">Nessun evento nel periodo selezionato.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
