<?php

declare(strict_types=1);

use Modules\Cms\Filament\Forms\Components\DownloadAttachmentPlaceHolder;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('DownloadAttachmentPlaceHolder extends XotBasePlaceholder', function () {
    Assert::assertTrue(class_exists(DownloadAttachmentPlaceHolder::class));
});

it('DownloadAttachmentPlaceHolder has setUp method')->todo();
it('DownloadAttachmentPlaceHolder has generateContent method')->todo();
