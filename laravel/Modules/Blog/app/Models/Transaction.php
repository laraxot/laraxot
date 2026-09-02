<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Modules\Blog\Database\Factories\TransactionFactory;
use Modules\Media\Models\Media;
use Modules\Rating\Models\RatingMorph;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Webmozart\Assert\Assert;

/**
 * @property int         $id
 * @property string|null $model_type
 * @property int|null    $model_id
 * @property int|null    $credits
 * @property string|null $user_id
 * @property string|null $note
 * @property string|null $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static TransactionFactory  factory($count = null, $state = [])
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction onlyTrashed()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereCreatedBy($value)
 * @method static Builder|Transaction whereCredits($value)
 * @method static Builder|Transaction whereDate($value)
 * @method static Builder|Transaction whereDeletedAt($value)
 * @method static Builder|Transaction whereDeletedBy($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereModelId($value)
 * @method static Builder|Transaction whereModelType($value)
 * @method static Builder|Transaction whereNote($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @method static Builder|Transaction whereUpdatedBy($value)
 * @method static Builder|Transaction whereUserId($value)
 * @method static Builder|Transaction withTrashed()
 * @method static Builder|Transaction withoutTrashed()
 *
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property float|null                  $stocks_count
 * @property float|null                  $stocks_value
 *
 * @method static Builder<static>|Transaction  whereStocksCount($value)
 * @method static Builder<static>|Transaction  whereStocksValue($value)
 * @method static Transaction|null             first()
 * @method static Collection<int, Transaction> get()
 * @method static Transaction                  create(array<string, mixed> $attributes = [])
 * @method static Transaction                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Transaction  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Transaction  whereNotNull((string|Expression) $columns)
 * @method static int                          count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class Transaction extends BaseModel
{
    protected $connection = 'blog';

    protected $fillable = [
        'date',
        'model_type',
        'model_id',
        'user_id',
        'credits',
        'note',
        'stocks_count',
        'stocks_value',
    ];

    public function getRatingMorph(): RatingMorph
    {
        Assert::notNull($ratingMorph = RatingMorph::where('rating_id', $this->model_id)->first());

        return $ratingMorph;
    }
}
