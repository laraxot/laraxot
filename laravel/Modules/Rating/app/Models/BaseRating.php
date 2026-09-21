<?php

declare(strict_types=1);

namespace Modules\Rating\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Rating\Database\Factories\RatingFactory;
use Modules\Rating\Enums\RuleEnum;
use Modules\Rating\Models\Contracts\RatingContract;
use Modules\Xot\Contracts\HasRecursiveRelationshipsContract;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * Modules\Rating\Models\BaseRating.
 *
 * Classe base astratta per tutti i modelli Rating nei vari moduli.
 * Fornisce casts, fillable, scope e media conversions condivisi (DRY).
 * Utilizza HasRecursiveRelationships per l'albero genitore-figlio (adjacency list):
 * children(), parent(), ancestors(), descendants() arrivano dal trait e non si riscrivono.
 *
 * @see https://github.com/spatie/laravel-schemaless-attributes
 * @see /Modules/Rating/docs/schemaless-attributes-errors.md
 *
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 * @property RuleEnum                                          $rule
 *
 * @method static Builder|BaseRating newModelQuery()
 * @method static Builder|BaseRating newQuery()
 * @method static Builder|BaseRating query()
 * @method static Builder|BaseRating withExtraAttributes(array<string, mixed>|string $attributes = [], mixed $value = null)
 *
 * @property int             $id
 * @property int             $user_id
 * @property float           $value
 * @property string|null     $related_type
 * @property string|null     $created_by
 * @property string|null     $updated_by
 * @property string|null     $deleted_by
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 * @property int|null        $post_id
 * @property string|null     $title
 * @property string|null     $color
 * @property string|null     $icon
 * @property string|null     $txt
 * @property bool|null       $is_disabled
 * @property bool|null       $is_readonly
 * @property int|null        $order_column
 * @property int|null        $parent_id
 * @property Model|\Eloquent $linkedTo
 * @property BaseRatingMorph $pivot
 *
 * @method static Builder|BaseRating whereColor($value)
 * @method static Builder|BaseRating whereCreatedAt($value)
 * @method static Builder|BaseRating whereCreatedBy($value)
 * @method static Builder|BaseRating whereDeletedBy($value)
 * @method static Builder|BaseRating whereIcon($value)
 * @method static Builder|BaseRating whereId($value)
 * @method static Builder|BaseRating whereIsDisabled($value)
 * @method static Builder|BaseRating whereIsReadonly($value)
 * @method static Builder|BaseRating whereOrderColumn($value)
 * @method static Builder|BaseRating wherePostId($value)
 * @method static Builder|BaseRating whereRelatedType($value)
 * @method static Builder|BaseRating whereRule($value)
 * @method static Builder|BaseRating whereTitle($value)
 * @method static Builder|BaseRating whereTxt($value)
 * @method static Builder|BaseRating whereUpdatedAt($value)
 * @method static Builder|BaseRating whereUpdatedBy($value)
 *
 * @property MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property int|null                                          $media_count
 * @property ProfileContract|null                              $creator
 * @property ProfileContract|null                              $updater
 *
 * @mixin Eloquent
 *
 * @method static RatingFactory factory($count = null, $state = [])
 */
abstract class BaseRating extends BaseModel implements HasMedia, RatingContract, Sortable
{
    // L'albero dei rating vive su `parent_id`, che e' gia' la colonna di default del
    // trait: niente getParentKeyName() da riscrivere. Il trait porta parent() e
    // children() **piu'** il ricorsivo — ancestors(), descendants(), toTree() — che
    // due relazioni scritte a mano non possono dare.
    use HasRecursiveRelationships;
    use HasSlug;
    use InteractsWithMedia;
    use SortableTrait;

    /**
     * Etichetta del nodo nell'albero.
     *
     * Richiesta da {@see HasRecursiveRelationshipsContract} e usata da
     * `GetTreeOptionsByModelClassAction` per costruire le opzioni indentate del `Select`
     * su `parent_id`. Per un criterio l'etichetta e' il titolo.
     */
    public function getLabel(): string
    {
        $title = $this->getAttribute('title');

        if (is_string($title) && '' !== $title) {
            return $title;
        }

        $key = $this->getKey();

        return '#'.(is_scalar($key) ? (string) $key : '');
    }

    /** @var list<string> */
    protected $fillable = [
        'id',
        'extra_attributes',
        'parent_id',
        'title',
        'color',
        'txt',
        'rule',
        'is_disabled',
        'is_readonly',
        'order_column',
        'slug',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Scope to query by extra attributes.
     *
     * @see https://github.com/spatie/laravel-schemaless-attributes
     * @see /Modules/Rating/docs/schemaless-attributes-errors.md
     *
     * @param Builder<BaseRating>         $query
     * @param array<string, mixed>|string $attributes
     *
     * @return Builder<BaseRating>
     */
    public function scopeWithExtraAttributes(Builder $query, array|string $attributes = [], mixed $value = null): Builder
    {
        if (is_string($attributes) && null !== $value) {
            // Single attribute with value: withExtraAttributes('anno', 2024)
            return $query->where("extra_attributes->{$attributes}", $value);
        }

        if (is_array($attributes)) {
            // Multiple attributes: withExtraAttributes(['anno' => 2024, 'type' => 'foo'])
            foreach ($attributes as $key => $val) {
                $query = $query->where("extra_attributes->{$key}", $val);
            }
        }

        return $query;
    }

    /**
     * @return MorphTo<Model, BaseRating>
     */
    public function linkedTo(): MorphTo
    {
        return $this->morphTo('model'); // @phpstan-ignore return.type
    }

    /**
     * Register the conversions that should be performed.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('300x300')
            ->width(300)
            ->height(300);
        $this->addMediaConversion('150x150')
            ->width(151)
            ->height(151);
        $this->addMediaConversion('50x50')
            ->width(150)
            ->height(150);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @see https://github.com/spatie/laravel-schemaless-attributes
     * @see /Modules/Rating/docs/schemaless-attributes-errors.md
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'extra_attributes' => SchemalessAttributes::class,
            'rule' => RuleEnum::class,
            'is_disabled' => 'boolean',
            'is_readonly' => 'boolean',
        ];
    }
}
