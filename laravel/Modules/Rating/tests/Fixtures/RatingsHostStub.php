<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Rating\Models\AbstractRatingsHost;
use Modules\Rating\Models\Rating;

/**
 * Host di test (richiesto da XotBaseModel::getClassName()).
 * Caricato solo dai Unit test via require_once.
 */
class RatingsHostStub extends AbstractRatingsHost
{
    protected $table = 'ratings_host_stub';

    /** @var list<string> */
    protected $fillable = ['title', 'ratings_avg', 'ratings_count', 'post_type'];

    /** @var MorphToMany<Rating, $this, MorphPivot, 'pivot'>|null */
    public ?MorphToMany $forcedMorph = null;

    /** @var HasMany<Rating, $this>|null */
    public ?HasMany $forcedHasMany = null;

    /**
     * @template TRelatedModel of Model
     *
     * @param class-string<TRelatedModel> $related
     *
     * @return MorphToMany<TRelatedModel, $this, MorphPivot, 'pivot'>
     */
    public function morphToManyX(
        string $related,
        string $name,
        ?string $_table = null,
        ?string $foreignPivotKey = null,
        ?string $relatedPivotKey = null,
        ?string $parentKey = null,
        ?string $relatedKey = null,
        ?string $relation = null,
        bool $inverse = false,
    ): MorphToMany {
        if ($this->forcedMorph instanceof MorphToMany) {
            /** @var MorphToMany<TRelatedModel, $this, MorphPivot, 'pivot'> $forcedMorph */
            $forcedMorph = $this->forcedMorph;

            return $forcedMorph;
        }

        return parent::morphToManyX(
            $related,
            $name,
            $_table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relation,
            $inverse,
        );
    }

    /**
     * @template TRelatedModel of Model
     *
     * @param class-string<TRelatedModel> $related
     *
     * @return HasMany<TRelatedModel, $this>
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        if ($this->forcedHasMany instanceof HasMany) {
            /** @var HasMany<TRelatedModel, $this> $forcedHasMany */
            $forcedHasMany = $this->forcedHasMany;

            return $forcedHasMany;
        }

        return parent::hasMany($related, $foreignKey, $localKey);
    }
}
