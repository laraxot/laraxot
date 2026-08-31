<?php

declare(strict_types=1);

namespace Modules\UI\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Models\BaseModel;

/**
 * FieldOption model for UI module.
 *
 * FormBuilder module not available - extending from XotBaseModel instead.
 *
 *
 * @method static \Modules\UI\Database\Factories\FieldOptionFactory factory($count = null, $state = [])
 * @method static Builder<static>|FieldOption newModelQuery()
 * @method static Builder<static>|FieldOption newQuery()
 * @method static Builder<static>|FieldOption query()
 *
 * @mixin \Eloquent
 */
class FieldOption extends BaseModel
{
    protected $table = 'field_options';

    /** @var list<string> */
    protected $fillable = [
        'field_id',
        'label',
        'value',
        'order',
    ];
}
