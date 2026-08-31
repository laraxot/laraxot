<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Support\WtsLegacyPayload;
use Illuminate\Support\Str;
use Modules\Wts\Label\ZplBuilder;
use function Safe\file_get_contents;

/**
 * Genera l'etichetta di un collo WTS in ZPL (per stampa diretta su etichettatrice,
 * es. Honeywell PM42 con emulazione ZPL). Stesso contenuto del PDF: produttore,
 * indirizzo unita' locale, CER, descrizione, QR grande, peso VERIFICATO, no partita.
 *
 * Formato di default 140x50 mm a 203 dpi (8 dot/mm) ~= 1119 x 400 dot.
 */
final readonly class GenerateWtsUnitLabelZplAction
{
    public function __construct(
        private BuildWtsQrPayloadAction $payloadAction,
    ) {}

    public function execute(WtsUnit $unit): string
    {
        $unit->loadMissing('inboundLot.inboundRecord.technicalSheetSnapshot');

        $lot = $unit->inboundLot;
        $record = $lot?->inboundRecord;
        $sheet = $record?->technicalSheetSnapshot;

        // Un collo reale ha sempre lotto, record e scheda tecnica: senza, l'etichetta
        // non e' generabile. La guardia soddisfa anche l'analisi statica.
        if ($lot === null || $record === null || $sheet === null) {
            throw new \RuntimeException('Collo senza lotto o scheda tecnica: etichetta non generabile.');
        }

        /** @var array<string, mixed>|null $payload */
        $payload = $sheet->legacy_payload;

        $entryWeight = $record->entry_weight;
        $grossQuantity = (float) $record->gross_quantity;
        $weight = $entryWeight !== null
            ? (float) $entryWeight
            : ($grossQuantity > 0 ? $grossQuantity : null);
        $conferimentoDate = WtsLegacyPayload::movementDate($payload, $record->received_at);

        $values = [
            'sheet_code' => $sheet->sheet_code,
            'cer' => $sheet->waste_code,
            'producer' => $sheet->producer_name,
            'address' => WtsLegacyPayload::firstString($payload, ['producer_address', 'imp_indirizzo', 'imp_ind']) ?? '',
            // Descrizione della SCHEDA TECNICA (str_des, specifica per produttore), non quella
            // generica del codice rifiuto. Ripiego sulle vecchie se il payload non ce l'ha.
            'description' => Str::limit(
                WtsLegacyPayload::firstString($payload, ['technical_description'])
                    ?? $sheet->production_description
                    ?? $sheet->packaging_description
                    ?? '',
                90
            ),
            'weight' => $weight !== null ? number_format($weight, 0, ',', '.').' kg' : 'N/D',
            'formulario' => $record->form_number ?? 'N/D',
            'stato' => WtsLegacyPayload::firstString($payload, ['physical_state_code', 'stf_cod']) ?? 'N/D',
            'colli' => (string) ($record->ecos_units_count ?? $lot->units()->count()),
            'data' => $conferimentoDate?->format('d-m-Y') ?? 'N/D',
            // Linea di trattamento (lit_cod: WD/WR) e ciclo di produzione (ltc_cod: COMB/ACCO).
            'linea' => $record->treatment_line_code ?? 'N/D',
            'ciclo' => $record->production_cycle_code ?? 'N/D',
            'unit_code' => $unit->unit_code,
            'lot_code' => $lot->lot_code,
            'qr' => $this->payloadAction->execute($unit),
        ];

        return (new ZplBuilder)->renderTemplate($this->template(), $values);
    }

    /**
     * Template etichetta: dimensioni dal config (mm -> dot), layout come il PDF
     * (QR a sinistra, dati a destra) tarato sul formato 140x50 mm @203dpi, poi
     * ruotato secondo config `label_pdf.zpl_rotate` per adattarlo al verso del supporto.
     *
     * @return array<string, mixed>
     */
    private function template(): array
    {
        $dpiRaw = config('wts.label_pdf.dpi', 203);
        $dpi = is_numeric($dpiRaw) ? (int) $dpiRaw : 203;
        $widthRaw = config('wts.label_pdf.width_mm', 140);
        $heightRaw = config('wts.label_pdf.height_mm', 50);
        $widthMm = is_numeric($widthRaw) ? (float) $widthRaw : 140.0;
        $heightMm = is_numeric($heightRaw) ? (float) $heightRaw : 50.0;

        $toDots = static fn (float $mm): int => (int) round($mm * $dpi / 25.4);
        $width = $toDots($widthMm);
        $height = $toDots($heightMm);

        $textX = 380;
        $qrMag = max(1, min(10, $this->intConfig('wts.label_pdf.zpl_qr_magnification', 5)));

        // Layout orizzontale (come il PDF): QR a sinistra, dati a destra. Le posizioni Y
        // (landscape) sono calcolate per dare, dopo la rotazione, colonne con gap fisso e
        // margini di sicurezza dai bordi -> nessun campo tagliato o sovrapposto (requisito
        // legale: ogni dato deve esserci tutto).
        $elements = [
            ['type' => 'qr', 'x' => 20, 'y' => 40, 'magnification' => $qrMag, 'errorCorrection' => 'M', 'dataSource' => 'qr'],
            ['type' => 'text', 'x' => $textX, 'y' => 80, 'font' => '0', 'fontHeight' => 34, 'fontWidth' => 34, 'bold' => true, 'dataSource' => 'sheet_code'],
            // Sulla stessa riga dell'ST, a destra: linea di trattamento e ciclo di produzione
            // (solo i valori, senza etichetta).
            ['type' => 'text', 'x' => 690, 'y' => 80, 'font' => '0', 'fontHeight' => 28, 'fontWidth' => 28, 'bold' => true, 'dataSource' => 'linea'],
            ['type' => 'text', 'x' => 900, 'y' => 80, 'font' => '0', 'fontHeight' => 28, 'fontWidth' => 28, 'bold' => true, 'dataSource' => 'ciclo'],
            ['type' => 'text', 'x' => $textX, 'y' => 130, 'font' => '0', 'fontHeight' => 46, 'fontWidth' => 46, 'bold' => true, 'prefix' => 'CER ', 'dataSource' => 'cer'],
            ['type' => 'text', 'x' => $textX, 'y' => 162, 'font' => '0', 'fontHeight' => 28, 'fontWidth' => 28, 'bold' => true, 'dataSource' => 'producer'],
            ['type' => 'text', 'x' => $textX, 'y' => 188, 'font' => '0', 'fontHeight' => 22, 'fontWidth' => 22, 'dataSource' => 'address'],
            ['type' => 'text', 'x' => $textX, 'y' => 212, 'font' => '0', 'fontHeight' => 20, 'fontWidth' => 20, 'dataSource' => 'description'],
            ['type' => 'text', 'x' => $textX, 'y' => 246, 'font' => '0', 'fontHeight' => 30, 'fontWidth' => 30, 'bold' => true, 'prefix' => 'Peso: ', 'dataSource' => 'weight'],
            ['type' => 'text', 'x' => $textX, 'y' => 274, 'font' => '0', 'fontHeight' => 24, 'fontWidth' => 24, 'prefix' => 'Formulario: ', 'dataSource' => 'formulario'],
            ['type' => 'text', 'x' => $textX, 'y' => 302, 'font' => '0', 'fontHeight' => 24, 'fontWidth' => 24, 'prefix' => 'Colli: ', 'dataSource' => 'colli'],
            ['type' => 'text', 'x' => $textX + 260, 'y' => 302, 'font' => '0', 'fontHeight' => 24, 'fontWidth' => 24, 'prefix' => 'Stato: ', 'dataSource' => 'stato'],
            ['type' => 'text', 'x' => $textX, 'y' => 330, 'font' => '0', 'fontHeight' => 24, 'fontWidth' => 24, 'prefix' => 'Data: ', 'dataSource' => 'data'],
            ['type' => 'text', 'x' => 250, 'y' => 360, 'font' => '0', 'fontHeight' => 18, 'fontWidth' => 18, 'prefix' => 'Collo ', 'dataSource' => 'unit_code'],
            ['type' => 'text', 'x' => 620, 'y' => 360, 'font' => '0', 'fontHeight' => 18, 'fontWidth' => 18, 'prefix' => 'Lotto ', 'dataSource' => 'lot_code'],
        ];

        // Logo W.T.S. nella zona bianca verso il foro (coordinate gia' finali: non ruotate).
        $logo = $this->logoElement();
        if ($logo !== null) {
            $elements[] = $logo;
        }

        $rotateRaw = config('wts.label_pdf.zpl_rotate', 0);
        $rotate = is_numeric($rotateRaw) ? ((int) $rotateRaw % 360 + 360) % 360 : 0;

        // Offset di posizionamento (mm -> dot) per centrare il contenuto sulla zona bianca.
        $offsetX = (int) round($this->floatConfig('wts.label_pdf.zpl_offset_x_mm', 0.0) * $dpi / 25.4);
        $offsetY = (int) round($this->floatConfig('wts.label_pdf.zpl_offset_y_mm', 0.0) * $dpi / 25.4);

        $qrX = $this->intConfig('wts.label_pdf.zpl_qr_x_dots', 20);
        $qrY = $this->intConfig('wts.label_pdf.zpl_qr_y_dots', 20);

        $template = $this->applyRotation($width, $height, $dpi, $elements, $rotate, $offsetX, $offsetY, $qrX, $qrY);

        $mediaRaw = config('wts.label_pdf.zpl_media_mode', '');
        $template['mediaMode'] = is_scalar($mediaRaw) ? strtoupper((string) $mediaRaw) : '';

        $trackingRaw = config('wts.label_pdf.zpl_media_tracking', '');
        $template['mediaTracking'] = is_scalar($trackingRaw) ? strtoupper((string) $trackingRaw) : '';

        // La PM42 senza ^PW/^LL non stampa. Di default forziamo le dimensioni; se è nota la
        // misura REALE del supporto (in dot) la usiamo al posto di quella calcolata: sui
        // cartellini fustellati il supporto è più lungo della zona bianca.
        if (config('wts.label_pdf.zpl_force_size', true) === false) {
            $template['labelWidth'] = 0;
            $template['labelHeight'] = 0;
        } else {
            $labelWidth = $this->intConfig('wts.label_pdf.zpl_label_width_dots', 0);
            $labelLength = $this->intConfig('wts.label_pdf.zpl_label_length_dots', 0);

            if ($labelWidth > 0) {
                $template['labelWidth'] = $labelWidth;
            }
            if ($labelLength > 0) {
                $template['labelHeight'] = $labelLength;
            }
        }

        return $template;
    }

    /**
     * Elemento logo W.T.S. (grafica ZPL pre-generata), o null se disabilitato/mancante.
     *
     * @return array<string, mixed>|null
     */
    private function logoElement(): ?array
    {
        if (config('wts.label_pdf.zpl_logo_enabled', true) !== true) {
            return null;
        }

        // Il logo e' un asset del modulo, non dell'applicazione: sta accanto al
        // codice che lo stampa e segue il modulo ovunque venga installato.
        $path = module_path('Wts', 'resources/wts/label-logo.gfa');
        if (! is_file($path)) {
            return null;
        }

        $zpl = trim((string) file_get_contents($path));
        if ($zpl === '') {
            return null;
        }

        return [
            'type' => 'graphic',
            'x' => $this->intConfig('wts.label_pdf.zpl_logo_x_dots', 135),
            'y' => $this->intConfig('wts.label_pdf.zpl_logo_y_dots', 760),
            'zplData' => $zpl,
        ];
    }

    private function floatConfig(string $key, float $default): float
    {
        $raw = config($key, $default);

        return is_numeric($raw) ? (float) $raw : $default;
    }

    private function intConfig(string $key, int $default): int
    {
        $raw = config($key, $default);

        return is_numeric($raw) ? (int) $raw : $default;
    }

    /**
     * Ruota il template di 90/180/270 gradi e applica l'offset (in dot). A 90/270 scambia
     * larghezza e altezza e imposta l'orientamento dei campi (R = 90° orario, B = 270°); a 180 usa I.
     *
     * @param  list<array<string, mixed>>  $elements
     * @return array<string, mixed>
     */
    private function applyRotation(int $width, int $height, int $dpi, array $elements, int $rotate, int $offsetX, int $offsetY, int $qrX, int $qrY): array
    {
        $swap = $rotate === 90 || $rotate === 270;

        $out = [];
        foreach ($elements as $el) {
            $x = is_numeric($el['x'] ?? null) ? (int) $el['x'] : 0;
            $y = is_numeric($el['y'] ?? null) ? (int) $el['y'] : 0;

            if ($rotate === 90) {
                $nx = $height - $y;
                $ny = $x;
                $el['orientation'] = 'R';
            } elseif ($rotate === 270) {
                $nx = $y;
                $ny = $width - $x;
                $el['orientation'] = 'B';
            } elseif ($rotate === 180) {
                $nx = $width - $x;
                $ny = $height - $y;
                $el['orientation'] = 'I';
            } else {
                $nx = $x;
                $ny = $y;
            }

            $type = $el['type'] ?? '';

            if ($type === 'qr') {
                // Il QR è quadrato e leggibile in ogni verso: non lo ruotiamo. Posizione
                // libera: qrX (larghezza) e qrY (lungo il nastro).
                $el['orientation'] = 'N';
                $el['x'] = $qrX + $offsetX;
                $el['y'] = $qrY + $offsetY;
            } elseif ($type === 'graphic') {
                // Logo: posizione fissa in testa (dove sta il QR), PRIMA del foro. NON riceve
                // l'offset Y (che serve a spostare dati+QR dopo il foro): così resta in testa.
                $el['x'] = $x + $offsetX;
                $el['y'] = $y;
            } else {
                $el['x'] = $nx + $offsetX;
                $el['y'] = $ny + $offsetY;
            }

            $out[] = $el;
        }

        return [
            'labelWidth' => $swap ? $height : $width,
            'labelHeight' => $swap ? $width : $height,
            'dpi' => $dpi,
            'elements' => $out,
        ];
    }
}
