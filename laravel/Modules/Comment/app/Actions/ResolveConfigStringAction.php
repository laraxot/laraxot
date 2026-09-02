<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolveConfigStringAction
{
    use QueueableAction;

    public function execute(string $key, string $default = ''): string
    {
        return $this->handle($key, $default);
    }

    public function handle(string $key, string $default = ''): string
    {
        $value = config($key, $default);

        return is_string($value) ? $value : $default;
    }
}
