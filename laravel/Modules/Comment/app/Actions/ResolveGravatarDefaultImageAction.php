<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolveGravatarDefaultImageAction
{
    use QueueableAction;

    public function execute(): string
    {
        return $this->handle();
    }

    public function handle(): string
    {
        $value = config('comments.gravatar.default_image', 'mp');

        return is_string($value) ? $value : 'mp';
    }
}
