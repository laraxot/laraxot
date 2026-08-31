<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Modules\Cms\Models{
/**
 * ---.
 *
 * @property string                       $id
 * @property array<array-key, mixed>|null $title
 * @property array<array-key, mixed>|null $description
 * @property string|null                  $slug
 * @property string|null                  $disk
 * @property array<array-key, mixed>|null $attachment
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property ProfileContract|null         $creator
 * @property MediaCollection<int, Media>  $media
 * @property int|null                     $media_count
 * @property mixed                        $translations
 * @property ProfileContract|null         $updater
 * @method static Builder<static>|Attachment newModelQuery()
 * @method static Builder<static>|Attachment newQuery()
 * @method static Builder<static>|Attachment query()
 * @method static Builder<static>|Attachment whereAttachment($value)
 * @method static Builder<static>|Attachment whereCreatedAt($value)
 * @method static Builder<static>|Attachment whereCreatedBy($value)
 * @method static Builder<static>|Attachment whereDescription($value)
 * @method static Builder<static>|Attachment whereDisk($value)
 * @method static Builder<static>|Attachment whereId($value)
 * @method static Builder<static>|Attachment whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Attachment whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Attachment whereLocale(string $column, string $locale)
 * @method static Builder<static>|Attachment whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Attachment whereSlug($value)
 * @method static Builder<static>|Attachment whereTitle($value)
 * @method static Builder<static>|Attachment whereUpdatedAt($value)
 * @method static Builder<static>|Attachment whereUpdatedBy($value)
 * @method static static|null                firstWhere(string $column, mixed $operator = null, mixed $value = null)
 * @property ProfileContract|null $deleter
 * @method static AttachmentFactory                factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Attachment extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Conf.
 *
 * @property int         $id
 * @property string|null $name
 * @method static Builder<static>|Conf newModelQuery()
 * @method static Builder<static>|Conf newQuery()
 * @method static Builder<static>|Conf query()
 * @method static Builder<static>|Conf whereId($value)
 * @method static Builder<static>|Conf whereName($value)
 * @method static int                  count()
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static ConfFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Conf extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Menu.
 *
 * @property string                 $id
 * @property string|null            $title
 * @property array<int, mixed>|null $items
 * @property int|null               $parent_id
 * @property string|null            $created_at
 * @property string|null            $updated_at
 * @property string|null            $created_by
 * @property string|null            $updated_by
 * @property Collection<int, Menu>  $children
 * @property int|null               $children_count
 * @property ProfileContract|null   $creator
 * @property Menu|null              $parent
 * @property ProfileContract|null   $updater
 * @property int                    $depth
 * @property string                 $path
 * @property Collection<int, Menu>  $ancestors      The model's recursive parents.
 * @property string                 $id
 * @property string|null            $title
 * @property int|null               $parent_id
 * @property string|null            $created_at
 * @property string|null            $updated_at
 * @property string|null            $created_by
 * @property string|null            $updated_by
 * @property Collection<int, Menu>  $children
 * @property int|null               $children_count
 * @property ProfileContract|null   $creator
 * @property Menu|null              $parent
 * @property ProfileContract|null   $updater
 * @property int                    $depth
 * @property string                 $path
 * @property Collection<int, Menu>  $ancestors      The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read Collection<int, Menu> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read Collection<int, Menu> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read Collection<int, Menu> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read Collection<int, Menu> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read Collection<int, Menu> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read Collection<int, Menu> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read Menu|null $rootAncestor The model's topmost parent.
 * @property-read Collection<int, Menu> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read Collection<int, Menu> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static Builder<static>|Menu    breadthFirst()
 * @method static Builder<static>|Menu    depthFirst()
 * @method static Builder<static>|Menu    doesntHaveChildren()
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static Builder<static>|Menu    getExpressionGrammar()
 * @method static Builder<static>|Menu    hasChildren()
 * @method static Builder<static>|Menu    hasParent()
 * @method static Builder<static>|Menu    isLeaf()
 * @method static Builder<static>|Menu    isRoot()
 * @method static Builder<static>|Menu    newModelQuery()
 * @method static Builder<static>|Menu    newQuery()
 * @method static Builder<static>|Menu    query()
 * @method static Builder<static>|Menu    tree($maxDepth = null)
 * @method static Builder<static>|Menu    treeOf((Model|callable) $constraint, $maxDepth = null)
 * @method static Builder<static>|Menu    whereCreatedAt($value)
 * @method static Builder<static>|Menu    whereCreatedBy($value)
 * @method static Builder<static>|Menu    whereDepth($operator, $value = null)
 * @method static Builder<static>|Menu    whereId($value)
 * @method static Builder<static>|Menu    whereParentId($value)
 * @method static Builder<static>|Menu    whereTitle($value)
 * @method static Builder<static>|Menu    whereUpdatedAt($value)
 * @method static Builder<static>|Menu    whereUpdatedBy($value)
 * @method static Builder<static>|Menu    whereDepth($operator, $value = null)
 * @method static Builder<static>|Menu    withGlobalScopes(array<string, mixed> $scopes)
 * @method static Builder<static>|Menu    withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static static                  firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                  create(array<string, mixed> $attributes = [])
 * @method static static                  updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static Builder<static>|Menu    delete()
 * @method static Builder<static>|Menu    where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Menu    whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Menu    whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Menu    whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Menu    orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Menu    latest($column = 'created_at')
 * @method static Builder<static>|Menu    oldest($column = 'created_at')
 * @method static Builder<static>|Menu    limit($value)
 * @method static Builder<static>|Menu    take($value)
 * @method static Builder<static>|Menu    skip($value)
 * @method static Builder<static>|Menu    offset($value)
 * @method static int                     count()
 * @method static int                     max($column)
 * @method static int                     min($column)
 * @method static int                     sum($column)
 * @method static float                   avg($column)
 * @method static mixed                   pluck($column, $key = null)
 * @method static Builder<static>|Menu    join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Menu    having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Menu    orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Menu    whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Menu    whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Menu    whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Menu    whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Menu    whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Menu    with($relations)
 * @method static Builder<static>|Menu    without($relations)
 * @method static Builder<static>|Menu    withCount($relations)
 * @method static Builder<static>|Menu    withSum($relation, $column)
 * @method static Builder<static>|Menu    withAvg($relation, $column)
 * @method static Builder<static>|Menu    withMin($relation, $column)
 * @method static Builder<static>|Menu    withMax($relation, $column)
 * @method static Builder<static>|Menu    findOrFail($id, $columns = ['*'])
 * @method static static                  findOrFail($id, $columns = ['*'])
 * @method static static                  firstOrFail($columns = ['*'])
 * @method static static                  update($attributes)
 * @method static int                     increment($column, $amount = 1, $extra = [])
 * @method static int                     decrement($column, $amount = 1, $extra = [])
 * @method static bool                    truncate()
 * @method static static                  destroy($ids)
 * @method static static                  restore()
 * @method static static                  forceDelete()
 * @method static static                  onlyTrashed()
 * @method static static                  withTrashed()
 * @method static static                  withoutTrashed()
 * @property ProfileContract|null $deleter
 * @method static MenuFactory                      factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 */
	class Menu extends \Eloquent implements \Modules\Xot\Contracts\HasRecursiveRelationshipsContract {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Module.
 *
 * @property string               $id
 * @property string|null          $name
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Module newModelQuery()
 * @method static Builder<static>|Module newQuery()
 * @method static Builder<static>|Module query()
 * @method static Builder<static>|Module whereId($value)
 * @method static Builder<static>|Module whereName($value)
 * @method static int                    count()
 * @property ProfileContract|null $deleter
 * @method static ModuleFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Module extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Page.
 *
 * @property string $id
 * @method static array<int, array<string, mixed>>            getMiddlewareBySlug(string $slug)
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @property string                       $id
 * @property array<array-key, mixed>|null $title
 * @property string|null                  $slug
 * @property array<array-key, mixed>|null $middleware
 * @property string|null                  $content
 * @property string|null                  $description
 * @property array<array-key, mixed>|null $blocks
 * @property array<array-key, mixed>|null $content_blocks
 * @property array<array-key, mixed>|null $sidebar_blocks
 * @property array<array-key, mixed>|null $footer_blocks
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property string|null                  $deleted_by
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 * @property ProfileContract|null         $deleter
 * @property mixed                        $translations
 * @method static Builder<static>|Page                                  newModelQuery()
 * @method static Builder<static>|Page                                  newQuery()
 * @method static Builder<static>|Page                                  query()
 * @method static Builder<static>|Page                                  whereContent($value)
 * @method static Builder<static>|Page                                  whereContentBlocks($value)
 * @method static Builder<static>|Page                                  whereCreatedAt($value)
 * @method static Builder<static>|Page                                  whereCreatedBy($value)
 * @method static Builder<static>|Page                                  whereDescription($value)
 * @method static Builder<static>|Page                                  whereFooterBlocks($value)
 * @method static Builder<static>|Page                                  whereId($value)
 * @method static Builder<static>|Page                                  whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereLocale(string $column, string $locale)
 * @method static Builder<static>|Page                                  whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Page                                  whereMiddleware($value)
 * @method static Builder<static>|Page                                  whereSidebarBlocks($value)
 * @method static Builder<static>|Page                                  whereSlug($value)
 * @method static Builder<static>|Page                                  whereTitle($value)
 * @method static Builder<static>|Page                                  whereUpdatedAt($value)
 * @method static Builder<static>|Page                                  whereUpdatedBy($value)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static static|null                                           first(array|string $columns = ['*'])
 * @method static static|null                                           firstWhere(string $column, mixed $operator = null, mixed $value = null)
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static Builder<static>|Page                                  delete()
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static int                                                   increment($column, $amount = 1, $extra = [])
 * @method static int                                                   decrement($column, $amount = 1, $extra = [])
 * @method static bool                                                  truncate()
 * @method static static                                                destroy($ids)
 * @method static static                                                restore()
 * @method static static                                                forceDelete()
 * @method static static                                                onlyTrashed()
 * @method static static                                                withTrashed()
 * @method static static                                                withoutTrashed()
 * @method static Collection<int, static>                               all($columns = ['*'])
 * @method static Collection<int, static>                               get($columns = ['*'])
 * @method static static|null                                           first($columns = ['*'])
 * @method static static|null                                           find($id, $columns = ['*'])
 * @method static Builder<static>|Page                                  newModelQuery()
 * @method static Builder<static>|Page                                  newQuery()
 * @method static Builder<static>|Page                                  query()
 * @method static Builder<static>|Page                                  whereContent($value)
 * @method static Builder<static>|Page                                  whereContentBlocks($value)
 * @method static Builder<static>|Page                                  whereCreatedAt($value)
 * @method static Builder<static>|Page                                  whereCreatedBy($value)
 * @method static Builder<static>|Page                                  whereDescription($value)
 * @method static Builder<static>|Page                                  whereFooterBlocks($value)
 * @method static Builder<static>|Page                                  whereId($value)
 * @method static Builder<static>|Page                                  whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                                  whereLocale(string $column, string $locale)
 * @method static Builder<static>|Page                                  whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Page                                  whereMiddleware($value)
 * @method static Builder<static>|Page                                  whereSidebarBlocks($value)
 * @method static Builder<static>|Page                                  whereSlug($value)
 * @method static Builder<static>|Page                                  whereTitle($value)
 * @method static Builder<static>|Page                                  whereUpdatedAt($value)
 * @method static Builder<static>|Page                                  whereUpdatedBy($value)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static static|null                                           first(array|string $columns = ['*'])
 * @method static static|null                                           firstWhere(string $column, mixed $operator = null, mixed $value = null)
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static Builder<static>|Page                                  delete()
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static Builder<static>|Page                                  where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereNull($columns, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotNull($columns, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotBetween($column, array<int, mixed> $values, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereDate($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereMonth($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereDay($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereYear($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereTime($column, string $operator, string $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereColumn($column, string $operator, mixed $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  orderBy($column, $direction = 'asc')
 * @method static Builder<static>|Page                                  latest($column = 'created_at')
 * @method static Builder<static>|Page                                  oldest($column = 'created_at')
 * @method static Builder<static>|Page                                  limit($value)
 * @method static Builder<static>|Page                                  take($value)
 * @method static Builder<static>|Page                                  skip($value)
 * @method static Builder<static>|Page                                  offset($value)
 * @method static int                                                   count()
 * @method static int                                                   max($column)
 * @method static int                                                   min($column)
 * @method static int                                                   sum($column)
 * @method static float                                                 avg($column)
 * @method static mixed                                                 pluck($column, $key = null)
 * @method static Builder<static>|Page                                  join($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  crossJoin($table, $first, $operator = null, $second = null)
 * @method static Builder<static>|Page                                  having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  orWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereNotExists($callback, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereHas($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereDoesntHave($relation, $operator = '<', $count = 1, $boolean = 'and', $callback = null)
 * @method static Builder<static>|Page                                  whereJsonContains($column, mixed $value, $boolean = 'and', $not = false)
 * @method static Builder<static>|Page                                  whereJsonLength($column, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonPath($path, $operator, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  whereJsonOverlaps($column, $value, $boolean = 'and')
 * @method static Builder<static>|Page                                  with($relations)
 * @method static Builder<static>|Page                                  without($relations)
 * @method static Builder<static>|Page                                  withCount($relations)
 * @method static Builder<static>|Page                                  withSum($relation, $column)
 * @method static Builder<static>|Page                                  withAvg($relation, $column)
 * @method static Builder<static>|Page                                  withMin($relation, $column)
 * @method static Builder<static>|Page                                  withMax($relation, $column)
 * @method static Builder<static>|Page                                  findOrFail($id, $columns = ['*'])
 * @method static static                                                findOrFail($id, $columns = ['*'])
 * @method static static                                                firstOrFail($columns = ['*'])
 * @method static static                                                firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                create(array<string, mixed> $attributes = [])
 * @method static static                                                updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                                                update($attributes)
 * @method static int                                                   increment($column, $amount = 1, $extra = [])
 * @method static int                                                   decrement($column, $amount = 1, $extra = [])
 * @method static bool                                                  truncate()
 * @method static static                                                destroy($ids)
 * @method static static                                                restore()
 * @method static static                                                forceDelete()
 * @method static static                                                onlyTrashed()
 * @method static static                                                withTrashed()
 * @method static static                                                withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static static|null                                           first($columns = ['*'])
 * @method static static|null                                           find($id, $columns = ['*'])
 * @property ProfileContract|null $deleter
 * @method static PageFactory factory($count = null, $state = [])
 * @property array<array-key, mixed>|null $blocks
 * @method static Builder<static>|Page                        whereBlocks($value)
 * @method array<int, array<string, mixed>>            getSushiRows()
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @method static array<int, string>                          getMiddlewareBySlug(string $slug)
 * @method static PageFactory                                 factory($count = null, $state = [])
 * @method static Builder<static>|Page                        newModelQuery()
 * @method static Builder<static>|Page                        newQuery()
 * @method static Builder<static>|Page                        query()
 * @method static Builder<static>|Page                        whereBlocks($value)
 * @method static Builder<static>|Page                        whereContent($value)
 * @method static Builder<static>|Page                        whereContentBlocks($value)
 * @method static Builder<static>|Page                        whereCreatedAt($value)
 * @method static Builder<static>|Page                        whereCreatedBy($value)
 * @method static Builder<static>|Page                        whereDescription($value)
 * @method static Builder<static>|Page                        whereFooterBlocks($value)
 * @method static Builder<static>|Page                        whereId($value)
 * @method static Builder<static>|Page                        whereMiddleware($value)
 * @method static Builder<static>|Page                        whereSidebarBlocks($value)
 * @method static Builder<static>|Page                        whereSlug($value)
 * @method static Builder<static>|Page                        whereTitle($value)
 * @method static Builder<static>|Page                        whereUpdatedAt($value)
 * @method static Builder<static>|Page                        whereUpdatedBy($value)
 * @method static Builder<static>|Page                        whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                        whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Page                        whereLocale(string $column, string $locale)
 * @method static Builder<static>|Page                        whereLocales(string $column, array<int, string> $locales)
 * @method static Collection<int, static>                     all($columns = ['*'])
 * @method static Collection<int, static>                     get($columns = ['*'])
 * @method static static|null                                 first($columns = ['*'])
 * @method static static|null                                 find($id, $columns = ['*'])
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @method static array<int, string>                          getMiddlewareBySlug(string $slug)
 * @method array<int, array<string, mixed>>            getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Page extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\PageContent.
 *
 * @property string                       $id
 * @property array<array-key, mixed>|null $name
 * @property string|null                  $slug
 * @property array<array-key, mixed>|null $blocks
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property ProfileContract|null         $creator
 * @property mixed                        $translations
 * @property ProfileContract|null         $updater
 * @method static Builder<static>|PageContent newModelQuery()
 * @method static Builder<static>|PageContent newQuery()
 * @method static Builder<static>|PageContent query()
 * @method static Builder<static>|PageContent whereBlocks($value)
 * @method static Builder<static>|PageContent whereCreatedAt($value)
 * @method static Builder<static>|PageContent whereCreatedBy($value)
 * @method static Builder<static>|PageContent whereId($value)
 * @method static Builder<static>|PageContent whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|PageContent whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|PageContent whereLocale(string $column, string $locale)
 * @method static Builder<static>|PageContent whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|PageContent whereName($value)
 * @method static Builder<static>|PageContent whereSlug($value)
 * @method static Builder<static>|PageContent whereUpdatedAt($value)
 * @method static Builder<static>|PageContent whereUpdatedBy($value)
 * @method static int                         count()
 * @property ProfileContract|null $deleter
 * @method static PageContentFactory               factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class PageContent extends \Eloquent {}
}

namespace Modules\Cms\Models{
/**
 * Modules\Cms\Models\Section.
 *
 * @property string $id
 * @method static array<string, \Modules\Cms\Datas\BlockData> getBlocksBySlug(string $slug, ?string $side = null)
 * @property string                       $id
 * @property array<array-key, mixed>|null $name
 * @property string|null                  $slug
 * @property array<array-key, mixed>|null $blocks
 * @property Carbon|null                  $created_at
 * @property Carbon|null                  $updated_at
 * @property string|null                  $created_by
 * @property string|null                  $updated_by
 * @property ProfileContract|null         $creator
 * @property mixed                        $translations
 * @property ProfileContract|null         $updater
 * @method static Builder<static>|Section newModelQuery()
 * @method static Builder<static>|Section newQuery()
 * @method static Builder<static>|Section query()
 * @method static Builder<static>|Section whereBlocks($value)
 * @method static Builder<static>|Section whereCreatedAt($value)
 * @method static Builder<static>|Section whereCreatedBy($value)
 * @method static Builder<static>|Section whereId($value)
 * @method static Builder<static>|Section whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Section whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|Section whereLocale(string $column, string $locale)
 * @method static Builder<static>|Section whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|Section whereName($value)
 * @method static Builder<static>|Section whereSlug($value)
 * @method static Builder<static>|Section whereUpdatedAt($value)
 * @method static Builder<static>|Section whereUpdatedBy($value)
 * @method static int                     count()
 * @method static Builder<static>|Section where($column, $operator = null, $value = null, $boolean = 'and')
 * @property ProfileContract|null $deleter
 * @method static SectionFactory                   factory($count = null, $state = [])
 * @method array<int, array<string, mixed>> getSushiRows()
 * @mixin \Eloquent
 * @property-read array $translatable_columns_from
 */
	class Section extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * Modello per i comuni italiani con Sushi.
 *
 * Implementa il pattern Facade per fornire un'interfaccia unificata a tutti i dati geografici:
 * regioni, province, città, CAP, codici ISTAT, ecc.
 * Tutti i dati sono estratti da file JSON e gestiti tramite Sushi.
 *
 * @property string|null $nome
 * @property float|null $codice
 * @property array<array-key, mixed>|null $zona
 * @property array<array-key, mixed>|null $regione
 * @property array<array-key, mixed>|null $provincia
 * @property string|null $sigla
 * @property string|null $codiceCatastale
 * @property array<array-key, mixed>|null $cap
 * @property int|null $popolazione
 * @property int|null $id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $content
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @method static Builder<static>|Comune newModelQuery()
 * @method static Builder<static>|Comune newQuery()
 * @method static Builder<static>|Comune query()
 * @method static Builder<static>|Comune whereCap($value)
 * @method static Builder<static>|Comune whereCodice($value)
 * @method static Builder<static>|Comune whereCodiceCatastale($value)
 * @method static Builder<static>|Comune whereContent($value)
 * @method static Builder<static>|Comune whereCreatedAt($value)
 * @method static Builder<static>|Comune whereCreatedBy($value)
 * @method static Builder<static>|Comune whereId($value)
 * @method static Builder<static>|Comune whereNome($value)
 * @method static Builder<static>|Comune wherePopolazione($value)
 * @method static Builder<static>|Comune whereProvincia($value)
 * @method static Builder<static>|Comune whereRegione($value)
 * @method static Builder<static>|Comune whereSigla($value)
 * @method static Builder<static>|Comune whereSlug($value)
 * @method static Builder<static>|Comune whereTitle($value)
 * @method static Builder<static>|Comune whereUpdatedAt($value)
 * @method static Builder<static>|Comune whereUpdatedBy($value)
 * @method static Builder<static>|Comune whereZona($value)
 * @property ProfileContract|null $deleter
 * @method static ComuneFactory factory($count = null, $state = [])
 * @property int|null $altitudine
 * @property string|null $codice_catastale
 * @property float|null $lat
 * @property float|null $lng
 * @property string|null $sigla_provincia
 * @property float|null $superficie
 * @property string|null $zona_altimetrica
 * @method static Builder<static>|Comune whereAltitudine($value)
 * @method static Builder<static>|Comune whereLat($value)
 * @method static Builder<static>|Comune whereLng($value)
 * @method static Builder<static>|Comune whereSiglaProvincia($value)
 * @method static Builder<static>|Comune whereSuperficie($value)
 * @method static Builder<static>|Comune whereZonaAltimetrica($value)
 * @mixin \Eloquent
 */
	class Comune extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property int|null $region_id
 * @property int|null $province_id
 * @property int $id
 * @property string|null $name
 * @property array<array-key, mixed>|null $postal_code
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|Locality newModelQuery()
 * @method static Builder<static>|Locality newQuery()
 * @method static Builder<static>|Locality query()
 * @method static Builder<static>|Locality whereId($value)
 * @method static Builder<static>|Locality whereName($value)
 * @method static Builder<static>|Locality wherePostalCode($value)
 * @method static Builder<static>|Locality whereProvinceId($value)
 * @method static Builder<static>|Locality whereRegionId($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Geo\Database\Factories\LocalityFactory factory($count = null, $state = [])
 */
	class Locality extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property int|null $region_id
 * @property int $id
 * @property string|null $name
 * @property-read ProfileContract|null $creator
 * @property-read Collection<int, Locality> $localities
 * @property-read int|null $localities_count
 * @property-read Region|null $region
 * @property-read ProfileContract|null $updater
 * @method static \Modules\Geo\Database\Factories\ProvinceFactory factory($count = null, $state = [])
 * @method static Builder<static>|Province newModelQuery()
 * @method static Builder<static>|Province newQuery()
 * @method static Builder<static>|Province query()
 * @method static Builder<static>|Province whereId($value)
 * @method static Builder<static>|Province whereName($value)
 * @method static Builder<static>|Province whereRegionId($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Province extends \Eloquent {}
}

namespace Modules\Geo\Models{
/**
 * @property int|null $id
 * @property string|null $name
 * @property-read ProfileContract|null $creator
 * @property-read Collection<int, Province> $provinces
 * @property-read int|null $provinces_count
 * @property-read ProfileContract|null $updater
 * @method static \Modules\Geo\Database\Factories\RegionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Region newModelQuery()
 * @method static Builder<static>|Region newQuery()
 * @method static Builder<static>|Region query()
 * @method static Builder<static>|Region whereId($value)
 * @method static Builder<static>|Region whereName($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Region extends \Eloquent {}
}

namespace Modules\Lang\Models{
/**
 * @property string|null                  $key
 * @property string|null                  $path
 * @property string|null                  $id
 * @property string|null                  $name
 * @property array<array-key, mixed>|null $content
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 * @method static TranslationFileFactory          factory($count = null, $state = [])
 * @method static Builder<static>|TranslationFile newModelQuery()
 * @method static Builder<static>|TranslationFile newQuery()
 * @method static Builder<static>|TranslationFile query()
 * @method static Builder<static>|TranslationFile whereContent($value)
 * @method static Builder<static>|TranslationFile whereId($value)
 * @method static Builder<static>|TranslationFile whereKey($value)
 * @method static Builder<static>|TranslationFile whereName($value)
 * @method static Builder<static>|TranslationFile wherePath($value)
 * @property ProfileContract|null $deleter
 * @mixin \Eloquent
 */
	class TranslationFile extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property string|null $id
 * @property string|null $name
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @method static \Modules\Tenant\Database\Factories\DomainFactory factory($count = null, $state = [])
 * @method static Builder<static>|Domain newModelQuery()
 * @method static Builder<static>|Domain newQuery()
 * @method static Builder<static>|Domain query()
 * @method static Builder<static>|Domain whereId($value)
 * @method static Builder<static>|Domain whereName($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class Domain extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property string|null $id
 * @property string|null $tenant_id
 * @property string|null $name
 * @property string|null $domain
 * @property bool $is_primary
 * @property string|null $status
 * @property string|null $verification_token
 * @property Carbon|null $verified_at
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @method static Builder<static>|TenantDomain newModelQuery()
 * @method static Builder<static>|TenantDomain newQuery()
 * @method static Builder<static>|TenantDomain query()
 * @method static Builder<static>|TenantDomain whereId($value)
 * @method static Builder<static>|TenantDomain whereName($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @method static \Modules\Tenant\Database\Factories\TenantDomainFactory factory($count = null, $state = [])
 */
	class TenantDomain extends \Eloquent {}
}

namespace Modules\Tenant\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $status
 * @property array<array-key, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @method static \Modules\Tenant\Database\Factories\TestSushiModelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSushiModel whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 */
	class TestSushiModel extends \Eloquent {}
}

namespace Modules\User\Models{
/**
 * @property int|null                  $id
 * @property string|null               $name
 * @property array<int, string>|null   $scopes
 * @property array<string, mixed>|null $parameters
 * @property bool|null                 $stateless
 * @property bool|null                 $active
 * @property bool|null                 $socialite
 * @property string|null               $svg
 * @property string|null               $client_id
 * @property string|null               $client_secret
 * @property ProfileContract|null      $creator
 * @property ProfileContract|null      $updater
 * @method static Builder|SocialProvider newModelQuery()
 * @method static Builder|SocialProvider newQuery()
 * @method static Builder|SocialProvider query()
 * @method static Builder|SocialProvider whereActive($value)
 * @method static Builder|SocialProvider whereClientId($value)
 * @method static Builder|SocialProvider whereClientSecret($value)
 * @method static Builder|SocialProvider whereId($value)
 * @method static Builder|SocialProvider whereName($value)
 * @method static Builder|SocialProvider whereParameters($value)
 * @method static Builder|SocialProvider whereScopes($value)
 * @method static Builder|SocialProvider whereSocialite($value)
 * @method static Builder|SocialProvider whereStateless($value)
 * @method static Builder|SocialProvider whereSvg($value)
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static Builder|SocialProvider whereCreatedAt($value)
 * @method static Builder|SocialProvider whereCreatedBy($value)
 * @method static Builder|SocialProvider whereUpdatedAt($value)
 * @method static Builder|SocialProvider whereUpdatedBy($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\SocialProviderFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class SocialProvider extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property int|null             $table_rows
 * @property string               $table_schema
 * @property string               $table_name
 * @property string|null          $model_class
 * @property Carbon|null          $created_at
 * @property string|null          $created_by
 * @property int                  $id
 * @property Carbon|null          $updated_at
 * @property string|null          $updated_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static InformationSchemaTableFactory          factory($count = null, $state = [])
 * @method static Builder<static>|InformationSchemaTable newModelQuery()
 * @method static Builder<static>|InformationSchemaTable newQuery()
 * @method static Builder<static>|InformationSchemaTable query()
 * @method static Builder<static>|InformationSchemaTable whereCreatedAt($value)
 * @method static Builder<static>|InformationSchemaTable whereCreatedBy($value)
 * @method static Builder<static>|InformationSchemaTable whereId($value)
 * @method static Builder<static>|InformationSchemaTable whereModelClass($value)
 * @method static Builder<static>|InformationSchemaTable whereTableName($value)
 * @method static Builder<static>|InformationSchemaTable whereTableRows($value)
 * @method static Builder<static>|InformationSchemaTable whereTableSchema($value)
 * @method static Builder<static>|InformationSchemaTable whereUpdatedAt($value)
 * @method static Builder<static>|InformationSchemaTable whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class InformationSchemaTable extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * Modules\Xot\Models\Feed.
 *
 * @property string|null $id
 * @property string|null $name
 * @property int|null    $size
 * @method static LogFactory          factory($count = null, $state = [])
 * @method static Builder<static>|Log newModelQuery()
 * @method static Builder<static>|Log newQuery()
 * @method static Builder<static>|Log query()
 * @method static Builder<static>|Log whereId($value)
 * @method static Builder<static>|Log whereName($value)
 * @method static Builder<static>|Log whereSize($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property string|null          $file_content
 * @property ProfileContract|null $updater
 * @mixin \Eloquent
 */
	class Log extends \Eloquent {}
}

namespace Modules\Xot\Models{
/**
 * @property int                             $id
 * @property string|null                     $name
 * @property string|null                     $slug
 * @property string|null                     $version
 * @property string|null                     $description
 * @property bool|null                       $status
 * @property bool|null                       $enabled
 * @property bool|null                       $is_active
 * @property int|null                        $priority
 * @property string|null                     $path
 * @property string|null                     $icon
 * @property array<array-key, mixed>|null    $colors
 * @property array<array-key, mixed>|null    $dependencies
 * @property array<array-key, mixed>|null    $config
 * @property array<array-key, mixed>|null    $metadata
 * @property \Illuminate\Support\Carbon|null $activation_date
 * @property \Illuminate\Support\Carbon|null $deactivation_date
 * @property \Illuminate\Support\Carbon|null $installation_date
 * @property array<array-key, mixed>|null    $update_history
 * @method static Builder<static>|Module newModelQuery()
 * @method static Builder<static>|Module newQuery()
 * @method static Builder<static>|Module query()
 * @method static Builder<static>|Module whereColors($value)
 * @method static Builder<static>|Module whereDescription($value)
 * @method static Builder<static>|Module whereIcon($value)
 * @method static Builder<static>|Module whereId($value)
 * @method static Builder<static>|Module whereName($value)
 * @method static Builder<static>|Module wherePath($value)
 * @method static Builder<static>|Module wherePriority($value)
 * @method static Builder<static>|Module whereStatus($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static ModuleFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	final class Module extends \Eloquent {}
}

