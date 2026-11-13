<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ExtraContract;
use Modules\Xot\Database\Factories\ExtraFactory;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

/**
 * Model Extra.
 *
<<<<<<< HEAD
 * @property int $id
 * @property int|null $model_id
 * @property string|null $model_type
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 *
 * @method static Builder|BaseModel disableCache()
 * @method static ExtraFactory factory($count = null, $state = [])
 * @method static Builder|Extra newModelQuery()
 * @method static Builder|Extra newQuery()
 * @method static Builder|Extra query()
 * @method static Builder|BaseModel withCacheCooldownSeconds(?int $seconds = null)
 * @method static Builder|Extra withExtraAttributes()
=======
<<<<<<< .merge_file_AMEcod
 * @property int $id
 * @property int|null $model_id
 * @property string|null $model_type
=======
 * <<<<<<< HEAD
 *
 * @property int                                               $id
 * @property int|null                                          $model_id
 * @property string|null                                       $model_type
>>>>>>> .merge_file_ThdblJ
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 *
 * @method static Builder|BaseModel disableCache()
 * @method static ExtraFactory      factory($count = null, $state = [])
 * @method static Builder|Extra     newModelQuery()
 * @method static Builder|Extra     newQuery()
 * @method static Builder|Extra     query()
 * @method static Builder|BaseModel withCacheCooldownSeconds(?int $seconds = null)
<<<<<<< .merge_file_AMEcod
 * @method static Builder|Extra withExtraAttributes()
=======
 * @method static Builder|Extra     withExtraAttributes()
 *                                                                                 =======
 *                                                                                 <<<<<<< HEAD
 *                                                                                 =======
 *                                                                                 <<<<<<< HEAD
 *                                                                                 <<<<<<< HEAD
 *
 * >>>>>>> laraxot/dev
 *
 * @property int                                               $id
 * @property int|null                                          $model_id
 * @property string|null                                       $model_type
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 *
 * @method static Builder|BaseModel disableCache()
 * @method static ExtraFactory      factory($count = null, $state = [])
 * @method static Builder|Extra     newModelQuery()
 * @method static Builder|Extra     newQuery()
 * @method static Builder|Extra     query()
 * @method static Builder|BaseModel withCacheCooldownSeconds(?int $seconds = null)
 * @method static Builder|Extra     withExtraAttributes()
 *                                                                                 <<<<<<< HEAD
 *                                                                                 =======
 *                                                                                 =======
 *
 * @property int                                               $id
 * @property int|null                                          $model_id
 * @property string|null                                       $model_type
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 *
 * @method static Builder|BaseModel disableCache()
 * @method static ExtraFactory      factory($count = null, $state = [])
 * @method static Builder|Extra     newModelQuery()
 * @method static Builder|Extra     newQuery()
 * @method static Builder|Extra     query()
 * @method static Builder|BaseModel withCacheCooldownSeconds(?int $seconds = null)
 * @method static Builder|Extra     withExtraAttributes()
 *                                                                                 >>>>>>> laraxot/dev
 *                                                                                 =======
 *
 * @property int                                               $id
 * @property int|null                                          $model_id
 * @property string|null                                       $model_type
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 *
 * @method static Builder|BaseModel disableCache()
 * @method static ExtraFactory      factory($count = null, $state = [])
 * @method static Builder|Extra     newModelQuery()
 * @method static Builder|Extra     newQuery()
 * @method static Builder|Extra     query()
 * @method static Builder|BaseModel withCacheCooldownSeconds(?int $seconds = null)
 * @method static Builder|Extra     withExtraAttributes()
 *                                                                                 >>>>>>> laraxot/dev
 *                                                                                 >>>>>>> laraxot/dev
 *                                                                                 >>>>>>> laraxot/dev
>>>>>>> .merge_file_ThdblJ
>>>>>>> 77b1e2748 (first)
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|Extra whereCreatedAt($value)
 * @method static Builder|Extra whereCreatedBy($value)
 * @method static Builder|Extra whereDeletedAt($value)
 * @method static Builder|Extra whereDeletedBy($value)
 * @method static Builder|Extra whereExtraAttributes($value)
 * @method static Builder|Extra whereId($value)
 * @method static Builder|Extra whereModelId($value)
 * @method static Builder|Extra whereModelType($value)
 * @method static Builder|Extra whereUpdatedAt($value)
 * @method static Builder|Extra whereUpdatedBy($value)
 *
 * @mixin \Eloquent
 */
abstract class BaseExtra extends BaseModel implements ExtraContract
{
    use SchemalessAttributesTrait;

    /** @var string */
    protected $connection = 'xot';

    protected $fillable = [
        'id',
        'model_id',
        'model_type',
        'extra_attributes',
    ];

    // ✅ CORRETTO: NON implementare scopeWithExtraAttributes() manualmente
    // Il trait SchemalessAttributesTrait lo fornisce automaticamente!

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'extra_attributes' => SchemalessAttributes::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
        ];
    }
}
