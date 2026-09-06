<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

final class RenderWtsQrSvgAction
{
    public function execute(string $payload, int $size = 260): string
    {
        $options = new QROptions([
            'version' => QRCode::VERSION_AUTO,
            'outputType' => QRCode::OUTPUT_SVG_STRING,
            'scale' => $size / 260,
        ]);

        $qrCode = new QRCode($options);

        return $qrCode->render($payload);
    }

    public function dataUri(string $payload, int $size = 260): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($this->execute($payload, $size));
    }
}
