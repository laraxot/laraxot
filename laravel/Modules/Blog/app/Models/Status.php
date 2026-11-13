<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\ModelStatus\Status as BaseStatus;

/**
 * Modules\Blog\Models\Status.
 *
 * @property int $id
 * @property string $name
 * @property string|null $reason
 * @property string $model_type
 * @property int $model_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Model|Eloquent $model
 *
 * @method static Builder|Status newModelQuery()
 * @method static Builder|Status newQuery()
 * @method static Builder|Status query()
 * @method static Builder|Status whereCreatedAt($value)
 * @method static Builder|Status whereCreatedBy($value)
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereModelId($value)
 * @method static Builder|Status whereModelType($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereReason($value)
 * @method static Builder|Status whereUpdatedAt($value)
 * @method static Builder|Status whereUpdatedBy($value)
 *
 * @property string $ip_address
 * @property string $user_agent
 * @property int $post_id
 * @property int $user_id
 *
 * @method static Builder|Status whereIpAddress($value)
 * @method static Builder|Status wherePostId($value)
 * @method static Builder|Status whereUserAgent($value)
 * @method static Builder|Status whereUserId($value)
 *
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|Status whereDeletedAt($value)
 * @method static Builder|Status whereDeletedBy($value)
 *
 * @mixin Eloquent
 */
class Status extends BaseStatus
{
    protected $connection = 'blog';
}
