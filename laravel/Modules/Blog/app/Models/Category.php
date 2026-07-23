<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Modules\Blog\Actions\ParentChilds\GetTreeOptions;
use Modules\Blog\Database\Factories\CategoryFactory;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * Modules\Blog\Models\Category.
 *
 * @property int                         $id
 * @property string                      $title
 * @property string                      $slug
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property string|null                 $updated_by
 * @property string|null                 $created_by
 * @property Collection<int, Article>    $articles
 * @property int|null                    $articles_count
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 *
 * @method static CategoryFactory  factory($count = null, $state = [])
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category onlyTrashed()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereCreatedBy($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereSlug($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @method static Builder|Category whereUpdatedBy($value)
 * @method static Builder|Category withTrashed()
 * @method static Builder|Category withoutTrashed()
 *
 * @property array<string, mixed>|null                                            $description
 * @property Carbon|null                                                          $deleted_at
 * @property string|null                                                          $deleted_by
 * @property string|null                                                          $parent_id
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $children
 * @property int|null                                                             $children_count
 * @property Category|null                                                        $parent
 * @property mixed                                                                $post_counter
 * @property mixed                                                                $translations
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestors                  The model's recursive parents.
 * @property int|null                                                             $ancestors_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                                                             $ancestors_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                                                             $bloodline_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $childrenAndSelf            The model's direct children and itself.
 * @property int|null                                                             $children_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendants                The model's recursive children.
 * @property int|null                                                             $descendants_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                                                             $descendants_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $parentAndSelf              The model's direct parent and itself.
 * @property int|null                                                             $parent_and_self_count
 * @property Category|null                                                        $rootAncestor               The model's topmost parent.
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblings                   The parent's other children.
 * @property int|null                                                             $siblings_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblingsAndSelf            All the parent's children.
 * @property int|null                                                             $siblings_and_self_count
 * @property array<string, mixed>|null                                            $description
 * @property Carbon|null                                                          $deleted_at
 * @property string|null                                                          $deleted_by
 * @property string|null                                                          $parent_id
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $children
 * @property int|null                                                             $children_count
 * @property Category|null                                                        $parent
 * @property mixed                                                                $post_counter
 * @property mixed                                                                $translations
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestors                  The model's recursive parents.
 * @property int|null                                                             $ancestors_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                                                             $ancestors_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                                                             $bloodline_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $childrenAndSelf            The model's direct children and itself.
 * @property int|null                                                             $children_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendants                The model's recursive children.
 * @property int|null                                                             $descendants_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                                                             $descendants_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $parentAndSelf              The model's direct parent and itself.
 * @property int|null                                                             $parent_and_self_count
 * @property Category|null                                                        $rootAncestor               The model's topmost parent.
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblings                   The parent's other children.
 * @property int|null                                                             $siblings_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, Category> $siblingsAndSelf            All the parent's children.
 * @property int|null                                                             $siblings_and_self_count
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        treeOf((Model|callable) $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDeletedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDeletedBy($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereLocale(string $column, string $locale)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereLocales(string $column, array<string, mixed> $locales)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        withGlobalScopes(array<string, mixed> $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 *
 * @property string|null              $icon
 * @property Banner|null              $banner
 * @property Collection<int, Article> $categoryArticles
 * @property int|null                 $category_articles_count
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereIcon($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereJsonContainsLocales(string $column, array<string, mixed> $locales, ?mixed $value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 *
 * @property string|null $name
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Category        whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 *
 * @mixin Model
 *
 * @property-read int $depth
 * @property-read string $path
 *
 * @method static Category|null             first()
 * @method static Collection<int, Category> get()
 * @method static Category                  create(array<string, mixed> $attributes = [])
 * @method static Category                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Category  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Category  whereNotNull((string|Expression) $columns)
 * @method static int                       count(string $columns = '*')
 *
 * @property int $is_active
 * @property int $sort_order
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Category whereSortOrder($value)
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class Category extends BaseModel
{
    use HasRecursiveRelationships;
    use HasTranslations;

    /** @var array<int, string> */
    public array $translatable = [
        'title',
        'description',
    ];

    /**
     * Attributi assegnabili in massa (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'name',
        'picture',
        'description',
        'parent_id',
        'in_leaderboard',
        'icon',
    ];

    /**
     * @return array<int|string, string>
     */
    public static function getTreeCategoryOptions(): array
    {
        $instance = new self();

        return app(GetTreeOptions::class)->execute($instance);
    }

    /**
     * Get the path key to the item for the frontend only.
     */
    public function getFrontRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsToMany<Article, $this, Pivot, 'pivot'>
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * @return HasMany<Article, $this>
     */
    public function categoryArticles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    /**
     * @return HasOne<Banner, $this>
     */
    public function banner(): HasOne
    {
        return $this->hasOne(Banner::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'title' => 'string',
            'slug' => 'string',
            'name' => 'string',
            'picture' => 'string',
            'description' => 'string',
            'parent_id' => 'string',
            'in_leaderboard' => 'boolean',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'icon' => 'string',
        ];
    }
}
