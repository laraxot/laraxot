@php
    $closedAt = data_get($article, 'closed_at');
    $resolvedAt = data_get($article, 'resolved_at');
    $eventEndsAt = data_get($article, 'ends_at') ?? data_get($article, 'event_end_date');
    $liquidity = data_get($article, 'liquidity');
    $positionsCount = (int) (data_get($article, 'count_credit') ?? 0);
    $creditsVolume = (float) (data_get($article, 'sum_credit') ?? 0);

    $ordersCount = 0;
    if (($article->exists ?? false) && method_exists($article, 'orders')) {
        $ordersCount = (int) $article->orders()->count();
    }

    $statusLabel = 'Archivato';
    $statusTone = 'slate';
    if ($resolvedAt !== null) {
        $statusLabel = 'Risolto';
        $statusTone = 'emerald';
    } elseif ($closedAt !== null && $closedAt->isPast()) {
        $statusLabel = 'In risoluzione';
        $statusTone = 'amber';
    } elseif ($closedAt !== null && $closedAt->isFuture()) {
        $statusLabel = 'Aperto';
        $statusTone = 'sky';
    }
@endphp

<div class="mb-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 bg-gradient-to-r from-slate-50 to-blue-50 px-5 py-4">
        <div class="flex items-center justify-between gap-3">
            <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                <x-filament::icon icon="heroicon-m-signal" class="h-5 w-5 text-sky-600" />
                Stato del mercato
            </h3>
            <span class="inline-flex items-center gap-1 rounded-full bg-{{ $statusTone }}-50 px-2.5 py-1 text-xs font-medium text-{{ $statusTone }}-700 ring-1 ring-inset ring-{{ $statusTone }}-200">
                <x-filament::icon icon="heroicon-m-bolt" class="h-3.5 w-3.5" />
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    <div class="space-y-4 p-5">
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                    <x-filament::icon icon="heroicon-m-user-group" class="h-4 w-4" />
                    Posizioni
                </div>
                <p class="mt-2 text-lg font-semibold text-gray-900">{{ number_format($positionsCount) }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                    <x-filament::icon icon="heroicon-m-banknotes" class="h-4 w-4" />
                    Volume
                </div>
                <p class="mt-2 text-lg font-semibold text-gray-900">{{ number_format($creditsVolume, 0, ',', '.') }} ø</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                    <x-filament::icon icon="heroicon-m-arrows-right-left" class="h-4 w-4" />
                    Liquidita'
                </div>
                <p class="mt-2 text-lg font-semibold text-gray-900">
                    {{ $liquidity !== null ? number_format((float) $liquidity, 0, ',', '.') . ' ø' : 'n/d' }}
                </p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                    <x-filament::icon icon="heroicon-m-queue-list" class="h-4 w-4" />
                    Ordini
                </div>
                <p class="mt-2 text-lg font-semibold text-gray-900">{{ number_format($ordersCount) }}</p>
            </div>
        </div>

        <div class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-start gap-3">
                <x-filament::icon icon="heroicon-m-clock" class="mt-0.5 h-4 w-4 text-slate-500" />
                <div>
                    <p class="text-sm font-medium text-slate-900">Chiusura trading</p>
                    <p class="text-sm text-slate-600">
                        {{ $closedAt ? $closedAt->translatedFormat('d M Y H:i') : 'non impostata' }}
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-filament::icon icon="heroicon-m-flag" class="mt-0.5 h-4 w-4 text-slate-500" />
                <div>
                    <p class="text-sm font-medium text-slate-900">Fine evento</p>
                    <p class="text-sm text-slate-600">
                        {{ $eventEndsAt ? $eventEndsAt->translatedFormat('d M Y H:i') : 'non impostata' }}
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-filament::icon icon="heroicon-m-check-badge" class="mt-0.5 h-4 w-4 text-slate-500" />
                <div>
                    <p class="text-sm font-medium text-slate-900">Risoluzione finale</p>
                    <p class="text-sm text-slate-600">
                        {{ $resolvedAt ? $resolvedAt->translatedFormat('d M Y H:i') : 'non ancora registrata' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
