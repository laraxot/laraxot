<?php

declare(strict_types=1);

uses(\Modules\Signature\Tests\TestCase::class);

use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\Signature\Actions\StoreSketchForModelAction;

it('rejects an invalid signature data url prefix', function (): void {
    app(StoreSignatureForModelAction::class)->execute(
        dataUrl: 'data:text/plain;base64,Zm9v',
        signableType: \stdClass::class,
        signableId: 1,
        userId: 1,
        signedAt: null,
        payload: [],
    );
})->throws(RuntimeException::class);

it('rejects invalid base64 in signature data url', function (): void {
    app(StoreSignatureForModelAction::class)->execute(
        dataUrl: 'data:image/png;base64,NOT_BASE64***',
        signableType: \stdClass::class,
        signableId: 1,
        userId: 1,
        signedAt: null,
        payload: [],
    );
})->throws(RuntimeException::class);

it('rejects an invalid sketch data url prefix', function (): void {
    app(StoreSketchForModelAction::class)->execute(
        dataUrl: 'data:text/plain;base64,Zm9v',
        sketchableType: \stdClass::class,
        sketchableId: 1,
        userId: 1,
        payload: [],
    );
})->throws(RuntimeException::class);

it('rejects invalid base64 in sketch data url', function (): void {
    app(StoreSketchForModelAction::class)->execute(
        dataUrl: 'data:image/png;base64,NOT_BASE64***',
        sketchableType: \stdClass::class,
        sketchableId: 1,
        userId: 1,
        payload: [],
    );
})->throws(RuntimeException::class);

