<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/** Resolves a registered Comment mail template. */
final class ResolveCommentMailViewAction
{
    use QueueableAction;

    /** @return view-string */
    public function execute(string $viewName): string
    {
        Assert::stringNotEmpty($viewName);

        if (! view()->exists($viewName)) {
            throw new \LogicException("Comment mail view [{$viewName}] does not exist.");
        }

        return $viewName;
    }
}
