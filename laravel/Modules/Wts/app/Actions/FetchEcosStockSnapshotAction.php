<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Ecos\EcosOdbcConnection;
use Modules\Wts\Datas\WtsInventoryRow;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;
use Throwable;

final class FetchEcosStockSnapshotAction
{
    public function __construct(
        private readonly EcosOdbcConnection $ecosConnection,
    ) {}

    /**
     * @return array{rows: list<WtsInventoryRow>, error: string|null}
     */
    public function execute(?string $referenceDate = null): array
    {
        try {
            $rows = [];

            foreach ($this->ecosConnection->select($this->sql(), $this->parameters($referenceDate)) as $row) {
                $mapped = $this->mapRow((array) $row);
                if ($mapped instanceof WtsInventoryRow) {
                    $rows[] = $mapped;
                }
            }

            return ['rows' => $rows, 'error' => null];
        } catch (Throwable $exception) {
            Log::warning('WTS ECOS stock snapshot failed.', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'rows' => [],
                'error' => 'Connessione ECOS non disponibile o query giacenza non eseguibile. '.$exception->getMessage(),
            ];
        }
    }

    private function sql(): string
    {
        // Nessun limite di default: la giacenza deve essere completa, altrimenti
        // il totale stampato sarebbe troncato e inutilizzabile per i controlli
        // sui limiti giornalieri. Un limite si applica solo se configurato > 0.
        $configuredLimit = config('wts.ecos.stock_limit', 0);
        $limit = is_numeric($configuredLimit) ? (int) $configuredLimit : 0;
        $top = $limit > 0 ? "TOP ({$limit})" : '';

        return <<<SQL
SELECT {$top}
    CAST(bspart.part_partita AS varchar(50)) AS batch_code,
    CAST(bsmco.mco_prog AS varchar(20)) AS unit_sub_code,
    bsmco.str_id AS sheet_code,
    COALESCE(srstr.str_rif_des, '') AS sheet_description,
    COALESCE(srstr.frs_cod, '') AS hp_codes,
    CAST(bsmco.stf_cod AS varchar(20)) AS physical_state_code,
    COALESCE(bsstf.stf_des, '') AS physical_state_label,
    COALESCE(bsmco.osr_cod, '') AS treatment_code,
    COALESCE(bsosr.osr_des, '') AS treatment_label,
    bsrif.cer2_cod + COALESCE(' ' + bscer2.cer2_class, '') AS waste_code,
    bspart.part_qta_p + COALESCE(c.p, 0) AS weight_kg,
    bspart.part_qta_v + COALESCE(c.v, 0) AS volume,
    CAST(bspart.part_qta_n + COALESCE(c.n, 0) AS int) AS colli_count,
    CASE
        WHEN bsmco.mco_tipomov <> 'R' AND bsmco.mco_tipomov <> 'O'
        THEN stubs.ubs_cod
        ELSE stubs_r.ubs_cod
    END AS zone_code,
    CASE
        WHEN bsmco.mco_tipomov <> 'R' AND bsmco.mco_tipomov <> 'O'
        THEN stubs.ubs_des
        ELSE stubs_r.ubs_des
    END AS zone_label,
    COALESCE(srstr.str_rif_des, bsrif.rif_des, '') AS production_description,
    COALESCE(bsdri.dri_rif_des, bsrif.rif_des, '') AS packaging_description,
    srstrext_1.campo_stringa AS note_1,
    srstrext_2.campo_stringa AS note_2,
    -- Numero formulario REALE (bbros "N. Formulario"), non la chiave interna del
    -- movimento mte_cod/ann/num: quella dava un numero errato nella stampa giacenza.
    CAST(bsmco.mco_num_fi AS varchar(50)) AS form_reference,
    -- Ragione sociale del PRODUTTORE (bsimp via bspart.imp_cod/imp_uni), NON l'indirizzo.
    bsimp.imp_ragsoc AS producer_name,
    -- Linea di trattamento (lit_cod: WD/WR) e ciclo di produzione (ltc_cod: COMB/ACCO).
    bsmco.lit_cod AS treatment_line_code,
    bsmco.ltc_cod AS production_cycle_code
FROM bsmte
INNER JOIN bsmco
    ON bsmco.mte_cod_p = bsmte.mte_cod_p
   AND bsmco.mte_ann_p = bsmte.mte_ann_p
   AND bsmco.mte_num_p = bsmte.mte_num_p
INNER JOIN bsstf
    ON bsstf.stf_cod = bsmco.stf_cod
LEFT JOIN srstr
    ON srstr.str_id = bsmco.str_id
LEFT JOIN bsosr
    ON bsosr.osr_cod = bsmco.osr_cod
INNER JOIN bslit
    ON bsmco.lit_cod = bslit.lit_cod
INNER JOIN bspart
    ON bspart.mte_cod_p = bsmco.mte_cod_p
   AND bspart.mte_ann_p = bsmco.mte_ann_p
   AND bspart.mte_num_p = bsmco.mte_num_p
   AND bspart.mco_prog = bsmco.mco_prog
INNER JOIN bsimp
    ON bspart.imp_cod = bsimp.imp_cod
   AND bspart.imp_uni = bsimp.imp_uni
INNER JOIN bsrif
    ON bsrif.rif_cod = bsmco.rif_cod
INNER JOIN bscer2
    ON bsrif.cer2_cod = bscer2.cer2_cod
LEFT JOIN stubs
    ON bsmco.imp_cod_de = stubs.imp_cod
   AND bsmco.imp_uni_de = stubs.imp_uni
   AND bsmco.mco_ubi_d = stubs.ubs_cod
LEFT JOIN stubs stubs_r
    ON bsmco.imp_cod_pr = stubs_r.imp_cod
   AND bsmco.imp_uni_pr = stubs_r.imp_uni
   AND bsmco.mco_ubi_o = stubs_r.ubs_cod
LEFT JOIN bsdri
    ON bsrif.rif_cod = bsdri.rif_cod
   AND ? = bsdri.imp_cod
   AND ? = bsdri.imp_uni
LEFT JOIN tscaext tscaext_1
    ON tscaext_1.caext_tab_cod = 'srstr'
   AND tscaext_1.caext_nome_campo = 'wts_giacenza_nota_1'
LEFT JOIN srstrext srstrext_1
    ON bsmco.str_id = srstrext_1.str_id
   AND srstrext_1.id_caext = tscaext_1.id
LEFT JOIN tscaext tscaext_2
    ON tscaext_2.caext_tab_cod = 'srstr'
   AND tscaext_2.caext_nome_campo = 'wts_giacenza_nota_2'
LEFT JOIN srstrext srstrext_2
    ON bsmco.str_id = srstrext_2.str_id
   AND srstrext_2.id_caext = tscaext_2.id
CROSS APPLY (
    SELECT
        SUM(s.col_qta_p) AS p,
        SUM(s.col_qta_v) AS v,
        SUM(s.col_qta_n) AS n
    FROM bscol s
    INNER JOIN bsmco r
        ON r.mte_cod_p = s.mte_cod_p
       AND r.mte_ann_p = s.mte_ann_p
       AND r.mte_num_p = s.mte_num_p
       AND r.mco_prog = s.mco_prog
    INNER JOIN bsmte b
        ON b.mte_cod_p = r.mte_cod_p
       AND b.mte_ann_p = r.mte_ann_p
       AND b.mte_num_p = r.mte_num_p
    WHERE s.col_partita = bspart.part_partita
      AND s.mte_cod_p_part = bspart.mte_cod_p
      AND s.mte_ann_p_part = bspart.mte_ann_p
      AND s.mte_num_p_part = bspart.mte_num_p
      AND s.mco_prog_part = bspart.mco_prog
      -- Data registrazione: la stampa bbros "Partite aperte x rifiuto" usa la
      -- Data Reg. (mte_dat_r), non la data di partenza. Con mte_dat_r i colli
      -- combaciano esattamente con la stampa reale (vedi docs/waste/07).
      AND b.mte_dat_r <= ?
) c
WHERE bsimp.imp_cod = ?
  AND (
        bsimp.imp_uni = ?
        OR bsimp.imp_uni_mud = ?
        OR ? = 0
      )
  AND bsmte.mte_dat_r <= ?
  -- Esclusione movimenti interni WTS/99 resa NULL-safe: con imp_cod_pr NULL la
  -- vecchia condizione NOT(... = ...) valeva NULL e scartava erroneamente le
  -- partite di trasformazione senza produttore (giacenza reale mancante).
  AND NOT (
        COALESCE(bsmco.imp_cod_pr, '') = ?
        AND COALESCE(bsmco.imp_uni_pr, 0) = ?
      )
  AND bspart.part_qta_p + COALESCE(c.p, 0) > 0
ORDER BY
    zone_code,
    waste_code,
    sheet_code,
    batch_code,
    unit_sub_code
SQL;
    }

    /**
     * @return list<int|string>
     */
    private function parameters(?string $referenceDate): array
    {
        $referenceMoment = $this->referenceMoment($referenceDate);
        $companyCode = $this->stringConfig('wts.ecos.company_code', 'WTS');
        $unit = $this->intConfig('wts.ecos.unit', 1);
        $excludedOriginCode = $this->stringConfig('wts.ecos.excluded_origin_code', 'WTS');
        $excludedOriginUnit = $this->intConfig('wts.ecos.excluded_origin_unit', 99);
        $siteUnit = $this->intConfig('wts.ecos.site_unit', 1);

        return [
            $companyCode,
            $unit,
            $referenceMoment->format('Y-m-d H:i:s'),
            $companyCode,
            $unit,
            $unit,
            $siteUnit,
            $referenceMoment->format('Y-m-d H:i:s'),
            $excludedOriginCode,
            $excludedOriginUnit,
        ];
    }

    private function referenceMoment(?string $referenceDate): CarbonImmutable
    {
        $normalizedReferenceDate = trim((string) $referenceDate);
        if ($normalizedReferenceDate === '') {
            return CarbonImmutable::now()->endOfDay();
        }

        return CarbonImmutable::parse($normalizedReferenceDate)->endOfDay();
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function mapRow(array $row): ?WtsInventoryRow
    {
        $batchCode = trim($this->stringValue($row['batch_code'] ?? null));
        if ($batchCode === '') {
            return null;
        }

        return new WtsInventoryRow(
            batchCode: $batchCode,
            unitSubCode: trim($this->stringValue($row['unit_sub_code'] ?? null, '0')),
            unitCode: null,
            sheetCode: $this->nullableStringValue($row['sheet_code'] ?? null),
            sheetDescription: $this->nullableStringValue($row['sheet_description'] ?? null),
            hpCodes: $this->nullableStringValue($row['hp_codes'] ?? null),
            physicalStateCode: $this->nullableStringValue($row['physical_state_code'] ?? null),
            physicalStateLabel: $this->nullableStringValue($row['physical_state_label'] ?? null),
            treatmentCode: $this->nullableStringValue($row['treatment_code'] ?? null),
            treatmentLabel: $this->nullableStringValue($row['treatment_label'] ?? null),
            wasteCode: $this->nullableStringValue($row['waste_code'] ?? null),
            weightKg: $this->nullableFloatValue($row['weight_kg'] ?? null),
            volume: $this->nullableFloatValue($row['volume'] ?? null),
            colliCount: $this->nullableIntValue($row['colli_count'] ?? null),
            zoneCode: $this->nullableStringValue($row['zone_code'] ?? null),
            zoneLabel: $this->nullableStringValue($row['zone_label'] ?? null),
            productionDescription: $this->nullableStringValue($row['production_description'] ?? null),
            packagingDescription: $this->nullableStringValue($row['packaging_description'] ?? null),
            note1: $this->nullableStringValue($row['note_1'] ?? null),
            note2: $this->nullableStringValue($row['note_2'] ?? null),
            formReference: $this->nullableStringValue($row['form_reference'] ?? null),
            statusCode: 'available',
            source: 'ecos',
            producerName: $this->nullableStringValue($row['producer_name'] ?? null),
            treatmentLineCode: $this->nullableStringValue($row['treatment_line_code'] ?? null),
            productionCycleCode: $this->nullableStringValue($row['production_cycle_code'] ?? null),
        );
    }

    private function stringValue(mixed $value, string $fallback = ''): string
    {
        $normalized = trim(is_scalar($value) ? (string) $value : '');

        return $normalized === '' ? $fallback : $normalized;
    }

    private function nullableStringValue(mixed $value): ?string
    {
        $normalized = trim(is_scalar($value) ? (string) $value : '');

        return $normalized === '' ? null : $normalized;
    }

    private function nullableFloatValue(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private function nullableIntValue(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function stringConfig(string $key, string $fallback): string
    {
        $configured = config($key, $fallback);

        return is_scalar($configured) ? (string) $configured : $fallback;
    }

    private function intConfig(string $key, int $fallback): int
    {
        $configured = config($key, $fallback);

        return is_numeric($configured) ? (int) $configured : $fallback;
    }
}
