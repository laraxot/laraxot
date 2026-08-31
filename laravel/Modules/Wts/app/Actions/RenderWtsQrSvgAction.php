<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

final class RenderWtsQrSvgAction
{
    public function execute(string $payload, int $size = 260): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd,
        );

        return (new Writer($renderer))->writeString($payload);
    }

    public function dataUri(string $payload, int $size = 260): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($this->execute($payload, $size));
    }
}
