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

    @php($money = fn (\Modules\Quotation\Domain\ValueObjects\Money $m): string => number_format($m->asFloat(), 2, ',', '.'))

    <div class="grid gap-6 md:grid-cols-2">
        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <h3 class="mb-3 text-base font-semibold">Registro IVA vendite (IVA a debito)</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="py-1">Aliquota</th>
                        <th class="py-1 text-right">Imponibile</th>
                        <th class="py-1 text-right">IVA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($liquidation->salesRows as $row)
                        <tr class="border-t border-gray-100 dark:border-gray-700">
                            <td class="py-1">{{ $row->rate }}%</td>
                            <td class="py-1 text-right">{{ $money($row->taxable) }}</td>
                            <td class="py-1 text-right">{{ $money($row->vat) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-2 text-gray-400">Nessuna vendita nel periodo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <h3 class="mb-3 text-base font-semibold">Registro IVA acquisti (IVA a credito)</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="py-1">Aliquota</th>
                        <th class="py-1 text-right">Imponibile</th>
                        <th class="py-1 text-right">IVA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($liquidation->purchaseRows as $row)
                        <tr class="border-t border-gray-100 dark:border-gray-700">
                            <td class="py-1">{{ $row->rate }}%</td>
                            <td class="py-1 text-right">{{ $money($row->taxable) }}</td>
                            <td class="py-1 text-right">{{ $money($row->vat) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-2 text-gray-400">Nessun acquisto nel periodo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="fi-section rounded-xl bg-white p-4 shadow dark:bg-gray-800">
        <h3 class="mb-3 text-base font-semibold">Liquidazione IVA del periodo</h3>
        <dl class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">IVA a debito</dt>
                <dd class="text-lg font-semibold">{{ $money($liquidation->totalDebit) }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">IVA a credito</dt>
                <dd class="text-lg font-semibold">{{ $money($liquidation->totalCredit) }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">{{ $liquidation->isPayable() ? 'IVA a debito (da versare)' : 'Credito IVA' }}</dt>
                <dd class="text-lg font-semibold {{ $liquidation->isPayable() ? 'text-danger-600' : 'text-success-600' }}">
                    {{ $money($liquidation->balance) }}
                </dd>
            </div>
        </dl>
    </div>
</x-filament-panels::page>
