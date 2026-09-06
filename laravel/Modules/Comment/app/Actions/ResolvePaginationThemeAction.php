<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolvePaginationThemeAction
{
    use QueueableAction;

    public function execute(): string
    {
        return $this->handle();
    }

    public function handle(): string
    {
        $value = config('comments.pagination.theme', 'tailwind');

        return is_string($value) ? $value : 'tailwind';
    }
}
