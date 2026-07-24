<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Signature\Database\Factories\SignatureFactory;
use Modules\Signature\Database\Factories\SketchFactory;
use Modules\Signature\Models\Signature;
use Modules\Signature\Models\Sketch;
use Modules\Signature\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

// No database writes here: this module's test infrastructure does not run
// migrations automatically, so factories are used unpersisted (makeOne()).

it('builds a signature with disk/path/mime_type stored directly on the model', function (): void {
    $signature = SignatureFactory::new()->makeOne([
        'path' => 'media/signatures/test.png',
    ]);

    Assert::assertInstanceOf(Signature::class, $signature);
    Assert::assertSame('public', $signature->disk);
    Assert::assertSame('media/signatures/test.png', $signature->path);
    Assert::assertSame('image/png', $signature->mime_type);
});

it('builds a sketch with disk/path/mime_type stored directly on the model', function (): void {
    $sketch = SketchFactory::new()->makeOne([
        'path' => 'media/sketches/test.png',
    ]);

    Assert::assertInstanceOf(Sketch::class, $sketch);
    Assert::assertSame('public', $sketch->disk);
    Assert::assertSame('media/sketches/test.png', $sketch->path);
    Assert::assertSame('image/png', $sketch->mime_type);
});

it('exposes a generic morphTo signable relation, not tied to a specific module', function (): void {
    $signature = new Signature();

    Assert::assertInstanceOf(MorphTo::class, $signature->signable());
});

it('exposes a generic morphTo sketchable relation, not tied to a specific module', function (): void {
    $sketch = new Sketch();

    Assert::assertInstanceOf(MorphTo::class, $sketch->sketchable());
});
