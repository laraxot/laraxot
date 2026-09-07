<?php

declare(strict_types=1);

namespace Modules\Rating\Models\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Rating\Models\Rating;

// ------ traits ---

/**
 * Trait RatingTrait.
 */
/** @phpstan-ignore trait.unused */
trait RatingTrait
{
    /**
     * @return MorphToMany
     */
    public function ratings()
    {
        return $this->morphRelated(Rating::class);
    }

    /**
     * @return HasMany
     */
    public function ratingObjectives()
    {
        $related = Rating::class;
        $userId = Auth::id();

        return $this->hasMany($related, 'related_type', 'post_type')

            ->selectRaw(
                'ratings.*,
                count(value) as rating_count,
                avg(value) as rating_avg,
                sum(if(user_id="'.$userId.'",value,0)) AS rating_my
                '
            )->leftJoin(
                'rating_morph',
                function ($join): void {
                    $join->on('rating_morph.rating_id', 'ratings.id')
                        ->whereRaw('rating_morph.post_type = ratings.related_type')
                        ->where('rating_morph.post_id', $this->id);
                }
            )->groupBy('ratings.id')
            ->with('post');
    }

    /**
     * Scope a query to only include popular users.
     */
    public function scopeWithRating(Builder $query): Builder
    {
        return $query->leftJoin(
            'rating_morph',
            function ($join): void {
                $join->on('rating_morph.post_type = ratings.related_type');
            }
        );
    }

    /**
     * @return MorphToMany
     */
    public function myRatings()
    {
        return $this->morphRelated(Rating::class)
            ->wherePivot('user_id', Auth::id());
    }

    // ----- mutators -----
    // *
    /**
     * @return Collection
     */
    public function getMyRatingAttribute()
    {
        $my = $this->myRatings;

        return $my->pluck('pivot.rating', 'post_id');
    }

    /**
     * ----.
     */
    public function getRatingsAvgAttribute(?float $value): ?float
    {
        if (null !== $value) {
            return $value;
        }
        $value = $this->ratings->avg('pivot.rating');
        if (null !== $value) {
            // ✅ Persist con update chirurgico (salva SOLO questo campo, previene loop)
            if (null !== $this->getKey()) {
                $this->update(['ratings_avg' => $value]);
            }
        }

        return $value;
    }

    public function getRatingsCountAttribute(?int $value): ?int
    {
        if (null !== $value) {
            return $value;
        }
        // Method Illuminate\Support\Collection<int,Modules\Rating\Models\Rating>::count() invoked with 1 parameter, 0 required.
        // $value = $this->ratings->count('pivot.rating');
        $value = $this->ratings->count(); // ?? forse fare filtro
        $this->ratings_count = $value;

        // Guard: modello deve avere PK per salvare
        if (null == $this->getKey()) {
            return $value;
        }

        // ✅ Persist con update chirurgico (salva SOLO questo campo, previene loop)
        $this->update(['ratings_count' => $value]);

        return $value;
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
        // Method Illuminate\Support\Collection<int,Modules\Rating\Models\Rating>::count() invoked with 1 parameter, 0 required.
        // $pivotAvg = $ratings->avg('pivot.rating');
        $pivotAvg = $this->ratings_avg;
        // $pivotCount = $ratings->count('pivot.rating');
        $pivotCount = $this->ratings_count;

        $msg = '<div class="rateit" data-rateit-value="'.$pivotAvg.'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
        $msg .= '('.$pivotAvg.') '.$pivotCount.' Votes ';

        // $ratingUrl = Panel::make()->get($this)->relatedUrl('my_rating','index_edit');
        // $ratingUrl = Panel::make()->get($this)->url('show').'?_act=rate';
        // $ratingUrl = Panel::make()->get($this)->itemAction('rate_it')->url();
        $ratingUrl = '#';
        // http://geek.local/public_html/it/article/prova-articolo?_act=rate
        /*
        return $msg.'<a data-href="'.$ratingUrl.'" class="btn btn-danger" data-toggle="modal" data-target="#myModalAjax" data-title="Rate it">
        Rate It </a>';
        */
        $title = 'Vota '.$this->title;

        $btn = '<button type="button" class="btn btn-red btn-danger" data-toggle="modal" data-target="#vueModal" data-title="'.$title.'" data-href="'.$ratingUrl.'">
        <span class="font-white"><i class="fa fa-star"></i> Vota ! </span>
        </button>';

        $btnIframe = '<button type="button" class="btn btn-red btn-danger" data-toggle="modal" data-target="#vueIframeModal" data-title="'.$title.'" data-href="'.$ratingUrl.'">
        <span class="font-white"><i class="fa fa-star"></i> Vota ! </span>
        </button>';

        return $msg.$btn.$btnIframe;
    }
}
