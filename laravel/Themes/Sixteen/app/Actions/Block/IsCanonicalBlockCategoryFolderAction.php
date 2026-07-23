<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Block;

use Spatie\QueueableAction\QueueableAction;
use Themes\Sixteen\Datas\BlockCategoryRegistryData;

final class IsCanonicalBlockCategoryFolderAction
{
    use QueueableAction;

    public function execute(string $folder): bool
    {
        return in_array($folder, BlockCategoryRegistryData::CANONICAL_FOLDERS, true);
    }
}
