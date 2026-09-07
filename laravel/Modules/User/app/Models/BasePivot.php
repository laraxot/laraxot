<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
// //use Laravel\Scout\Searchable;
use Modules\Xot\Traits\Updater;

/**
 * Class BasePivot.
 */
abstract class BasePivot extends Pivot
{
    use Updater;

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @see https://laravel-news.com/6-eloquent-secrets
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public static $snakeAttributes = true;

    public $incrementing = true;

    protected $perPage = 30;

=======
=======
>>>>>>> 87273113 (.)
     *
     * @var bool
     */
    public static $snakeAttributes = true;

    /** @var bool */
    public $incrementing = true;

    /** @var int */
    protected $perPage = 30;

    /** @var string */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public static $snakeAttributes = true;

    public $incrementing = true;

    protected $perPage = 30;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected $connection = 'user';

    /** @var list<string> */
    protected $appends = [];

    /**
     * Undocumented variable.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    protected $primaryKey = 'id';

=======
=======
>>>>>>> 87273113 (.)
    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    protected $primaryKey = 'id';

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected $keyType = 'string';

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string', // must be string else primary key of related model will be typed as int
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
        ];
    }
}
