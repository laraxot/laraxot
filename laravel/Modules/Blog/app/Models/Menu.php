<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Blog\Actions\ParentChilds\GetTreeOptions;
use Modules\Blog\Database\Factories\MenuFactory;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Collection;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * Modules\Blog\Models\Menu.
 *
 * @property int                       $id
 * @property string                    $name
 * @property array<string, mixed>|null $items
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property string|null               $updated_by
 * @property string|null               $created_by
 * @property Carbon|null               $deleted_at
 * @property string|null               $deleted_by
 *
 * @method static MenuFactory  factory($count = null, $state = [])
 * @method static Builder|Menu newModelQuery()
 * @method static Builder|Menu newQuery()
 * @method static Builder|Menu onlyTrashed()
 * @method static Builder|Menu query()
 * @method static Builder|Menu whereCreatedAt($value)
 * @method static Builder|Menu whereCreatedBy($value)
 * @method static Builder|Menu whereDeletedAt($value)
 * @method static Builder|Menu whereDeletedBy($value)
 * @method static Builder|Menu whereId($value)
 * @method static Builder|Menu whereItems($value)
 * @method static Builder|Menu whereName($value)
 * @method static Builder|Menu whereUpdatedAt($value)
 * @method static Builder|Menu whereUpdatedBy($value)
 * @method static Builder|Menu withTrashed()
 * @method static Builder|Menu withoutTrashed()
 *
 * @property string                      $title
 * @property int|null                    $parent_id
 * @property Collection<int, Menu>       $children
 * @property int|null                    $children_count
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property Menu|null                   $parent
 * @property Collection<int, Menu>       $ancestors                  The model's recursive parents.
 * @property int|null                    $ancestors_count
 * @property Collection<int, Menu>       $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                    $ancestors_and_self_count
 * @property Collection<int, Menu>       $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                    $bloodline_count
 * @property Collection<int, Menu>       $childrenAndSelf            The model's direct children and itself.
 * @property int|null                    $children_and_self_count
 * @property Collection<int, Menu>       $descendants                The model's recursive children.
 * @property int|null                    $descendants_count
 * @property Collection<int, Menu>       $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                    $descendants_and_self_count
 * @property Collection<int, Menu>       $parentAndSelf              The model's direct parent and itself.
 * @property int|null                    $parent_and_self_count
 * @property Menu|null                   $rootAncestor               The model's topmost parent.
 * @property Collection<int, Menu>       $siblings                   The parent's other children.
 * @property int|null                    $siblings_count
 * @property Collection<int, Menu>       $siblingsAndSelf            All the parent's children.
 * @property int|null                    $siblings_and_self_count
 * @property string                      $title
 * @property int|null                    $parent_id
 * @property Collection<int, Menu>       $children
 * @property int|null                    $children_count
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property Menu|null                   $parent
 * @property Collection<int, Menu>       $ancestors                  The model's recursive parents.
 * @property int|null                    $ancestors_count
 * @property Collection<int, Menu>       $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null                    $ancestors_and_self_count
 * @property Collection<int, Menu>       $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null                    $bloodline_count
 * @property Collection<int, Menu>       $childrenAndSelf            The model's direct children and itself.
 * @property int|null                    $children_and_self_count
 * @property Collection<int, Menu>       $descendants                The model's recursive children.
 * @property int|null                    $descendants_count
 * @property Collection<int, Menu>       $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null                    $descendants_and_self_count
 * @property Collection<int, Menu>       $parentAndSelf              The model's direct parent and itself.
 * @property int|null                    $parent_and_self_count
 * @property Menu|null                   $rootAncestor               The model's topmost parent.
 * @property Collection<int, Menu>       $siblings                   The parent's other children.
 * @property int|null                    $siblings_count
 * @property Collection<int, Menu>       $siblingsAndSelf            All the parent's children.
 * @property int|null                    $siblings_and_self_count
 *
 * @method static Collection<int, static>                                 all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu doesntHaveChildren()
 * @method static Collection<int, static>                                 get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu treeOf((Model|callable) $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereTitle($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu withGlobalScopes(array<string, mixed> $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 *
 * @property Profile|null $creator
 * @property Profile|null $updater
 *
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Collection<int, static> get($columns = ['*'])
 *
 * @property-read int $depth
 * @property-read string $path
 *
 * @method static Menu|null                                           first()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Menu> get()
 * @method static Menu                                                create(array<string, mixed> $attributes = [])
 * @method static Menu                                                firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Menu                                where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Menu                                whereNotNull((string|Expression) $columns)
 * @method static int                                                 count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class Menu extends BaseModel implements HasMedia
{
    use HasRecursiveRelationships;
    use InteractsWithMedia;

    /** @var list<string> */
    protected $fillable = [
        'title',
        'items',
        'parent_id',
    ];

    /**
     * @return array<int|string, string>
     */
    public static function getTreeMenuOptions(): array
    {
        $instance = new self();

        return app(GetTreeOptions::class)->execute($instance);

        // $categories = self::tree()->get()->toTree();
        // $results = [];
        // foreach ($categories as $cat) {
        //     $results[$cat->id] = $cat->title;
        //     foreach ($cat->children as $child) {
        //         $results[$child->id] = '--------->'.$child->title;
        //         foreach ($child->children as $cld) {
        //             $results[$cld->id] = '----------------->'.$cld->title;
        //             foreach ($cld->children as $c) {
        //                 $results[$c->id] = '------------------------->'.$c->title;
        //             }
        //         }
        //     }
        // }

        // return $results;
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'items' => 'array',
        ];
    }
}
