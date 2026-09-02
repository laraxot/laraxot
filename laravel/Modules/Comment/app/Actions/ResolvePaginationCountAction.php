<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolvePaginationCountAction
{
    use QueueableAction;

    public function execute(): int
    {
        return $this->handle();
    }

    public function handle(): int
    {
        $value = config('comments.pagination.results', 25);

        return is_int($value) ? min($value, 100) : 25;
    }
}
