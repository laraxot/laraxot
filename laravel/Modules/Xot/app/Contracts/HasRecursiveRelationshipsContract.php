<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Builder as AdjacencyBuilder;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Collection;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\Ancestors;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\Bloodline;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\Descendants;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\RootAncestor;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\RootAncestorOrSelf;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\Siblings;

/**
 * Modules\Xot\Contracts\HasRecursiveRelationshipsContract.
 *
<<<<<<< HEAD
 * @template TModel of Model
 *
 * @property int $id
 * @property string $name
 * @property int $depth
 * @property Collection<int, TModel> $children
 * @property int|null $children_count
 * @property Collection<int, TModel> $ancestors The model's recursive parents.
 * @property int|null $ancestors_count
 * @property Collection<int, TModel> $ancestorsAndSelf The model's recursive parents and itself.
 * @property int|null $ancestors_and_self_count
 * @property Collection<int, TModel> $bloodline The model's ancestors, descendants and itself.
 * @property int|null $bloodline_count
 * @property Collection<int, TModel> $childrenAndSelf The model's direct children and itself.
 * @property int|null $children_and_self_count
 * @property Collection<int, TModel> $descendants The model's recursive children.
 * @property int|null $descendants_count
 * @property Collection<int, TModel> $descendantsAndSelf The model's recursive children and itself.
 * @property int|null $descendants_and_self_count
 * @property Collection<int, TModel> $parentAndSelf The model's direct parent and itself.
 * @property int|null $parent_and_self_count
 *
 * @phpstan-require-extends Model
 *
 * Tipi di ritorno solo nel `@return`, non nativi, sui metodi che il trait vendor
 * `Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships` dichiara senza
 * tipo (`getParentKeyName()`, `getPathName()`, `hasNestedPath()`, ...): i modelli che usano
 * quel trait direttamente non possono implementare un contratto con tipi nativi senza
 * fatal runtime. `TypedHasRecursiveRelationships` li restringe comunque ai tipi nativi
 * quando serve, e PHPStan legge il `@return`.
 *
 * @mixin Model
 * The vendor trait is the source of truth for implementation. Its public methods are
 * intentionally untyped, so this contract keeps runtime-compatible signatures and
 * documents the types for static analysis instead.
 * @mixin Model
=======
<<<<<<< .merge_file_y0eAgw
 * @property int $id
 * @property string $name
 * @property int $depth
 * @property Collection<int, Model> $children
 * @property int|null $children_count
 * @property Collection<int, Model> $ancestors The model's recursive parents.
 * @property int|null $ancestors_count
 * @property Collection<int, Model> $ancestorsAndSelf The model's recursive parents and itself.
 * @property int|null $ancestors_and_self_count
 * @property Collection<int, Model> $bloodline The model's ancestors, descendants and itself.
 * @property int|null $bloodline_count
 * @property Collection<int, Model> $childrenAndSelf The model's direct children and itself.
 * @property int|null $children_and_self_count
 * @property Collection<int, Model> $descendants The model's recursive children.
 * @property int|null $descendants_count
 * @property Collection<int, Model> $descendantsAndSelf The model's recursive children and itself.
 * @property int|null $descendants_and_self_count
 * @property Collection<int, Model> $parentAndSelf The model's direct parent and itself.
 * @property int|null $parent_and_self_count
=======
 * <<<<<<< HEAD
 *
 * @property int                    $id
 * @property string                 $name
 * @property int                    $depth
 * @property Collection<int, Model> $children
 * @property int|null               $children_count
 * @property Collection<int, Model> $ancestors                  The model's recursive parents.
 * @property int|null               $ancestors_count
 * @property Collection<int, Model> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null               $ancestors_and_self_count
 * @property Collection<int, Model> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null               $bloodline_count
 * @property Collection<int, Model> $childrenAndSelf            The model's direct children and itself.
 * @property int|null               $children_and_self_count
 * @property Collection<int, Model> $descendants                The model's recursive children.
 * @property int|null               $descendants_count
 * @property Collection<int, Model> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null               $descendants_and_self_count
 * @property Collection<int, Model> $parentAndSelf              The model's direct parent and itself.
 * @property int|null               $parent_and_self_count
 *                                                              =======
 *                                                              <<<<<<< HEAD
 *                                                              <<<<<<< HEAD
 *                                                              =======
 *                                                              <<<<<<< HEAD
 *                                                              >>>>>>> laraxot/dev
 * @property int                    $id
 * @property string                 $name
 * @property int                    $depth
 * @property Collection<int, Model> $children
 * @property int|null               $children_count
 * @property Collection<int, Model> $ancestors                  The model's recursive parents.
 * @property int|null               $ancestors_count
 * @property Collection<int, Model> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null               $ancestors_and_self_count
 * @property Collection<int, Model> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null               $bloodline_count
 * @property Collection<int, Model> $childrenAndSelf            The model's direct children and itself.
 * @property int|null               $children_and_self_count
 * @property Collection<int, Model> $descendants                The model's recursive children.
 * @property int|null               $descendants_count
 * @property Collection<int, Model> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null               $descendants_and_self_count
 * @property Collection<int, Model> $parentAndSelf              The model's direct parent and itself.
 * @property int|null               $parent_and_self_count
 *                                                              =======
 * @property int                    $id
 * @property string                 $name
 * @property int                    $depth
 * @property Collection<int, Model> $children
 * @property int|null               $children_count
 * @property Collection<int, Model> $ancestors                  The model's recursive parents.
 * @property int|null               $ancestors_count
 * @property Collection<int, Model> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null               $ancestors_and_self_count
 * @property Collection<int, Model> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null               $bloodline_count
 * @property Collection<int, Model> $childrenAndSelf            The model's direct children and itself.
 * @property int|null               $children_and_self_count
 * @property Collection<int, Model> $descendants                The model's recursive children.
 * @property int|null               $descendants_count
 * @property Collection<int, Model> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null               $descendants_and_self_count
 * @property Collection<int, Model> $parentAndSelf              The model's direct parent and itself.
 * @property int|null               $parent_and_self_count
 *                                                              >>>>>>> laraxot/dev
 *                                                              <<<<<<< HEAD
 *                                                              =======
 *                                                              =======
 * @property int                    $id
 * @property string                 $name
 * @property int                    $depth
 * @property Collection<int, Model> $children
 * @property int|null               $children_count
 * @property Collection<int, Model> $ancestors                  The model's recursive parents.
 * @property int|null               $ancestors_count
 * @property Collection<int, Model> $ancestorsAndSelf           The model's recursive parents and itself.
 * @property int|null               $ancestors_and_self_count
 * @property Collection<int, Model> $bloodline                  The model's ancestors, descendants and itself.
 * @property int|null               $bloodline_count
 * @property Collection<int, Model> $childrenAndSelf            The model's direct children and itself.
 * @property int|null               $children_and_self_count
 * @property Collection<int, Model> $descendants                The model's recursive children.
 * @property int|null               $descendants_count
 * @property Collection<int, Model> $descendantsAndSelf         The model's recursive children and itself.
 * @property int|null               $descendants_and_self_count
 * @property Collection<int, Model> $parentAndSelf              The model's direct parent and itself.
 * @property int|null               $parent_and_self_count
 *                                                              >>>>>>> laraxot/dev
 *                                                              >>>>>>> laraxot/dev
 *                                                              >>>>>>> laraxot/dev
>>>>>>> .merge_file_XaLzGh
 *
 * @phpstan-require-extends Model
 *
 * @mixin \Eloquent
>>>>>>> 77b1e2748 (first)
 */
interface HasRecursiveRelationshipsContract
{
    /**
     * Execute a query with a maximum depth constraint for the recursive query.
<<<<<<< HEAD
     *
     * Il ritorno e' `mixed` perche' il trait vendor `HasAdjacencyList` lo dichiara cosi':
     * un tipo piu' stretto qui rende fatale il caricamento di ogni classe che usa il trait.
=======
>>>>>>> 77b1e2748 (first)
     */
    public static function withMaxDepth(int $maxDepth, callable $query): mixed;

    /**
     * Get the name of the parent key column.
     *
     * @return string
     */
    public function getParentKeyName();

    /**
     * Get the qualified parent key column.
     *
     * @return string
     */
    public function getQualifiedParentKeyName();

    /**
     * Get the name of the local key column.
     *
     * @return string
     */
    public function getLocalKeyName();

    /**
     * Get the qualified local key column.
     *
     * @return string
     */
    public function getQualifiedLocalKeyName();

    /**
     * Get the name of the depth column.
     *
     * @return string
     */
    public function getDepthName();

    /**
     * Get the name of the path column.
     *
     * @return string
     */
    public function getPathName();

    /**
     * Get the path separator.
     *
     * @return string
     */
    public function getPathSeparator();

    /**
     * Get the additional custom paths.
     *
     * @return array<string>
     */
    public function getCustomPaths();

    /**
     * Get the name of the common table expression.
     *
     * @return string
     */
    public function getExpressionName();

<<<<<<< HEAD
    /** @return Ancestors<TModel, TModel> */
    public function ancestors();

    /** @return Ancestors<TModel, TModel> */
    public function ancestorsAndSelf();

    /** @return Bloodline<TModel, TModel> */
    public function bloodline();

    /** @return HasMany<TModel, TModel> */
    public function children();

    /** @return Descendants<TModel, TModel> */
    public function childrenAndSelf();

    /** @return Descendants<TModel, TModel> */
    public function descendants();

    /** @return Descendants<TModel, TModel> */
    public function descendantsAndSelf();

    /** @return BelongsTo<TModel, TModel> */
    public function parent();

    /** @return Ancestors<TModel, TModel> */
    public function parentAndSelf();

    /** @return RootAncestor<TModel, TModel> */
    public function rootAncestor();

    /** @return RootAncestorOrSelf<TModel, TModel> */
    public function rootAncestorOrSelf();

    /** @return Siblings<TModel, TModel> */
    public function siblings();

    /** @return Siblings<TModel, TModel> */
=======
    /** @return Ancestors<Model, Model> */
    public function ancestors();

    /** @return Ancestors<Model, Model> */
    public function ancestorsAndSelf();

    /** @return Bloodline<Model, Model> */
    public function bloodline();

    /** @return HasMany<Model, Model> */
    public function children();

    /** @return Descendants<Model, Model> */
    public function childrenAndSelf();

    /** @return Descendants<Model, Model> */
    public function descendants();

    /** @return Descendants<Model, Model> */
    public function descendantsAndSelf();

    /** @return BelongsTo<Model, Model> */
    public function parent();

    /** @return Ancestors<Model, Model> */
    public function parentAndSelf();

    /** @return RootAncestor<Model, Model> */
    public function rootAncestor();

    /** @return RootAncestorOrSelf<Model, Model> */
    public function rootAncestorOrSelf();

    /** @return Siblings<Model, Model> */
    public function siblings();

    /** @return Siblings<Model, Model> */
>>>>>>> 77b1e2748 (first)
    public function siblingsAndSelf();

    /**
     * Get the first segment of the model's path.
     *
     * @return string
     */
    public function getFirstPathSegment();

    /**
     * Determine whether the model's path is nested.
     *
     * @return bool
     */
    public function hasNestedPath();

    /**
     * Determine if an attribute is an integer.
     *
<<<<<<< HEAD
     *
=======
>>>>>>> 77b1e2748 (first)
     * @return bool
     */
    public function isIntegerAttribute(string $attribute);

    /**
<<<<<<< HEAD
     * @return AdjacencyBuilder<TModel>
=======
<<<<<<< .merge_file_y0eAgw
=======
     * <<<<<<< HEAD
     * =======
     * <<<<<<< HEAD
     * <<<<<<< HEAD.
     *
     * =======
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_XaLzGh
     * @return AdjacencyBuilder<Model>
>>>>>>> 77b1e2748 (first)
     */
    public function newEloquentBuilder(Builder $query);

    /**
<<<<<<< HEAD
     * @param  list<TModel>  $models
     * @return Collection<int, TModel>
=======
<<<<<<< .merge_file_y0eAgw
     * @param  list<Model>  $models
=======
     * <<<<<<< HEAD.
     *
     * @param Builder $query
     *
     * @return AdjacencyBuilder<Model>
     */
    public function newEloquentBuilder($query);

    /**
     * =======
     * <<<<<<< HEAD
     * <<<<<<< HEAD.
     *
     * @param list<Model> $models
     *                            =======
     *                            >>>>>>> laraxot/dev
     * @param list<Model> $models
     *
     * >>>>>>> laraxot/dev
     *
     * <<<<<<< HEAD
     * =======
     * =======
     * @param list<Model> $models
     *                            >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_XaLzGh
     * @return Collection<int, Model>
>>>>>>> 77b1e2748 (first)
     */
    public function newCollection(array $models = []);

    /**
     * added by XOT, viene utilizzato nelle options delle select.
     */
    public function getLabel(): string;
}
