<?php

declare(strict_types=1);

namespace Modules\Rating\Models\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Rating\Models\Rating;
use Webmozart\Assert\Assert;

/**
 * Trait HasRatingsTrait.
 *
 * @see Modules/Rating/docs/schemaless-attributes.md
 *
 * @phpstan-require-extends Model
 */
trait HasRatingsTrait
{
    /** @return class-string<Rating> */
    public function getRatingClass(): string
    {
        $moduleName = Str::of(static::class)
            ->after('Modules\\')
            ->before('\\')
            ->toString();
        $ratingClass = 'Modules\\'.$moduleName.'\Models\Rating';

        Assert::classExists($ratingClass);
        Assert::isAOf($ratingClass, Rating::class);

        return $ratingClass;
    }

    /**
     * Get ratings for this model.
     *
     * @return MorphToMany<Rating, $this, MorphPivot, 'pivot'>
     */
    public function ratings(): MorphToMany
    {
        return $this->morphToManyX(Rating::class, 'model', 'ratings', 'rating_morph');
    }

    /**
     * Get rating objectives with aggregated data.
     *
     * @return HasMany<Rating, $this>
     */
    public function ratingObjectives(): HasMany
    {
        $relatedClass = $this->getRatingClass();
        $userId = (int) Auth::id();

        return $this->hasMany($relatedClass, 'related_type', 'post_type')
            ->selectRaw(
                'ratings.*,
                count(value) as rating_count,
                avg(value) as rating_avg,
                sum(if(user_id = ?, value, 0)) AS rating_my',
                [$userId]
            )->leftJoin(
                'rating_morph',
                function (JoinClause $join): void {
                    $join->on('rating_morph.rating_id', 'ratings.id')
                        ->whereColumn('rating_morph.post_type', 'ratings.related_type')
                        ->where('rating_morph.post_id', $this->getKey());
                }
            )->groupBy('ratings.id')
            ->with('linkedTo');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithRating(Builder $query): Builder
    {
        return $query->leftJoin(
            'rating_morph',
            static function (JoinClause $join): void {
                $join->on('rating_morph.post_type', '=', 'ratings.related_type');
            }
        );
    }

    /**
     * Get my ratings for this model.
     *
     * @return MorphToMany<Rating, $this, MorphPivot, 'pivot'>
     */
    public function myRatings(): MorphToMany
    {
        return $this->morphToManyX(Rating::class, 'model', 'ratings', 'rating_morph')
            ->wherePivot('user_id', (string) Auth::id());
    }

    // ----- mutators -----
    // *
    /** @return Collection<array-key, mixed> */
    public function getMyRatingAttribute(): Collection
    {
        $myRatings = $this->myRatings;

        return $myRatings->pluck('pivot.rating', 'post_id');
    }

    /**
     * ----.
     */
    public function getRatingsAvgAttribute(?float $value): ?float
    {
        if ($value !== null) {
            return $value;
        }
        $value = $this->ratings->avg('pivot.rating');
        if ($value === null) {
            return 0.0;
        }

        // ✅ Persist con update chirurgico (salva SOLO questo campo, previene loop)
        if ($this->getKey() !== null) {
            $this->update(['ratings_avg' => $value]);
        }

        return $value;
    }

    public function getRatingsCountAttribute(?int $value): ?int
    {
        if ($value !== null) {
            return $value;
        }
        $value = $this->ratings->count();
        $this->ratings_count = $value;

        // Guard: modello deve avere PK per salvare
        if ($this->getKey() == null) {
            return $value;
        }

        // ✅ Persist con update chirurgico (salva SOLO questo campo, previene loop)
        $this->update(['ratings_count' => $value]);

        return $value;
    }

    /**
     * Get ratings filtered by extra_attributes.
     *
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Rating>
     */
    public function getRatingsWhere(array $filters): Collection
    {
        $query = $this->ratings();

        foreach ($filters as $key => $filterValue) {
            $query->where("extra_attributes->{$key}", $filterValue);
        }

        return $query->get();
    }

    /**
     * @param  array<string, mixed>  $where
     * @return Collection<int, Rating>
     */
    public function syncRatingsWhere(array $where): Collection
    {
        $ratingClass = $this->getRatingClass();
        $ratings = $ratingClass::query()
            ->withExtraAttributes($where)
            ->get();

        if ($ratings->isEmpty()) {
            return $ratings;
        }

        $this->ratings()->sync($ratings->modelKeys());
        $this->setRelation('ratings', $ratings);

        return $ratings;
    }

    // */
    /*
        public function setMyRatingAttribute($value){
        dddx($value);
        }
    */
    // ------ functions ------
    /**
     * @throws FileNotFoundException
     * @throws \ReflectionException
     */
    public function ratingAvgHtml(): string
    {
        $pivot_avg = $this->ratings_avg;
        $pivot_cout = $this->ratings_count;

        $msg = '<div class="rateit" data-rateit-value="'.$pivot_avg.'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
        $msg .= '('.$pivot_avg.') '.$pivot_cout.' Votes ';

        $rating_url = '#';
        $title = 'Vota '.(isset($this->title) ? (string) $this->title : '');

        $btn = '<button type="button" class="btn btn-red btn-danger" data-toggle="modal" data-target="#vueModal" data-title="'.$title.'" data-href="'.$rating_url.'">
        <span class="font-white"><i class="fa fa-star"></i> Vota ! </span>
        </button>';

        $btn_iframe = '<button type="button" class="btn btn-red btn-danger" data-toggle="modal" data-target="#vueIframeModal" data-title="'.$title.'" data-href="'.$rating_url.'">
        <span class="font-white"><i class="fa fa-star"></i> Vota ! </span>
        </button>';

        return $msg.$btn.$btn_iframe;
    }

    /** @return array<string, string> */
    public function getRatingsRules(string $prefix, string $postfix): array
    {
        $rows = $this->ratings;
        $rules = $rows->mapWithKeys(function ($row) {
            $ruleValue = $row->rule instanceof \BackedEnum ? (string) $row->rule->value : (string) $row->rule;

            return [$row->id => $ruleValue];
        })->toArray();

        $rules = Arr::prependKeysWith($rules, $prefix);
        $res = [];
        foreach ($rules as $key => $ruleValue) {
            $keyWithPostfix = $key.$postfix;
            $ruleStr = (string) $ruleValue;

            // ✅ Se la regola è numeric o integer, aggiungi nullable se non presente
            if (Str::contains($ruleStr, ['numeric', 'integer']) && ! Str::contains($ruleStr, 'nullable')) {
                $ruleStr = 'nullable|'.$ruleStr;
            }

            $res[$keyWithPostfix] = $ruleStr;
        }

        return $res;
    }

    /** @return array<string, string> */
    public function getRatingsValidationAttributes(string $prefix, string $postfix): array
    {
        $rows = $this->ratings;
        $res = [];
        foreach ($rows as $row) {
            $keyWithPostfix = $prefix.$row->id.$postfix;
            $res[$keyWithPostfix] = (string) $row->title;
        }

        return $res;
    }
}
