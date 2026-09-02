<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Contracts;

use Illuminate\Support\Collection;
use Modules\Comment\Enums\NotificationSubscriptionType;

/**
 * Commentable model with subscription / mention notification hooks (HasComments trait).
 */
interface SupportsCommentNotifications
{
    /**
     * @return Collection<int, CanComment>
     */
    public function subscribers(?NotificationSubscriptionType $type = null): Collection;

    /**
     * @return Collection<int, CanComment>
     */
    public function participatingCommentators(): Collection;
}
