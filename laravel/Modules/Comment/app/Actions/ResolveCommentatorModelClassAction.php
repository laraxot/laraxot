<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveCommentatorModelClassAction
{
    use QueueableAction;

    /** @return class-string<CanComment>|null */
    public function execute(): ?string
    {
        return $this->handle();
    }

    /** @return class-string<CanComment>|null */
    public function handle(): ?string
    {
        $class = config('comments.models.commentator');
        if (! is_string($class) || $class === '') {
            return null;
        }
        Assert::classExists($class);
        Assert::subclassOf($class, CanComment::class);

        return $class;
    }
}
