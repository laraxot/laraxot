<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

final class RenderWtsQrSvgAction
{
    public function execute(string $payload, int $size = 260): string
    {
        $options = new QROptions([
            'version' => Version::AUTO,
            // 'custom' is QROutputInterface::CUSTOM's value; the constant itself
            // is deprecated (no replacement) as of chillerlan/php-qrcode 5.0.0.
            'outputType' => 'custom',
            'outputInterface' => QRMarkupSVG::class,
            'outputBase64' => false,
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
