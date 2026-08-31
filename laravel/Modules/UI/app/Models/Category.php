<?php

declare(strict_types=1);

namespace Modules\UI\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Models\BaseModel;

/**
 * @property int $id
 * @property string|null $name
 * @property string $title
 * @property string $slug
 * @property int|null $parent_id
 * @property string|null $description
 * @property string|null $icon
 * @property bool $is_active
 * @property int $sort_order
 *
 * @method static \Modules\UI\Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|Category newModelQuery()
 * @method static Builder<static>|Category newQuery()
 * @method static Builder<static>|Category query()
 *
 * @mixin \Eloquent
 */
class Category extends BaseModel
{
    protected $table = 'categories';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'parent_id',
        'is_active',
        'sort_order',
    ];
}
