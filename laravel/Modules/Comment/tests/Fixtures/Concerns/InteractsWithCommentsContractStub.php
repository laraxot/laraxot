<?php

declare(strict_types=1);

namespace Modules\Comment\Tests\Fixtures\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Concerns\InteractsWithComments;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Datas\CommentatorProperties;

final class InteractsWithCommentsContractStub extends Model implements CanComment
{
    use InteractsWithComments;
    use Notifiable;

    protected $table = 'interacts_with_comments_contract_unused';

    public function __construct(private int|string $id = 1)
    {
        parent::__construct();
        $this->setAttribute($this->getKeyName(), $id);
    }

    public function getKey(): int|string
    {
        return $this->id;
    }

    public function getMorphClass(): string
    {
        return 'interacts-comments-contract';
    }

    public function commentatorProperties(): CommentatorProperties
    {
        return CommentatorProperties::email('contract@example.com')->name('Contract');
    }

    /** @return MorphMany<CommentNotificationSubscription, $this> */
    public function subscriberNotificationSubscriptions(): MorphMany
    {
        return $this->morphMany(CommentNotificationSubscription::class, 'subscriber');
    }
}
