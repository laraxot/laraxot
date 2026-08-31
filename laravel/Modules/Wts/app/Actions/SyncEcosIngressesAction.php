<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Ecos\EcosOdbcConnection;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Sincronizza (in SOLA LETTURA) i "viaggi in ingresso" WTS da ECOS.
 *
 * IMPORTANTE: la lista "Conferimenti" di bbros NON proviene dai movimenti
 * registrati (bsmco/bsmte) ma dalla PROGRAMMAZIONE dei conferimenti attesi
 * (codprog/codprgd): camion annunciati ma non ancora arrivati.
 * Vedi docs/waste/13-query-conferimenti-programmati-wts.md per la mappatura
 * verificata 1:1 sui dati reali ECOS.
 *
 * Formulario, partita, colli e peso reali arrivano invece dal movimento
 * registrato (bsmco/bspart), agganciato alla programmazione tramite
 * rif_programmazione: restano vuoti finche' il viaggio non e' arrivato.
 * Vedi docs/waste/14-query-formulario-partita-colli-wts.md.
 */
final class SyncEcosIngressesAction
{
    public function __construct(
        private readonly EcosOdbcConnection $ecosConnection,
    ) {}

    /**
     * @return array{attempted: bool, imported: int, error: string|null}
     */
    public function execute(?string $receivedDate = null): array
    {
        if (! config('wts.ecos.sync_ingresses')) {
            return ['attempted' => false, 'imported' => 0, 'error' => null];
        }

        try {
            $rows = $this->ecosConnection->select(
                $this->sql(),
                $this->parameters($receivedDate),
            );

            $imported = 0;
            foreach ($rows as $row) {
                $this->syncRow((array) $row);
                $imported++;
            }

            return ['attempted' => true, 'imported' => $imported, 'error' => null];
        } catch (Throwable $exception) {
            Log::warning('WTS ECOS ingress sync failed.', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'attempted' => true,
                'imported' => 0,
                'error' => 'Connessione ECOS non disponibile o query non eseguibile. '.$exception->getMessage(),
            ];
        }
    }

    private function sql(): string
    {
        $configuredLimit = config('wts.ecos.ingress_limit', 80);
        $limit = max(1, min(is_numeric($configuredLimit) ? (int) $configuredLimit : 80, 500));

        return <<<SQL
SELECT TOP ({$limit})
    CONCAT(
        CAST(cp.prog_num AS varchar(20)), '-',
        CAST(d.prgd_prog AS varchar(20))
    ) AS legacy_reference,
    CAST(cp.prog_num AS varchar(20)) AS program_number,
    cp.doc_cod AS document_type,
    d.str_id AS sheet_code,
    pr.imp_ragsoc AS producer_name,
    -- Indirizzo UNITA' LOCALE del produttore (bsimp via imp_cod_pr/imp_uni_pr), per l'etichetta.
    pr.imp_indir AS producer_street,
    pr.imp_civ AS producer_civ,
    pr.imp_cap AS producer_cap,
    pr.imp_citta AS producer_city,
    pr.imp_prov AS producer_prov,
    co.imp_ragsoc AS commercial_company_name,
    tr.imp_ragsoc AS transporter_name,
    s.frs_cod AS hp_codes,
    r.cer2_cod AS waste_code,
    COALESCE(s.str_rif_des, r.rif_des, '') AS production_description,
    -- Descrizione della SCHEDA TECNICA (specifica per produttore/prodotto), diversa dalla
    -- descrizione generica del codice rifiuto (str_rif_des/rif_des).
    s.str_des AS technical_description,
    COALESCE(d.prgd_numero_colli, d.prgd_num_c, 0) AS ecos_units,
    CAST(d.prgd_qta_p AS decimal(12, 2)) AS gross_quantity,
    cp.prog_data_prev_conf AS movement_date,
    m.mco_num_fi AS form_number,
    m.mco_dat_fi AS form_date,
    COALESCE(m.mco_numero_colli, m.mco_num_c) AS movement_units,
    -- Peso da prendere in carico: la QUANTITA' VERIFICATA in impianto (mco_qta_mud,
    -- la "Qta verificata" di bbros), NON il peso dichiarato dal produttore
    -- (mco_qta_p/_pres/_opt). Fallback al dichiarato se la verifica manca ancora.
    CAST(COALESCE(NULLIF(m.mco_qta_mud, 0), m.mco_qta_p) AS decimal(12, 2)) AS movement_quantity,
    m.lit_cod AS label_list_code,
    m.ltc_cod AS label_cycle_code,
    m.stf_cod AS physical_state_code,
    p.part_partita AS batch_number,
    p.part_chiuso AS batch_closed,
    p.ubs_cod AS storage_area_code,
    d.imp_cod_pr,
    d.imp_uni_pr,
    cp.imp_cod_de,
    cp.imp_uni_de,
    cp.imp_cod_tr,
    cp.imp_uni_tr,
    cp.imp_cod_co1,
    cp.imp_uni_co1,
    d.rif_cod,
    d.prgd_prog
FROM codprog cp
INNER JOIN codprgd d
    ON  d.imp_cod_ut = cp.imp_cod_ut
   AND d.imp_uni_ut = cp.imp_uni_ut
   AND d.prog_num   = cp.prog_num
INNER JOIN bsrif r
    ON r.rif_cod = d.rif_cod
LEFT JOIN srstr s
    ON s.str_id = d.str_id
LEFT JOIN bsimp pr
    ON pr.imp_cod = d.imp_cod_pr AND pr.imp_uni = d.imp_uni_pr
LEFT JOIN bsimp tr
    ON tr.imp_cod = cp.imp_cod_tr AND tr.imp_uni = cp.imp_uni_tr
LEFT JOIN bsimp co
    ON co.imp_cod = cp.imp_cod_co1 AND co.imp_uni = cp.imp_uni_co1
LEFT JOIN bsmco m
    ON m.rif_programmazione = CONCAT(
           RTRIM(cp.imp_cod_ut), CHAR(2),
           CAST(cp.imp_uni_ut AS varchar(10)), CHAR(2),
           CAST(cp.prog_num   AS varchar(20)), CHAR(2),
           CAST(d.prgd_prog   AS varchar(20))
       )
LEFT JOIN bspart p
    ON  p.mte_cod_p = m.mte_cod_p
   AND  p.mte_ann_p = m.mte_ann_p
   AND  p.mte_num_p = m.mte_num_p
   AND  p.mco_prog  = m.mco_prog
WHERE cp.imp_cod_de = ?
  AND cp.imp_uni_de = ?
  AND cp.prog_data_prev_conf >= ?
  AND cp.prog_data_prev_conf <= ?
ORDER BY cp.prog_num, d.prgd_prog
SQL;
    }

    /**
     * @return array<int, scalar>
     */
    private function parameters(?string $receivedDate): array
    {
        $date = $this->movementDate($receivedDate);
        $configuredCompanyCode = config('wts.ecos.company_code', 'WTS');
        $configuredUnit = config('wts.ecos.unit', 1);
        $companyCode = is_scalar($configuredCompanyCode) ? (string) $configuredCompanyCode : 'WTS';
        $unit = is_numeric($configuredUnit) ? (int) $configuredUnit : 1;

        return [
            $companyCode,
            $unit,
            $date->startOfDay()->format('Y-m-d H:i:s'),
            $date->endOfDay()->format('Y-m-d H:i:s'),
        ];
    }

    private function movementDate(?string $receivedDate): CarbonImmutable
    {
        $normalizedDate = trim((string) $receivedDate);
        if ($normalizedDate === '') {
            return CarbonImmutable::now();
        }

        try {
            return CarbonImmutable::parse($normalizedDate);
        } catch (Throwable) {
            return CarbonImmutable::now();
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function syncRow(array $row): void
    {
        $sheetCode = $this->stringValue($row['sheet_code'] ?? null, 'ST-ECOS-ND');
        $payload = $this->payload($row);

        /** @var WtsTechnicalSheetSnapshot $snapshot */
        $snapshot = WtsTechnicalSheetSnapshot::query()->updateOrCreate(
            ['sheet_code' => $sheetCode],
            [
                'legacy_system' => 'ecos',
                'producer_name' => $this->stringValue($row['producer_name'] ?? null, 'Produttore ECOS non disponibile'),
                'waste_code' => $this->stringValue($row['waste_code'] ?? null, 'N/D'),
                'hp_codes' => $this->nullableStringValue($row['hp_codes'] ?? null),
                'packaging_description' => null,
                'production_description' => $this->nullableStringValue($row['production_description'] ?? null),
                'note_1' => null,
                'note_2' => null,
                'legacy_payload' => array_merge($payload, [
                    'ecos_units' => $row['ecos_units'] ?? null,
                    'producer_address' => $this->composeProducerAddress($row),
                    'technical_description' => $this->nullableStringValue($row['technical_description'] ?? null),
                    'ecos_imported_at' => now()->toDateTimeString(),
                ]),
            ],
        );

        $legacyReference = $this->stringValue($row['legacy_reference'] ?? null, 'ECOS-ND');

        // Blindatura tracciabilita': un ingresso gia' preso in carico non deve
        // piu' essere sovrascritto dalla sync (formulario, pesi e stato sono
        // stati accettati formalmente dall'operatore in UI-02).
        /** @var WtsInboundRecord|null $existingRecord */
        $existingRecord = WtsInboundRecord::query()->where('legacy_reference', $legacyReference)->first();
        if ($existingRecord !== null && ($existingRecord->taken_charge_at !== null || $existingRecord->status_code === 'taken_charge')) {
            return;
        }

        WtsInboundRecord::query()->updateOrCreate(
            ['legacy_reference' => $legacyReference],
            [
                'form_number' => $this->nullableStringValue($row['form_number'] ?? null),
                'batch_number' => $this->nullableStringValue($row['batch_number'] ?? null),
                'source_company_name' => $this->stringValue($row['producer_name'] ?? null, 'Produttore ECOS non disponibile'),
                'commercial_company_name' => $this->nullableStringValue($row['commercial_company_name'] ?? null),
                'vehicle_reference' => $this->nullableStringValue($row['transporter_name'] ?? null),
                'gross_quantity' => is_numeric($row['gross_quantity'] ?? null) ? (float) $row['gross_quantity'] : 0.0,
                'ecos_units_count' => is_numeric($row['movement_units'] ?? null) ? (int) $row['movement_units'] : null,
                'entry_weight' => is_numeric($row['movement_quantity'] ?? null) ? (float) $row['movement_quantity'] : null,
                // Linea di trattamento (lit_cod: WD/WR) e ciclo di produzione (ltc_cod: COMB/ACCO) da ECOS.
                'treatment_line_code' => $this->nullableStringValue($row['label_list_code'] ?? null),
                'production_cycle_code' => $this->nullableStringValue($row['label_cycle_code'] ?? null),
                'is_weight_closed' => $this->batchClosed($row['batch_closed'] ?? null),
                'status_code' => 'ready_for_labelling',
                'technical_sheet_snapshot_id' => $snapshot->id,
                'received_at' => $row['movement_date'] ?? null,
                'notes' => 'Conferimento programmato importato in sola lettura da ECOS.',
                // Payload del SINGOLO viaggio. La scheda tecnica ne ha uno suo,
                // ma e' condivisa da tutti i conferimenti con lo stesso ST:
                // progressivo, tipo documento, data e colli attesi devono stare
                // qui, altrimenti ogni riga eredita i dati dell'ultimo viaggio
                // sincronizzato per quella scheda.
                'legacy_payload' => $payload,
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    /**
     * Compone l'indirizzo dell'unita' locale del produttore per l'etichetta:
     * "VIA X 54/56, 32044 CITTA' (PROV)". Null se non c'e' nulla.
     *
     * @param  array<string, mixed>  $row
     */
    private function composeProducerAddress(array $row): ?string
    {
        $get = static function (string $key) use ($row): string {
            $value = $row[$key] ?? null;

            return is_scalar($value) ? trim((string) $value) : '';
        };

        $street = $get('producer_street');
        $civ = $get('producer_civ');
        $cap = $get('producer_cap');
        $city = $get('producer_city');
        $prov = $get('producer_prov');

        $streetLine = trim($street.' '.$civ);
        $cityLine = trim($cap.' '.$city);
        if ($prov !== '') {
            $cityLine = trim($cityLine.' ('.$prov.')');
        }

        $address = implode(', ', array_filter(
            [$streetLine, $cityLine],
            static fn (string $part): bool => $part !== '',
        ));

        return $address !== '' ? $address : null;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function payload(array $row): array
    {
        $payload = [];

        foreach ($row as $key => $value) {
            $payload[$key] = $value instanceof DateTimeInterface
                ? $value->format('Y-m-d H:i:s')
                : $value;
        }

        return $payload;
    }

    private function stringValue(mixed $value, string $fallback): string
    {
        $normalized = trim(is_scalar($value) ? (string) $value : '');

        return $normalized === '' ? $fallback : $normalized;
    }

    /**
     * `bspart.part_chiuso` vale 'Y'/'N'; resta null se il viaggio non e' ancora
     * arrivato (nessuna partita registrata), cosi' la UI non mostra uno stato
     * peso che in ECOS non esiste ancora.
     */
    private function batchClosed(mixed $value): ?bool
    {
        $normalized = strtoupper(trim(is_scalar($value) ? (string) $value : ''));

        return $normalized === '' ? null : $normalized === 'Y';
    }

    private function nullableStringValue(mixed $value): ?string
    {
        $normalized = trim(is_scalar($value) ? (string) $value : '');

        return $normalized === '' ? null : $normalized;
    }
}
