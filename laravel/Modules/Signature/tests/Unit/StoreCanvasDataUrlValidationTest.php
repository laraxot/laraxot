<?php

declare(strict_types=1);

use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\Signature\Actions\StoreSketchForModelAction;

it('rejects an invalid signature data url prefix', function (): void {
    expect(fn (): mixed => app(StoreSignatureForModelAction::class)->execute(
        dataUrl: 'data:text/plain;base64,Zm9v',
        signableType: stdClass::class,
        signableId: 1,
        userId: 1,
        signedAt: null,
        payload: [],
    ))->toThrow(RuntimeException::class);
});

it('rejects invalid base64 in signature data url', function (): void {
    expect(fn (): mixed => app(StoreSignatureForModelAction::class)->execute(
        dataUrl: 'data:image/png;base64,NOT_BASE64***',
        signableType: stdClass::class,
        signableId: 1,
        userId: 1,
        signedAt: null,
        payload: [],
    ))->toThrow(RuntimeException::class);
});

it('rejects an invalid sketch data url prefix', function (): void {
    expect(fn (): mixed => app(StoreSketchForModelAction::class)->execute(
        dataUrl: 'data:text/plain;base64,Zm9v',
        sketchableType: stdClass::class,
        sketchableId: 1,
        userId: 1,
        payload: [],
    ))->toThrow(RuntimeException::class);
});

it('rejects invalid base64 in sketch data url', function (): void {
    expect(fn (): mixed => app(StoreSketchForModelAction::class)->execute(
        dataUrl: 'data:image/png;base64,NOT_BASE64***',
        sketchableType: stdClass::class,
        sketchableId: 1,
        userId: 1,
        payload: [],
    ))->toThrow(RuntimeException::class);
});
