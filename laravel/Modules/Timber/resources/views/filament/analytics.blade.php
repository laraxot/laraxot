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

    @php($money = fn (\Modules\Quotation\DTO\Money $m): string => '€ '.number_format($m->asFloat(), 2, ',', '.'))

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-sm text-gray-500">Vendite</div>
            <div class="text-2xl font-bold text-success-600">{{ $money($summary->salesTotal) }}</div>
        </div>
        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-sm text-gray-500">Acquisti</div>
            <div class="text-2xl font-bold text-warning-600">{{ $money($summary->purchasesTotal) }}</div>
        </div>
        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-sm text-gray-500">Margine (vendite − acquisti)</div>
            <div class="text-2xl font-bold">{{ $money($summary->marginProxy()) }}</div>
        </div>
        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-sm text-gray-500">Da incassare</div>
            <div class="text-2xl font-bold text-danger-600">{{ $money($summary->outstandingTotal) }}</div>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-sm text-gray-500">Flussi di cassa</div>
            <dl class="mt-2 space-y-1 text-sm">
                <div class="flex justify-between"><dt>Incassato nel periodo</dt><dd class="font-semibold">{{ $money($summary->collectedTotal) }}</dd></div>
                <div class="flex justify-between"><dt>Ancora da incassare</dt><dd class="font-semibold">{{ $money($summary->outstandingTotal) }}</dd></div>
            </dl>
        </div>

        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <h3 class="mb-2 text-base font-semibold">Migliori clienti per fatturato</h3>
            <table class="w-full text-sm">
                <tbody>
                    @forelse ($summary->topCustomers as $row)
                        <tr class="border-t border-gray-100 dark:border-gray-700">
                            <td class="py-1">{{ $row->customerName }}</td>
                            <td class="py-1 text-right font-semibold">{{ $money($row->total) }}</td>
                        </tr>
                    @empty
                        <tr><td class="py-2 text-gray-400">Nessuna vendita nel periodo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
