<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Models\XotBaseModel;
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Actions\Factory\GetFactoryAction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Models\Traits\RelationX;
use Modules\Xot\Traits\Updater;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\Xot\Models\XotBaseModel;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Class BaseModel.
 */
<<<<<<< HEAD
<<<<<<< HEAD
abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'user';

=======
=======
>>>>>>> 87273113 (.)
abstract class BaseModel extends Model
{
    use HasFactory;
    use RelationX;
    use Updater;

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @see https://laravel-news.com/6-eloquent-secrets
     *
     * @var bool
     */
    public static $snakeAttributes = true;

    /** @var bool */
    public $incrementing = true;

    /** @var bool */
    public $timestamps = true;

    /** @var int */
    protected $perPage = 30;

    /** @var string */
    protected $connection = 'user';

    /** @var list<string> */
    protected $appends = [];

    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
    protected $keyType = 'string';

    /** @var list<string> */
    protected $hidden = [
        // 'password'
    ];

    /**
     * @see vendor/ laravel / framework / src / Illuminate / Database / Eloquent / Factories / HasFactory.php
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return app(GetFactoryAction::class)->execute(static::class);
    }

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'user';

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            // 'id' => 'string',
=======
            'id' => 'string',
>>>>>>> f548be94 (.)
=======
            'id' => 'string',
=======
            // 'id' => 'string',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'uuid' => 'string',
            'published_at' => 'datetime',
            'verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
        ];
    }
}
