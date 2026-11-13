<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;

/**
 * Chiave stabile per deduplicare commentator in collection (notifiche approvazione).
 */
final class CommentatorUniqueKeyAction
{
    use QueueableAction;

    public function execute(CanComment $commentator): string
    {
        $key = $commentator->getKey();

        $keyPart = match (true) {
            $key === null => 'null',
            is_int($key) || is_string($key) => (string) $key,
            default => 'unknown',
        };

        return $commentator->getMorphClass().':'.$keyPart;
    }
}
