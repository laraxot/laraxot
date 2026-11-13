<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Database\Factories\PulseAggregateFactory;

/**
<<<<<<< HEAD
=======
<<<<<<< .merge_file_XfDhgC
>>>>>>> 77b1e2748 (first)
 * @property string $id
 * @property int $bucket
 * @property int $period
 * @property string $type
 * @property string $key
<<<<<<< HEAD
 * @property string|null $key_hash
 * @property string $aggregate
 * @property string $value
 * @property int|null $count
 *
 * @method static PulseAggregateFactory factory($count = null, $state = [])
=======
=======
 * <<<<<<< HEAD.
 *
 * @property string      $id
 * @property int         $bucket
 * @property int         $period
 * @property string      $type
 * @property string      $key
>>>>>>> .merge_file_MOq64D
 * @property string|null $key_hash
 * @property string      $aggregate
 * @property string      $value
 * @property int|null    $count
 *
 * @method static PulseAggregateFactory factory($count = null, $state = [])
<<<<<<< .merge_file_XfDhgC
=======
 *                                                                          =======
 *                                                                          <<<<<<< HEAD
 *                                                                          =======
 *                                                                          <<<<<<< HEAD
 *                                                                          >>>>>>> laraxot/dev
 *                                                                          <<<<<<< HEAD.
 *
 * @property string      $id
 * @property int         $bucket
 * @property int         $period
 * @property string      $type
 * @property string      $key
 * @property string|null $key_hash
 * @property string      $aggregate
 * @property string      $value
 * @property int|null    $count
 *
 * @method static PulseAggregateFactory factory($count = null, $state = [])
 *                                                                          =======
 *
 * @property string      $id
 * @property int         $bucket
 * @property int         $period
 * @property string      $type
 * @property string      $key
 * @property string|null $key_hash
 * @property string      $aggregate
 * @property string      $value
 * @property int|null    $count
 *
 * <<<<<<< HEAD
 * =======
 *
 * @method static PulseAggregateFactory factory($count = null, $state = [])
 *                                                                          >>>>>>> laraxot/dev
 *                                                                          =======
 *
 * @property string      $id
 * @property int         $bucket
 * @property int         $period
 * @property string      $type
 * @property string      $key
 * @property string|null $key_hash
 * @property string      $aggregate
 * @property string      $value
 * @property int|null    $count
 *
 * >>>>>>> laraxot/dev
 *
 * @method static PulseAggregateFactory          factory($count = null, $state = [])
 *                                                                                   >>>>>>> laraxot/dev
 *                                                                                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_MOq64D
>>>>>>> 77b1e2748 (first)
 * @method static Builder<static>|PulseAggregate newModelQuery()
 * @method static Builder<static>|PulseAggregate newQuery()
 * @method static Builder<static>|PulseAggregate query()
 * @method static Builder<static>|PulseAggregate whereAggregate($value)
 * @method static Builder<static>|PulseAggregate whereBucket($value)
 * @method static Builder<static>|PulseAggregate whereCount($value)
 * @method static Builder<static>|PulseAggregate whereId($value)
 * @method static Builder<static>|PulseAggregate whereKey($value)
 * @method static Builder<static>|PulseAggregate whereKeyHash($value)
 * @method static Builder<static>|PulseAggregate wherePeriod($value)
 * @method static Builder<static>|PulseAggregate whereType($value)
 * @method static Builder<static>|PulseAggregate whereValue($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 *
 * @mixin \Eloquent
 */
class PulseAggregate extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'type',
        'key',
        'value',
    ];
}
