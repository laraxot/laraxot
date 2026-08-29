<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

final class RenderTimberQrSvgAction
{
    public function execute(string $payload, int $size = 260): string
    {
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'scale' => max(1, (int) round($size / 25)),
        ]);

        return (string) (new QRCode($options))->render($payload);
    }

    public function dataUri(string $payload, int $size = 260): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($this->execute($payload, $size));
    }
}
