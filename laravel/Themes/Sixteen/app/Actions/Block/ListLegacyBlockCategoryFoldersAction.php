<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Block;

use Spatie\QueueableAction\QueueableAction;

use function Safe\scandir;

final class ListLegacyBlockCategoryFoldersAction
{
    use QueueableAction;

    /** @return list<string> */
    public function execute(string $blocksRoot): array
    {
        if (! is_dir($blocksRoot)) {
            return [];
        }

        $present = [];

        /** @var list<string> $entries */
        $entries = scandir($blocksRoot);

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..' || str_ends_with($entry, '.old')) {
                continue;
            }

            $path = $blocksRoot.DIRECTORY_SEPARATOR.$entry;

            if (is_dir($path) && app(IsLegacyBlockCategoryFolderAction::class)->execute($entry)) {
                $present[] = $entry;
            }
        }

        sort($present);

        return $present;
    }
}
