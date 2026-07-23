<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Themes\Sixteen\Actions\Block\IsCanonicalBlockCategoryFolderAction;
use Themes\Sixteen\Actions\Block\IsLegacyBlockCategoryFolderAction;
use Themes\Sixteen\Actions\Block\ListInvalidBlockCategoryFoldersAction;
use Themes\Sixteen\Datas\BlockCategoryRegistryData;

$blocksRoot = dirname(__DIR__, 2).'/resources/views/components/blocks';

test('blocks subfolders use allowed tailwind or flowbite names', function () use ($blocksRoot): void {
    $invalid = app(ListInvalidBlockCategoryFoldersAction::class)->execute($blocksRoot);

    Assert::assertSame(
        [],
        $invalid,
        'Sottocartelle blocks/ non ammesse (usare slug da Flowbite/Tailwind UI): '.implode(', ', $invalid)
    );
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
