<?php

declare(strict_types=1);

namespace Modules\UI\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Models\BaseModel;

/**
 * Collection model for UI module.
 *
 * FormBuilder module not available - extending from XotBaseModel instead.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property int|null $theme_id
 * @property bool $is_active
 * @property int|null $order
 *
 * @method static \Modules\UI\Database\Factories\CollectionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Collection newModelQuery()
 * @method static Builder<static>|Collection newQuery()
 * @method static Builder<static>|Collection query()
 *
 * @mixin \Eloquent
 */
class Collection extends BaseModel
{
    protected $table = 'collections';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'description',
        'type',
        'theme_id',
        'is_active',
        'order',
    ];
}
