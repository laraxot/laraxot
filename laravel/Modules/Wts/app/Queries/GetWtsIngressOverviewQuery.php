<?php

declare(strict_types=1);

namespace Modules\Wts\Queries;

use Modules\Wts\Actions\SyncEcosIngressesAction;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Support\WtsLegacyPayload;
use Illuminate\Database\Eloquent\Collection;

final class GetWtsIngressOverviewQuery
{
    /** @var array{attempted: bool, imported: int, error: string|null} */
    private array $lastEcosSync = ['attempted' => false, 'imported' => 0, 'error' => null];

    public function __construct(
        private readonly SyncEcosIngressesAction $syncEcosIngresses,
    ) {}

    /**
     * @return Collection<int, WtsInboundRecord>
     */
    public function execute(?string $search = null, ?string $receivedDate = null): Collection
    {
        $this->lastEcosSync = $this->syncEcosIngresses->execute($receivedDate);

        /** @var Collection<int, WtsInboundRecord> $records */
        $records = WtsInboundRecord::query()
            ->with(['technicalSheetSnapshot', 'lots.units'])
            ->get();

        $normalizedReceivedDate = trim((string) $receivedDate);
        $normalizedSearch = trim((string) $search);

        return $records
            ->filter(fn (WtsInboundRecord $record): bool => ! $this->isWtsSource($record->source_company_name))
            ->filter(fn (WtsInboundRecord $record): bool => ! $this->isWtsSource($record->technicalSheetSnapshot?->producer_name))
            ->filter(fn (WtsInboundRecord $record): bool => $this->matchesSearch($record, $normalizedSearch))
            ->filter(fn (WtsInboundRecord $record): bool => $this->shouldIncludeRecord($record, $normalizedReceivedDate))
            ->sortByDesc(fn (WtsInboundRecord $record): int => $this->movementDateTimestamp($record))
            ->values();
    }

    /**
     * @param  Collection<int, WtsInboundRecord>  $ingresses
     * @return array{label: string, description: string, class: string}
     */
    public function dataSourceStatus(Collection $ingresses): array
    {
        $hasEcosRows = $ingresses->contains(
            fn (WtsInboundRecord $record): bool => $record->technicalSheetSnapshot?->legacy_system === 'ecos',
        );

        // Se l'ultima sync e' fallita ma restiamo con righe ECOS gia' salvate,
        // NON mostrare il verde rassicurante: sono dati vecchi. Prima questo
        // caso mostrava "DATI REALI ECOS" nascondendo il fallimento, e l'Aggiorna
        // sembrava funzionare senza aggiornare nulla.
        if ($hasEcosRows && $this->lastEcosSync['error'] !== null) {
            return [
                'label' => 'DATI ECOS NON AGGIORNATI',
                'description' => 'Ultima sincronizzazione ECOS fallita: '.$this->lastEcosSync['error'].' Sono mostrati gli ultimi dati sincronizzati: potrebbero non essere aggiornati (formulario, peso e colli potrebbero mancare).',
                'class' => 'border-amber-300 bg-amber-50 text-amber-900 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-100',
            ];
        }

        if ($hasEcosRows) {
            return [
                'label' => 'DATI REALI ECOS',
                'description' => 'Le righe visibili arrivano dalla query SQL Server ECOS e sono sincronizzate in sola lettura.',
                'class' => 'border-emerald-300 bg-emerald-50 text-emerald-900 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-100',
            ];
        }

        if ($this->lastEcosSync['error'] !== null) {
            return [
                'label' => 'DATI DEMO - ECOS NON RAGGIUNGIBILE',
                'description' => $this->lastEcosSync['error'].' Sono ancora mostrati i dati demo/fallback del mockup.',
                'class' => 'border-rose-300 bg-rose-50 text-rose-900 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-100',
            ];
        }

        if ($this->lastEcosSync['attempted']) {
            return [
                'label' => 'DATI DEMO - NESSUN INGRESSO ECOS',
                'description' => 'La query ECOS e stata eseguita, ma non ha restituito ingressi per i filtri correnti.',
                'class' => 'border-rose-300 bg-rose-50 text-rose-900 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-100',
            ];
        }

        return [
            'label' => 'DATI DEMO',
            'description' => 'La sincronizzazione ECOS non e abilitata: il mockup usa dati locali o righe demo.',
            'class' => 'border-rose-300 bg-rose-50 text-rose-900 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-100',
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function extractLegacyDocumentType(array $payload): ?string
    {
        $documentKeys = config('wts.legacy_ingress_document_keys', []);
        if (! is_array($documentKeys)) {
            return null;
        }

        foreach ($documentKeys as $key) {
            if (! is_string($key)) {
                continue;
            }

            $value = data_get($payload, $key);
            if (is_scalar($value) && trim((string) $value) !== '') {
                return strtoupper(trim((string) $value));
            }
        }

        return null;
    }

    private function shouldIncludeRecord(WtsInboundRecord $record, ?string $receivedDate = null): bool
    {
        // La data del conferimento va letta SOLO dal viaggio (payload del record
        // o received_at), mai dalla scheda tecnica: quella e' condivisa da tutti
        // i conferimenti con lo stesso ST, quindi ogni riga finirebbe nel giorno
        // dell'ultimo viaggio sincronizzato per quella scheda e comparirebbero
        // righe di altre giornate (e lo stesso viaggio piu' volte).
        if (! WtsLegacyPayload::matchesDate($this->recordPayload($record), $record->received_at, $receivedDate)) {
            return false;
        }

        $normalizedPayload = $this->payloadFor($record);

        if ($normalizedPayload === null) {
            return true;
        }

        if ($this->isInternalWtsSourceMovement($normalizedPayload)) {
            return false;
        }

        $documentType = $this->extractLegacyDocumentType($normalizedPayload);
        if ($documentType === null) {
            return true;
        }

        /** @var array<int, scalar> $prefixes */
        $prefixes = is_array(config('wts.legacy_ingress_document_prefixes', ['ING', 'INV']))
            ? array_values(array_filter(
                config('wts.legacy_ingress_document_prefixes', ['ING', 'INV']),
                static fn (mixed $prefix): bool => is_scalar($prefix),
            ))
            : ['ING', 'INV'];

        foreach ($prefixes as $prefix) {
            $normalizedPrefix = strtoupper(trim((string) $prefix));
            if ($normalizedPrefix !== '' && str_starts_with($documentType, $normalizedPrefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function isInternalWtsSourceMovement(array $payload): bool
    {
        $sourceCompany = data_get($payload, 'imp_cod_pr', '');
        $sourceCompanyCode = is_scalar($sourceCompany) ? strtoupper(trim((string) $sourceCompany)) : '';
        $sourceUnit = data_get($payload, 'imp_uni_pr');

        return $sourceCompanyCode === 'WTS'
            && is_scalar($sourceUnit)
            && (int) $sourceUnit === 99;
    }

    private function movementDateTimestamp(WtsInboundRecord $record): int
    {
        $movementDate = WtsLegacyPayload::movementDate(
            $this->recordPayload($record),
            $record->received_at,
        );

        return $movementDate?->getTimestamp() ?? 0;
    }

    /**
     * Payload ECOS del singolo viaggio.
     *
     * @return array<string, mixed>|null
     */
    private function recordPayload(WtsInboundRecord $record): ?array
    {
        $payload = $record->legacy_payload;

        return is_array($payload) ? $payload : null;
    }

    /**
     * Payload del viaggio, con ripiego sulla scheda tecnica per i record
     * sincronizzati prima che il payload venisse salvato sul singolo viaggio.
     *
     * @return array<string, mixed>|null
     */
    private function payloadFor(WtsInboundRecord $record): ?array
    {
        $payload = $this->recordPayload($record);
        if ($payload !== null) {
            return $payload;
        }

        $sheetPayload = $record->technicalSheetSnapshot?->legacy_payload;

        return is_array($sheetPayload) ? $sheetPayload : null;
    }

    private function isWtsSource(?string $value): bool
    {
        return $value !== null && str_contains(mb_strtolower($value), 'waste treatment solution');
    }

    private function matchesSearch(WtsInboundRecord $record, string $search): bool
    {
        if ($search === '') {
            return true;
        }

        $sheet = $record->technicalSheetSnapshot;

        return $this->contains($record->form_number, $search)
            || $this->contains($record->vehicle_reference, $search)
            || $this->contains($record->source_company_name, $search)
            || $this->contains($sheet?->sheet_code, $search)
            || $this->contains($sheet?->producer_name, $search)
            || $this->contains($sheet?->waste_code, $search)
            || $this->contains($sheet?->production_description, $search)
            || $this->contains($sheet?->packaging_description, $search);
    }

    private function contains(?string $haystack, string $needle): bool
    {
        return $haystack !== null && str_contains(mb_strtolower($haystack), mb_strtolower($needle));
    }
}
