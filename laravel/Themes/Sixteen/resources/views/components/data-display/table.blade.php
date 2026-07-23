{{-- Bootstrap Italia Table Component --}}
{{-- 
    Componente tabella conforme alle specifiche Bootstrap Italia
    Supporta ordinamento, paginazione, selezione e accessibilità
--}}
@props([
    'data' => [],
    'columns' => [],
    'sortable' => false,
    'sortBy' => null,
    'sortDirection' => 'asc',
    'selectable' => false,
    'selected' => [],
    'striped' => true,
    'hover' => true,
    'bordered' => false,
    'small' => false,
    'responsive' => true,
    'caption' => null,
    'emptyMessage' => 'Nessun dato disponibile',
    'loading' => false,
    'class' => null,
    'tableClass' => null,
    'headerClass' => null,
    'bodyClass' => null,
    'rowClass' => null,
    'cellClass' => null,
])

@php
$tableId = 'table-' . uniqid();

$tableClasses = [
    'w-full',
    'text-sm',
    'text-left',
    'text-gray-500',
    'dark:text-gray-400',
    $striped ? 'table-striped' : '',
    $hover ? 'table-hover' : '',
    $bordered ? 'border border-gray-200' : '',
    $small ? 'text-xs' : '',
    $tableClass
];

$headerClasses = [
    'text-xs',
    'text-gray-700',
    'uppercase',
    'bg-gray-50',
    'dark:bg-gray-700',
    'dark:text-gray-400',
    $headerClass
];

$bodyClasses = [
    'bg-white',
    'divide-y',
    'divide-gray-200',
    'dark:bg-gray-800',
    'dark:divide-gray-700',
    $bodyClass
];

$rowClasses = [
    'bg-white',
    'border-b',
    'dark:bg-gray-800',
    'dark:border-gray-700',
    'hover:bg-gray-50',
    'dark:hover:bg-gray-600',
    $rowClass
];

$cellClasses = [
    'px-6',
    'py-4',
    'whitespace-nowrap',
    'text-sm',
    'font-medium',
    'text-gray-900',
    'dark:text-white',
    $cellClass
];
@endphp

<div @class(['relative', $class]) x-data="tableManager(@js($data), @js($columns), @js($sortable), @js($selectable))">
    @if($loading)
        <div class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-italia-blue-500"></div>
                <span class="text-sm text-gray-600">Caricamento...</span>
            </div>
        </div>
    @endif

    @if($responsive)
        <div class="overflow-x-auto">
    @endif

    <table @class($tableClasses) id="{{ $tableId }}" role="table">
        @if($caption)
            <caption class="text-left text-sm font-medium text-gray-900 mb-2">
                {{ $caption }}
            </caption>
        @endif

        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                @if($selectable)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input 
                            type="checkbox" 
                            class="rounded border-gray-300 text-italia-blue-600 focus:ring-italia-blue-500"
                            x-model="selectAll"
                            @change="toggleSelectAll()"
                            aria-label="Seleziona tutti"
                        >
                    </th>
                @endif

                @foreach($columns as $column)
                    <th 
                        scope="col" 
                        @class([
                            ...$headerClasses,
                            $column['sortable'] ?? $sortable ? 'cursor-pointer select-none hover:bg-gray-100' : ''
                        ])
                        @if($column['sortable'] ?? $sortable)
                            @click="sort('{{ $column['key'] }}')"
                            @keydown.enter="sort('{{ $column['key'] }}')"
                            @keydown.space.prevent="sort('{{ $column['key'] }}')"
                            tabindex="0"
                            role="button"
                            :aria-sort="getSortDirection('{{ $column['key'] }}')"
                        @endif
                    >
                        <div class="flex items-center space-x-1">
                            <span>{{ $column['label'] ?? $column['key'] }}</span>
                            @if($column['sortable'] ?? $sortable)
                                <div class="flex flex-col">
                                    <svg 
                                        class="w-3 h-3 transition-colors" 
                                        :class="getSortIcon('{{ $column['key'] }}', 'asc')"
                                        fill="currentColor" 
                                        viewBox="0 0 20 20"
                                        aria-hidden="true"
                                    >
                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <svg 
                                        class="w-3 h-3 transition-colors" 
                                        :class="getSortIcon('{{ $column['key'] }}', 'desc')"
                                        fill="currentColor" 
                                        viewBox="0 0 20 20"
                                        aria-hidden="true"
                                    >
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody @class($bodyClasses)>
            @forelse($data as $index => $row)
                <tr @class($rowClasses) :class="{ 'bg-blue-50': selected.includes({{ $index }}) }">
                    @if($selectable)
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input 
                                type="checkbox" 
                                class="rounded border-gray-300 text-italia-blue-600 focus:ring-italia-blue-500"
                                :checked="selected.includes({{ $index }})"
                                @change="toggleSelect({{ $index }})"
                                :aria-label="`Seleziona riga ${index + 1}`"
                            >
                        </td>
                    @endif

                    @foreach($columns as $column)
                        <td @class($cellClasses)>
                            @if(isset($column['component']))
                                {{-- Componente personalizzato --}}
                                <x-dynamic-component 
                                    :component="$column['component']" 
                                    :data="$row"
                                    :column="$column"
                                />
                            @elseif(isset($column['format']))
                                {{-- Formattazione personalizzata --}}
                                @php
                                    $value = data_get($row, $column['key']);
                                    $formatted = match($column['format']) {
                                        'currency' => '€ ' . number_format($value, 2),
                                        'date' => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '',
                                        'datetime' => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : '',
                                        'number' => number_format($value),
                                        'percentage' => number_format($value, 1) . '%',
                                        'boolean' => $value ? 'Sì' : 'No',
                                        'badge' => $value ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">' . $value . '</span>' : '',
                                        default => $value
                                    };
                                @endphp
                                {!! $formatted !!}
                            @else
                                {{-- Valore semplice --}}
                                {{ data_get($row, $column['key']) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td 
                        colspan="{{ count($columns) + ($selectable ? 1 : 0) }}" 
                        class="px-6 py-12 text-center text-sm text-gray-500"
                    >
                        <div class="flex flex-col items-center space-y-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>{{ $emptyMessage }}</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($responsive)
        </div>
    @endif

    @if($selectable && count($selected) > 0)
        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-700">
                    {{ count($selected) }} elemento/i selezionato/i
                </span>
                <div class="flex space-x-2">
                    <button 
                        type="button"
                        class="text-sm text-blue-600 hover:text-blue-800"
                        @click="clearSelection()"
                    >
                        Deseleziona tutto
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function tableManager(data, columns, sortable, selectable) {
    return {
        data: data,
        columns: columns,
        sortable: sortable,
        selectable: selectable,
        sortBy: null,
        sortDirection: 'asc',
        selected: [],
        selectAll: false,

        sort(key) {
            if (!this.sortable) return;
            
            if (this.sortBy === key) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = key;
                this.sortDirection = 'asc';
            }
            
            this.data.sort((a, b) => {
                const aVal = this.getNestedValue(a, key);
                const bVal = this.getNestedValue(b, key);
                
                if (aVal < bVal) return this.sortDirection === 'asc' ? -1 : 1;
                if (aVal > bVal) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        },

        getNestedValue(obj, path) {
            return path.split('.').reduce((o, p) => o && o[p], obj);
        },

        getSortDirection(key) {
            if (this.sortBy !== key) return 'none';
            return this.sortDirection === 'asc' ? 'ascending' : 'descending';
        },

        getSortIcon(key, direction) {
            if (this.sortBy !== key) return 'text-gray-300';
            if (this.sortDirection === direction) return 'text-italia-blue-600';
            return 'text-gray-300';
        },

        toggleSelect(index) {
            const idx = this.selected.indexOf(index);
            if (idx > -1) {
                this.selected.splice(idx, 1);
            } else {
                this.selected.push(index);
            }
            this.updateSelectAll();
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selected = this.data.map((_, index) => index);
            } else {
                this.selected = [];
            }
        },

        clearSelection() {
            this.selected = [];
            this.selectAll = false;
        },

        updateSelectAll() {
            this.selectAll = this.selected.length === this.data.length;
        }
    };
}
</script>
@endpush

{{-- 
Utilizzo:

<!-- Tabella base -->
<x-table 
    :data="$users"
    :columns="[
        ['key' => 'name', 'label' => 'Nome'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'created_at', 'label' => 'Data registrazione', 'format' => 'date']
    ]"
    caption="Elenco utenti"
/>

<!-- Tabella con ordinamento -->
<x-table 
    :data="$products"
    :columns="[
        ['key' => 'name', 'label' => 'Prodotto', 'sortable' => true],
        ['key' => 'price', 'label' => 'Prezzo', 'format' => 'currency', 'sortable' => true],
        ['key' => 'stock', 'label' => 'Scorte', 'sortable' => true]
    ]"
    sortable
/>

<!-- Tabella con selezione -->
<x-table 
    :data="$orders"
    :columns="[
        ['key' => 'id', 'label' => 'ID'],
        ['key' => 'customer', 'label' => 'Cliente'],
        ['key' => 'total', 'label' => 'Totale', 'format' => 'currency'],
        ['key' => 'status', 'label' => 'Stato', 'format' => 'badge']
    ]"
    selectable
/>

<!-- Tabella con componenti personalizzati -->
<x-table 
    :data="$users"
    :columns="[
        ['key' => 'name', 'label' => 'Nome'],
        ['key' => 'avatar', 'label' => 'Avatar', 'component' => 'user-avatar'],
        ['key' => 'actions', 'label' => 'Azioni', 'component' => 'user-actions']
    ]"
/>

<!-- Tabella responsive -->
<x-table 
    :data="$data"
    :columns="$columns"
    responsive
    striped
    hover
    bordered
/>

<!-- Tabella con caricamento -->
<x-table 
    :data="$data"
    :columns="$columns"
    :loading="$isLoading"
/>

<!-- Tabella con messaggio vuoto personalizzato -->
<x-table 
    :data="[]"
    :columns="$columns"
    empty-message="Nessun risultato trovato"
/>

Formati disponibili:
- currency: formatta come valuta (€ 1.234,56)
- date: formatta come data (dd/mm/yyyy)
- datetime: formatta come data e ora (dd/mm/yyyy hh:mm)
- number: formatta come numero (1.234)
- percentage: formatta come percentuale (12.5%)
- boolean: formatta come Sì/No
- badge: formatta come badge colorato

Opzioni di stile:
- striped: righe alternate colorate
- hover: effetto hover sulle righe
- bordered: bordi intorno alla tabella
- small: dimensioni ridotte
- responsive: scroll orizzontale su mobile

Accessibilità:
- Supporto screen reader
- ARIA attributes completi
- Navigazione da tastiera
- Ordinamento accessibile
- Selezione accessibile
--}}
