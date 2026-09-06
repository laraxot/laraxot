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
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'scale' => $size / 260,
        ]);

        $qrCode = new QRCode($options);

        $result = $qrCode->render($payload);

        return is_string($result) ? $result : '';
    }

    public function dataUri(string $payload, int $size = 260): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($this->execute($payload, $size));
    }
}
