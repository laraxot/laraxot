<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

final class RenderTimberQrSvgAction
{
    private const OUTPUT_TYPE = 'svg';

    public function execute(string $payload, int $size = 260): string
    {
        $options = new QROptions([
            'outputType' => self::OUTPUT_TYPE,
            'scale' => max(1, (int) round($size / 25)),
            // render() deve restituire il markup SVG grezzo: il data URI lo costruisce dataUri()
            'imageBase64' => false,
        ]);

        $rendered = (new QRCode($options))->render($payload);
        return is_string($rendered) ? $rendered : '';
    }

    public function dataUri(string $payload, int $size = 260): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($this->execute($payload, $size));
    }
}
