<?php

declare(strict_types=1);

namespace Modules\Wts\Label;

use InvalidArgumentException;

/**
 * Costruisce comandi ZPL II per testo, barcode Code 128 e immagini (^GF).
 *
 * @see https://labelary.com/zpl.html
 * @see Citizen ZPL Command Reference (CL-S700 series)
 */
final class ZplBuilder
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function renderDefaultLabel(array $data): string
    {
        $title = $this->escapeFieldData(TypeCaster::string($data['title'] ?? ''));
        $product = $this->escapeFieldData(TypeCaster::string($data['product'] ?? ''));
        $serial = $this->escapeFieldData(TypeCaster::string($data['serial'] ?? ''));
        $barcode = $this->escapeFieldData(TypeCaster::string($data['barcode'] ?? ''));

        return implode("\n", [
            '^XA',
            '^FO40,40^A0N,40,40^FD'.$title.'^FS',
            '^FO40,100^A0N,30,30^FDProdotto: '.$product.'^FS',
            '^FO40,145^A0N,30,30^FDSeriale: '.$serial.'^FS',
            '^FO40,200^BY2',
            '^BCN,100,Y,N,N',
            '^FD'.$barcode.'^FS',
            '^XZ',
        ]);
    }

    /**
     * @param  array<string, mixed>  $template
     * @param  array<string, mixed>  $values
     */
    public function renderTemplate(array $template, array $values = []): string
    {
        $width = TypeCaster::int($template['labelWidth'] ?? 400, 400);
        $height = TypeCaster::int($template['labelHeight'] ?? 300, 300);
        $dpi = TypeCaster::int($template['dpi'] ?? 203, 203);

        $lines = ['^XA'];

        if ($width > 0 && $height > 0) {
            $lines[] = sprintf('^PW%d', $width);
            $lines[] = sprintf('^LL%d', $height);
        }

        $originX = max(0, TypeCaster::int($template['originX'] ?? 0));
        $originY = max(0, TypeCaster::int($template['originY'] ?? 0));

        if ($originX > 0 || $originY > 0) {
            $lines[] = sprintf('^LH%d,%d', $originX, $originY);
        }

        $lines[] = '^CI28';

        // Tracking del supporto (^MN): N=continuo, W=gap/web, M=black mark.
        $mediaTracking = strtoupper(TypeCaster::string($template['mediaTracking'] ?? ''));

        if (in_array($mediaTracking, ['N', 'W', 'M'], true)) {
            $lines[] = '^MN'.$mediaTracking;
        }

        // Modalità supporto: T=strappo, P=peel, C=taglierina, D=taglio ritardato.
        // Serve a far tagliare/avanzare l'etichetta a fine stampa (es. PM42 con cutter).
        $mediaMode = strtoupper(TypeCaster::string($template['mediaMode'] ?? ''));

        if (in_array($mediaMode, ['T', 'P', 'C', 'D'], true)) {
            $lines[] = '^MM'.$mediaMode;
        }

        $elements = $template['elements'] ?? [];

        if (! is_array($elements)) {
            throw new InvalidArgumentException('Il campo template.elements deve essere un array.');
        }

        foreach ($elements as $element) {
            if (! is_array($element)) {
                continue;
            }

            $fragment = $this->renderElement(TypeCaster::stringKeyedArray($element), $values, $dpi);

            if ($fragment !== '') {
                $lines[] = $fragment;
            }
        }

        $lines[] = '^XZ';

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $element
     * @param  array<string, mixed>  $values
     */
    private function renderElement(array $element, array $values, int $dpi): string
    {
        $type = TypeCaster::string($element['type'] ?? 'text', 'text');
        $x = TypeCaster::int($element['x'] ?? 0);
        $y = TypeCaster::int($element['y'] ?? 0);

        return match ($type) {
            'text' => $this->renderText($element, $values, $x, $y),
            'barcode' => $this->renderBarcode($element, $values, $x, $y),
            'qr' => $this->renderQr($element, $values, $x, $y),
            'image' => $this->renderImage($element, $x, $y),
            'graphic' => $this->renderGraphic($element, $x, $y),
            default => '',
        };
    }

    /**
     * @param  array<string, mixed>  $element
     * @param  array<string, mixed>  $values
     */
    private function renderText(array $element, array $values, int $x, int $y): string
    {
        $value = $this->resolveValue($element, $values);
        $font = TypeCaster::string($element['font'] ?? '0', '0');
        $height = TypeCaster::int($element['fontHeight'] ?? 30, 30);
        $width = TypeCaster::int($element['fontWidth'] ?? 30, 30);
        $prefix = TypeCaster::string($element['prefix'] ?? '');
        $suffix = TypeCaster::string($element['suffix'] ?? '');
        $bold = TypeCaster::bool($element['bold'] ?? false, false);
        $underline = TypeCaster::bool($element['underline'] ?? false, false);
        $orientation = $this->orientation($element);

        $text = $this->escapeFieldData($prefix.$value.$suffix);

        $lines = [sprintf('^FO%d,%d^A%s%s,%d,%d^FD%s^FS', $x, $y, $font, $orientation, $height, $width, $text)];

        if ($bold) {
            $lines[] = sprintf('^FO%d,%d^A%s%s,%d,%d^FD%s^FS', $x + 1, $y, $font, $orientation, $height, $width, $text);
        }

        if ($underline) {
            $lines[] = $this->renderTextUnderline($x, $y, $height, $width, $text);
        }

        return implode("\n", $lines);
    }

    private function renderTextUnderline(int $x, int $y, int $fontHeight, int $fontWidth, string $text): string
    {
        $underlineWidth = max(20, (int) (strlen($text) * max(1, (int) ($fontWidth * 0.55))));
        $lineThickness = max(2, (int) round($fontHeight / 10));
        $underlineY = $y + $fontHeight + max(1, (int) round($fontHeight / 8));

        return sprintf(
            '^FO%d,%d^GB%d,%d,%d,B,0^FS',
            $x,
            $underlineY,
            $underlineWidth,
            $lineThickness,
            $lineThickness
        );
    }

    /**
     * @param  array<string, mixed>  $element
     * @param  array<string, mixed>  $values
     */
    private function renderBarcode(array $element, array $values, int $x, int $y): string
    {
        $value = $this->resolveValue($element, $values);
        $moduleWidth = TypeCaster::int($element['moduleWidth'] ?? 2, 2);
        $height = TypeCaster::int($element['height'] ?? 100, 100);
        $showText = TypeCaster::bool($element['showText'] ?? true, true) ? 'Y' : 'N';
        $barcodeType = TypeCaster::string($element['barcodeType'] ?? 'code128', 'code128');

        $data = $this->escapeFieldData($value);
        $orientation = $this->orientation($element);

        if ($barcodeType === 'code39') {
            return sprintf(
                '^FO%d,%d^BY%d^B3%s,N,%d,%s,N^FD%s^FS',
                $x,
                $y,
                $moduleWidth,
                $orientation,
                $height,
                $showText,
                $data
            );
        }

        return sprintf(
            '^FO%d,%d^BY%d^BC%s,%d,%s,N,N^FD%s^FS',
            $x,
            $y,
            $moduleWidth,
            $orientation,
            $height,
            $showText,
            $data
        );
    }

    /**
     * QR Code model 2, modalità di input automatica (`<errorCorrection>A,<data>`).
     *
     * @param  array<string, mixed>  $element
     * @param  array<string, mixed>  $values
     */
    private function renderQr(array $element, array $values, int $x, int $y): string
    {
        $value = $this->resolveValue($element, $values);
        $magnification = max(1, min(10, TypeCaster::int($element['magnification'] ?? 4, 4)));
        $errorCorrection = TypeCaster::string($element['errorCorrection'] ?? 'M', 'M');

        if (! in_array($errorCorrection, ['H', 'Q', 'M', 'L'], true)) {
            $errorCorrection = 'M';
        }

        $data = $this->escapeFieldData($value);

        return sprintf('^FO%d,%d^BQ%s,2,%d^FD%sA,%s^FS', $x, $y, $this->orientation($element), $magnification, $errorCorrection, $data);
    }

    /**
     * Orientamento del campo ZPL: N (normale), R (90° orario), I (180°), B (270°).
     * Default N per retro-compatibilità.
     *
     * @param  array<string, mixed>  $element
     */
    private function orientation(array $element): string
    {
        $orientation = strtoupper(TypeCaster::string($element['orientation'] ?? 'N', 'N'));

        return in_array($orientation, ['N', 'R', 'I', 'B'], true) ? $orientation : 'N';
    }

    /**
     * Grafica ZPL pre-generata (comando ^GF/^GFA gia' pronto), posizionata a ^FO.
     * Usato per loghi convertiti offline: nessuna dipendenza da GD a runtime.
     *
     * @param  array<string, mixed>  $element
     */
    private function renderGraphic(array $element, int $x, int $y): string
    {
        $zplData = TypeCaster::string($element['zplData'] ?? '');

        if ($zplData === '') {
            return '';
        }

        return sprintf('^FO%d,%d%s^FS', $x, $y, $zplData);
    }

    /**
     * Gli elementi immagine non sono supportati.
     *
     * La libreria di origine delegava la conversione raster->ZPL a un
     * ZplImageConverter che non e' mai stato scritto: il ramo esplodeva a
     * runtime. Le etichette WTS disegnano il QR con ^BQ nativo, quindi qui si
     * rifiuta l'elemento invece di fingere un supporto che non c'e'.
     *
     * @param  array<string, mixed>  $element
     */
    private function renderImage(array $element, int $x, int $y): string
    {
        return '';
    }

    /**
     * @param  array<string, mixed>  $element
     * @param  array<string, mixed>  $values
     */
    private function resolveValue(array $element, array $values): string
    {
        $dataSource = TypeCaster::string($element['dataSource'] ?? '');

        if ($dataSource !== '' && array_key_exists($dataSource, $values)) {
            return TypeCaster::string($values[$dataSource]);
        }

        if (isset($element['staticValue'])) {
            return TypeCaster::string($element['staticValue']);
        }

        if ($dataSource !== '' && isset($element['defaultValue'])) {
            return TypeCaster::string($element['defaultValue']);
        }

        return '';
    }

    private function escapeFieldData(string $value): string
    {
        return str_replace(['^', '~'], ['\5E', '\7E'], $value);
    }
}
