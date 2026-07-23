<?php

declare(strict_types=1);

/**
 * @see https://github.com/cnastasi/event-sourcing-with-laravel/blob/main/app/Events/ProductPurchased.php
 */

namespace Modules\Blog\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class RatingClosedArticle extends ShouldBeStored
{
    public function __construct(
        public readonly string $userId,
        public readonly string $articleId,
        public readonly string $ratingId,
        public readonly int $credit,
    ) {
    }
}
