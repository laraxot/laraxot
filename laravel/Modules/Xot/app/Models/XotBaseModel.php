<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\Traits\RelationX;
use Modules\Xot\Traits\Updater;
use Webmozart\Assert\Assert;

/**
 * Class XotBaseModel.
 */
abstract class XotBaseModel extends EloquentModel
{
    /** @use HasXotFactory<Factory<static>> */
    use HasXotFactory;

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

    /** @var int */
    protected $perPage = 30;

    /** @var string */
    protected $connection = 'xot';

    /** @var list<string> */
    protected $appends = [];

    /** @var list<string> */
    protected $hidden = [
        // 'password'
    ];

    /**
     * Sibling model in the same `Models\` namespace as the calling leaf (`static::class`).
     *
     * Call from the leaf/base that owns the relation context, e.g. from `BaseScheda`:
     * `static::getClassName(CriteriOption::class)` → `Progressioni\Models\CriteriOption`
     * when `static` is `Progressioni\Models\Scheda`.
     *
     * Do **not** call as `CriteriOption::getClassName()` — LSB would stay on the prototype.
     *
     * @param class-string<EloquentModel> $fallback Prototype FQCN (basename reused; used if sibling missing)
     *
     * @return class-string<EloquentModel>
     */
    public static function getClassName(string $fallback): string
    {
        Assert::subclassOf($fallback, EloquentModel::class);

        $short = class_basename($fallback);
        $candidate = Str::of(static::class)
            ->beforeLast('\\')
            ->append('\\'.$short)
            ->toString();

        if (is_string($candidate) && '' !== $candidate && class_exists($candidate)) {
            Assert::subclassOf($candidate, EloquentModel::class);

            /* @var class-string<EloquentModel> $candidate */
            return $candidate;
        }

        /* @var class-string<EloquentModel> $fallback */
        return $fallback;
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
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
