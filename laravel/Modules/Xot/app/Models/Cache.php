<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Database\Factories\CacheFactory;

/**
 * Modules\Xot\Models\Cache.
 *
 * @property string $key
 * @property string $value
<<<<<<< HEAD
 * @property int $expiration
 *
 * @method static CacheFactory factory($count = null, $state = [])
=======
<<<<<<< .merge_file_XrvvUG
 * @property int $expiration
 *
 * @method static CacheFactory factory($count = null, $state = [])
=======
 *                              <<<<<<< HEAD
 * @property int    $expiration
 *
 * @method static CacheFactory factory($count = null, $state = [])
 *                                                                 =======
 *                                                                 <<<<<<< HEAD
 *                                                                 <<<<<<< HEAD
 *                                                                 =======
 *                                                                 <<<<<<< HEAD
 *                                                                 >>>>>>> laraxot/dev
 *
 * @property int $expiration
 *
 * @method static CacheFactory factory($count = null, $state = [])
 *                                                                 =======
 *
 * @property int $expiration
 *
 * <<<<<<< HEAD
 * =======
 *
 * @method static CacheFactory factory($count = null, $state = [])
 *                                                                 >>>>>>> laraxot/dev
 *                                                                 =======
 *
 * @property int $expiration
 *
 * >>>>>>> laraxot/dev
 *
 * @method static CacheFactory          factory($count = null, $state = [])
 *                                                                          >>>>>>> laraxot/dev
 *                                                                          >>>>>>> laraxot/dev
>>>>>>> .merge_file_zcOSvc
>>>>>>> 77b1e2748 (first)
 * @method static Builder<static>|Cache newModelQuery()
 * @method static Builder<static>|Cache newQuery()
 * @method static Builder<static>|Cache query()
 * @method static Builder<static>|Cache whereExpiration($value)
 * @method static Builder<static>|Cache whereKey($value)
 * @method static Builder<static>|Cache whereValue($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 *
 * @mixin \Eloquent
 */
class Cache extends BaseModel
{
    protected $table = 'cache';

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    /** @var list<string> */
    protected $fillable = [
        'key',
        'value',
        'expiration',
    ];
}
