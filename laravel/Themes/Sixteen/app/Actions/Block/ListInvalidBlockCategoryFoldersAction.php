<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Block;

use Spatie\QueueableAction\QueueableAction;

use function Safe\scandir;

final class ListInvalidBlockCategoryFoldersAction
{
    use QueueableAction;

    /** @return list<string> */
    public function execute(string $blocksRoot): array
    {
        if (! is_dir($blocksRoot)) {
            return [];
        }

        $invalid = [];

        /** @var list<string> $entries */
        $entries = scandir($blocksRoot);

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..' || str_ends_with($entry, '.old')) {
                continue;
            }

            $path = $blocksRoot.DIRECTORY_SEPARATOR.$entry;

            if (! is_dir($path)) {
                continue;
            }

            if (! app(IsAllowedBlockCategoryFolderAction::class)->execute($entry)) {
                $invalid[] = $entry;
            }
        }

        sort($invalid);

        return $invalid;
    }
}
