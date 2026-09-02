<?php

declare(strict_types=1);

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Comment\Database\Factories\CommentNotificationOptOutFactory;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * @property int|string|null $commentator_id
 * @property string|null $commentator_type
 *
 * @property-read Model|null $commentable
 * @property-read Model|null $commentator
 *
 * @method static CommentNotificationOptOutFactory factory($count = null, $state = [])
 * @method static Builder<static> newModelQuery()
 * @method static Builder<static> newQuery()
 * @method static Builder<static> query()
 */
class CommentNotificationOptOut extends Model
{
    /** @use HasXotFactory<CommentNotificationOptOutFactory> */
    use HasXotFactory;

    protected $connection = 'comment';

    protected $guarded = [];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Model, $this> */
    public function user(): BelongsTo
    {
        $commentatorClass = CommentConfigData::make()->models['commentator'] ?? null;

        /** @var class-string<Model> $related */
        $related = is_string($commentatorClass) && $commentatorClass !== '' ? $commentatorClass : Model::class;

        return $this->belongsTo($related, 'user_id');
    }

    /** @return MorphTo<Model, $this> */
    public function commentator(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return MorphTo<Model, $this> */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): CommentNotificationOptOutFactory
    {
        return CommentNotificationOptOutFactory::new();
    }
}
