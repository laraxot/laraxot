<?php

declare(strict_types=1);

namespace Modules\Comment\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Comment\Models\Contracts\CanComment;

class ReactionPolicy
{
    public function delete(CanComment $commentator, Model $reaction): bool
    {
        $owner = $reaction->getRelationValue('commentator');

        if (! is_object($owner)) {
            return false;
        }

        if (! method_exists($owner, 'getMorphClass') || ! method_exists($owner, 'getKey')) {
            return false;
        }

        return $commentator->getMorphClass() === $owner->getMorphClass()
            && $commentator->getKey() === $owner->getKey();
    }
}
