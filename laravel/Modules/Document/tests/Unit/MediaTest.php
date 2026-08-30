<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Document\Database\Factories\MediaFactory;
use Modules\Document\Models\Media;
use Modules\Document\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Document\Tests\TestCase::class);

// No database writes here: factories are used unpersisted (makeOne()).

it('builds a media with disk/path/kind stored directly on the model', function (): void {
    $media = MediaFactory::new()->makeOne([
        'path' => 'media/documents/test.pdf',
        'kind' => 'document',
    ]);

    Assert::assertInstanceOf(Media::class, $media);
    Assert::assertSame('public', $media->disk);
    Assert::assertSame('media/documents/test.pdf', $media->path);
    Assert::assertSame('document', $media->kind);
});

it('exposes a generic morphTo mediable relation, not tied to a specific module', function (): void {
    $media = new Media();

    Assert::assertInstanceOf(MorphTo::class, $media->mediable());
});
