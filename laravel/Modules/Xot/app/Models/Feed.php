<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Database\Factories\FeedFactory;

/**
 * Modules\Xot\Models\Feed.
 *
<<<<<<< HEAD
 * @property string $id
=======
<<<<<<< .merge_file_pX75Bx
 * @property string $id
=======
 * <<<<<<< HEAD
 *
 * @property string      $id
 *                                   =======
 *                                   <<<<<<< HEAD
 *                                   <<<<<<< HEAD
 *                                   =======
 *                                   <<<<<<< HEAD
 *                                   >>>>>>> laraxot/dev
 * @property string      $id
 *                                   =======
 * @property string      $id
 *                                   >>>>>>> laraxot/dev
 *                                   <<<<<<< HEAD
 *                                   =======
 *                                   =======
 * @property string      $id
 *                                   >>>>>>> laraxot/dev
 *                                   >>>>>>> laraxot/dev
 *                                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_E7YS3G
>>>>>>> 77b1e2748 (first)
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
<<<<<<< HEAD
 * @method static FeedFactory factory($count = null, $state = [])
=======
<<<<<<< .merge_file_pX75Bx
 * @method static FeedFactory factory($count = null, $state = [])
=======
 * <<<<<<< HEAD
 *
 * @method static FeedFactory          factory($count = null, $state = [])
 *                                                                         =======
 *                                                                         <<<<<<< HEAD
 *                                                                         <<<<<<< HEAD
 *                                                                         =======
 *                                                                         <<<<<<< HEAD
 *                                                                         >>>>>>> laraxot/dev
 * @method static FeedFactory          factory($count = null, $state = [])
 *                                                                         =======
 * @method static FeedFactory          factory($count = null, $state = [])
 *                                                                         >>>>>>> laraxot/dev
 *                                                                         <<<<<<< HEAD
 *                                                                         =======
 *                                                                         =======
 * @method static FeedFactory          factory($count = null, $state = [])
 *                                                                         >>>>>>> laraxot/dev
 *                                                                         >>>>>>> laraxot/dev
 *                                                                         >>>>>>> laraxot/dev
>>>>>>> .merge_file_E7YS3G
>>>>>>> 77b1e2748 (first)
 * @method static Builder<static>|Feed newModelQuery()
 * @method static Builder<static>|Feed newQuery()
 * @method static Builder<static>|Feed query()
 * @method static Builder<static>|Feed whereCreatedAt($value)
 * @method static Builder<static>|Feed whereCreatedBy($value)
 * @method static Builder<static>|Feed whereId($value)
 * @method static Builder<static>|Feed whereUpdatedAt($value)
 * @method static Builder<static>|Feed whereUpdatedBy($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 *
 * @mixin \Eloquent
 */
class Feed extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
    ];
}
