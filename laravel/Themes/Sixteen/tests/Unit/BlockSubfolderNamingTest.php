<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Tests\TestCase;
use Themes\Sixteen\Actions\Block\IsCanonicalBlockCategoryFolderAction;
use Themes\Sixteen\Actions\Block\IsLegacyBlockCategoryFolderAction;
use Themes\Sixteen\Datas\BlockCategoryRegistryData;

use function Safe\glob;

uses(TestCase::class);

$blocksRoot = dirname(__DIR__, 2).'/resources/views/components/blocks';

test('blocks subfolders use reusable CMS type slugs', function () use ($blocksRoot): void {
    $folders = glob($blocksRoot.'/*', GLOB_ONLYDIR);
    Assert::assertNotEmpty($folders);
    foreach ($folders as $folder) {
        Assert::assertIsString($folder);
        Assert::assertMatchesRegularExpression('/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/', basename($folder));
    }
});

test('registry marks known domain folders as legacy', function (): void {
    Assert::assertTrue(app(IsLegacyBlockCategoryFolderAction::class)->execute('ticket-layout'));
    Assert::assertTrue(app(IsLegacyBlockCategoryFolderAction::class)->execute('ticket-list'));
    Assert::assertTrue(app(IsCanonicalBlockCategoryFolderAction::class)->execute('hero'));
    Assert::assertTrue(app(IsCanonicalBlockCategoryFolderAction::class)->execute('cta'));
    Assert::assertFalse(app(IsCanonicalBlockCategoryFolderAction::class)->execute('ticket-layout'));
});

test('legacy folders do not overlap canonical list', function (): void {
    $overlap = array_values(array_intersect(
        BlockCategoryRegistryData::CANONICAL_FOLDERS,
        BlockCategoryRegistryData::LEGACY_FOLDERS
    ));

    Assert::assertSame([], $overlap);
});
