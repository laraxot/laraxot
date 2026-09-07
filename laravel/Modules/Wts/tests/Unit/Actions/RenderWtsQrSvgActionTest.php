<?php

declare(strict_types=1);

use Modules\Wts\Actions\RenderWtsQrSvgAction;

use function Safe\base64_decode;

it('renders a QR code payload as SVG markup', function (): void {
    $svg = (new RenderWtsQrSvgAction())->execute('WTS-UNIT-123');

    expect($svg)->toContain('<svg');
});

it('accepts a custom size without erroring', function (): void {
    $svg = (new RenderWtsQrSvgAction())->execute('WTS-UNIT-123', 520);

    expect($svg)->toContain('<svg');
});

it('encodes the SVG as a base64 data URI', function (): void {
    $dataUri = (new RenderWtsQrSvgAction())->dataUri('WTS-UNIT-123');

    expect($dataUri)->toStartWith('data:image/svg+xml;base64,');

    $encoded = substr($dataUri, strlen('data:image/svg+xml;base64,'));
    $decoded = base64_decode($encoded);

    expect($decoded)->toContain('<svg');
});
