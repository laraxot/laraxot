<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Database\Factories\CacheLockFactory;

/**
 * Modules\Xot\Models\CacheLock.
 *
 * @property string $key
 * @property string $owner
<<<<<<< HEAD
 * @property int $expiration
 *
 * @method static CacheLockFactory factory($count = null, $state = [])
=======
<<<<<<< .merge_file_VvqUlZ
 * @property int $expiration
 *
 * @method static CacheLockFactory factory($count = null, $state = [])
=======
 *                              <<<<<<< HEAD
 * @property int    $expiration
 *
 * @method static CacheLockFactory factory($count = null, $state = [])
 *                                                                     =======
 *                                                                     <<<<<<< HEAD
 *                                                                     <<<<<<< HEAD
 *                                                                     =======
 *                                                                     <<<<<<< HEAD
 *                                                                     >>>>>>> laraxot/dev
 *
 * @property int $expiration
 *
 * @method static CacheLockFactory factory($count = null, $state = [])
 *                                                                     =======
 *
 * @property int $expiration
 *
 * <<<<<<< HEAD
 * =======
 *
 * @method static CacheLockFactory factory($count = null, $state = [])
 *                                                                     >>>>>>> laraxot/dev
 *                                                                     =======
 *
 * @property int $expiration
 *
 * >>>>>>> laraxot/dev
 *
 * @method static CacheLockFactory          factory($count = null, $state = [])
 *                                                                              >>>>>>> laraxot/dev
 *                                                                              >>>>>>> laraxot/dev
>>>>>>> .merge_file_xQ2ap7
>>>>>>> 77b1e2748 (first)
 * @method static Builder<static>|CacheLock newModelQuery()
 * @method static Builder<static>|CacheLock newQuery()
 * @method static Builder<static>|CacheLock query()
 * @method static Builder<static>|CacheLock whereExpiration($value)
 * @method static Builder<static>|CacheLock whereKey($value)
 * @method static Builder<static>|CacheLock whereOwner($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 *
 * @mixin \Eloquent
 */
class CacheLock extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'key',
        'owner',
        'expiration',
    ];
}
