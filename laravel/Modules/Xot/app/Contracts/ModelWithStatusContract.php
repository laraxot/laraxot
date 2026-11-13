<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spatie\ModelStatus\Status;

/**
 * Modules\Xot\Contracts\ModelWithStatusContract.
 *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_VJvIRc
>>>>>>> 77b1e2748 (first)
 * @property int $id
 * @property int|null $user_id
 * @property string|null $post_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $title
 * @property PivotContract|null $pivot
 * @property string $tennant_name
 * @property UserContract|null $user
 * @property string $status
<<<<<<< HEAD
 * @property Collection<int, Status> $statuses
 * @property int|null $statuses_count
 *
 * @method int|string|null getKey()
 * @method string getRouteKey()
 * @method string getRouteKeyName()
 * @method string getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model> with($array)
=======
=======
 * <<<<<<< HEAD
 *
 * @property int                     $id
 * @property int|null                $user_id
 * @property string|null             $post_type
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property string|null             $title
 * @property PivotContract|null      $pivot
 * @property string                  $tennant_name
 * @property UserContract|null       $user
 * @property string                  $status
>>>>>>> .merge_file_RZyVlB
 * @property Collection<int, Status> $statuses
 * @property int|null                $statuses_count
 *
 * @method int|string|null                              getKey()
 * @method string                                       getRouteKey()
 * @method string                                       getRouteKeyName()
 * @method string                                       getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model> with($array)
<<<<<<< .merge_file_VJvIRc
>>>>>>> 77b1e2748 (first)
 * @method list<string> getFillable()
 * @method static fill($array)
 * @method \Illuminate\Database\Connection getConnection()
 * @method bool update($params)
 * @method bool|null delete()
 * @method int detach($params)
 * @method void attach($params)
 * @method bool save($params)
 * @method array<string, mixed> treeLabel()
 * @method array<string, mixed> treeSons()
 * @method array<string, mixed> toArray()
<<<<<<< HEAD
=======
=======
 * @method list<string>                                 getFillable()
 * @method static                                       fill($array)
 * @method \Illuminate\Database\Connection              getConnection()
 * @method bool                                         update($params)
 * @method bool|null                                    delete()
 * @method int                                          detach($params)
 * @method void                                         attach($params)
 * @method bool                                         save($params)
 * @method array<string, mixed>                         treeLabel()
 * @method array<string, mixed>                         treeSons()
 * @method array<string, mixed>                         toArray()
 *                                                                        =======
 *                                                                        <<<<<<< HEAD
 *                                                                        <<<<<<< HEAD
 *                                                                        <<<<<<< .merge_file_XXsVLF
 *                                                                        =======
 *                                                                        <<<<<<< HEAD
 *
 * @property int                     $id
 * @property int|null                $user_id
 * @property string|null             $post_type
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property string|null             $title
 * @property PivotContract|null      $pivot
 * @property string                  $tennant_name
 * @property UserContract|null       $user
 * @property string                  $status
 * @property Collection<int, Status> $statuses
 * @property int|null                $statuses_count
 *
 * @method int|string|null                              getKey()
 * @method string                                       getRouteKey()
 * @method string                                       getRouteKeyName()
 * @method string                                       getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model> with($array)
 * @method list<string>                                 getFillable()
 * @method static                                       fill($array)
 * @method \Illuminate\Database\Connection              getConnection()
 * @method bool                                         update($params)
 * @method bool|null                                    delete()
 * @method int                                          detach($params)
 * @method void                                         attach($params)
 * @method bool                                         save($params)
 * @method array<string, mixed>                         treeLabel()
 * @method array<string, mixed>                         treeSons()
 * @method array<string, mixed>                         toArray()
 *                                                                        =======
 *                                                                        <<<<<<< .merge_file_Ak1OQV
 *                                                                        =======
 *                                                                        <<<<<<< HEAD
 *                                                                        <<<<<<< .merge_file_XXsVLF
 *                                                                        >>>>>>> laraxot/dev
 *
 * @property int                     $id
 * @property int|null                $user_id
 * @property string|null             $post_type
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property string|null             $title
 * @property PivotContract|null      $pivot
 * @property string                  $tennant_name
 * @property UserContract|null       $user
 * @property string                  $status
 *                                                   =======
 *                                                   <<<<<<< .merge_file_RCHBHS
 *                                                   <<<<<<< HEAD
 *                                                   =======
 *                                                   >>>>>>> .merge_file_WmDkAR
 *                                                   >>>>>>> laraxot/dev
 * @property int                     $id
 * @property int|null                $user_id
 * @property string|null             $post_type
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property string|null             $title
 * @property PivotContract|null      $pivot
 * @property string                  $tennant_name
 * @property UserContract|null       $user
 * @property string                  $status
 *                                                   <<<<<<< HEAD
 *                                                   =======
 *                                                   <<<<<<< .merge_file_Ak1OQV
 *                                                   =======
 *                                                   >>>>>>> laraxot/dev
 *                                                   =======
 *                                                   <<<<<<< HEAD
 * @property int                     $id
 * @property int|null                $user_id
 * @property string|null             $post_type
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property string|null             $title
 * @property PivotContract|null      $pivot
 * @property string                  $tennant_name
 * @property UserContract|null       $user
 * @property string                  $status
 *                                                   =======
 * @property int                     $id
 * @property int|null                $user_id
 * @property string|null             $post_type
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property string|null             $title
 * @property PivotContract|null      $pivot
 * @property string                  $tennant_name
 * @property UserContract|null       $user
 * @property string                  $status
 *                                                   >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
 *                                                   <<<<<<< HEAD
 *                                                   =======
 *                                                   >>>>>>> .merge_file_WmDkAR
 *                                                   >>>>>>> laraxot/dev
 * @property Collection<int, Status> $statuses
 * @property int|null                $statuses_count
 *
 * <<<<<<< HEAD
 *
 * @method int|string|null                              getKey()
 * @method string                                       getRouteKey()
 * @method string                                       getRouteKeyName()
 * @method string                                       getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model> with($array)
 * @method list<string>                                 getFillable()
 * @method static                                       fill($array)
 * @method \Illuminate\Database\Connection              getConnection()
 * @method bool                                         update($params)
 * @method bool|null                                    delete()
 * @method int                                          detach($params)
 * @method void                                         attach($params)
 * @method bool                                         save($params)
 * @method array<string, mixed>                         treeLabel()
 * @method array<string, mixed>                         treeSons()
 * @method array<string, mixed>                         toArray()
 *                                                                        <<<<<<< HEAD
 *                                                                        =======
 *
 * @property Collection<int, Status> $statuses
 * @property int|null                $statuses_count
 *
 * @method int|string|null                              getKey()
 * @method string                                       getRouteKey()
 * @method string                                       getRouteKeyName()
 * @method string                                       getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model> with($array)
 * @method list<string>                                 getFillable()
 * @method static                                       fill($array)
 * @method \Illuminate\Database\Connection              getConnection()
 * @method bool                                         update($params)
 * @method bool|null                                    delete()
 * @method int                                          detach($params)
 * @method void                                         attach($params)
 * @method bool                                         save($params)
 * @method array<string, mixed>                         treeLabel()
 * @method array<string, mixed>                         treeSons()
 * @method array<string, mixed>                         toArray()
 *                                                                        <<<<<<< .merge_file_Ak1OQV
 *                                                                        =======
 *                                                                        =======
 *                                                                        <<<<<<< .merge_file_RCHBHS
 * @method int|string|null                              getKey()
 * @method string                                       getRouteKey()
 * @method string                                       getRouteKeyName()
 * @method string                                       getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model> with($array)
 * @method list<string>                                 getFillable()
 * @method static                                       fill($array)
 * @method \Illuminate\Database\Connection              getConnection()
 * @method bool                                         update($params)
 * @method bool|null                                    delete()
 * @method int                                          detach($params)
 * @method void                                         attach($params)
 * @method bool                                         save($params)
 * @method array<string, mixed>                         treeLabel()
 * @method array<string, mixed>                         treeSons()
 * @method array<string, mixed>                         toArray()
 *                                                                        =======
 * @method mixed                                        getKey()
 * @method string                                       getRouteKey()
 * @method string                                       getRouteKeyName()
 * @method string                                       getTable()
 * @method mixed                                        with($array)
 * @method array<string, mixed>                         getFillable()
 * @method mixed                                        fill($array)
 * @method mixed                                        getConnection()
 * @method mixed                                        update($params)
 * @method mixed                                        delete()
 * @method mixed                                        detach($params)
 * @method mixed                                        attach($params)
 * @method mixed                                        save($params)
 * @method array<string, mixed>                         treeLabel()
 * @method array<string, mixed>                         treeSons()
 * @method array<string, mixed>                         toArray()
 *                                                                        >>>>>>> laraxot/dev
 *                                                                        >>>>>>> .merge_file_rpt3WK
 *                                                                        >>>>>>> .merge_file_wDA4Ri
 *                                                                        =======
 * @method int|string|null                              getKey()
 * @method string                                       getRouteKey()
 * @method string                                       getRouteKeyName()
 * @method string                                       getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model> with($array)
 * @method list<string>                                 getFillable()
 * @method static                                       fill($array)
 * @method \Illuminate\Database\Connection              getConnection()
 * @method bool                                         update($params)
 * @method bool|null                                    delete()
 * @method int                                          detach($params)
 * @method void                                         attach($params)
 * @method bool                                         save($params)
 * @method array<string, mixed>                         treeLabel()
 * @method array<string, mixed>                         treeSons()
 * @method array<string, mixed>                         toArray()
 *                                                                        >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
 *                                                                        >>>>>>> .merge_file_WmDkAR
 *                                                                        >>>>>>> laraxot/dev
 *                                                                        >>>>>>> laraxot/dev
 *                                                                        =======
 *
 * @property int                     $id
 * @property int|null                $user_id
 * @property string|null             $post_type
 * @property Carbon|null             $created_at
 * @property Carbon|null             $updated_at
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property string|null             $title
 * @property PivotContract|null      $pivot
 * @property string                  $tennant_name
 * @property UserContract|null       $user
 * @property string                  $status
 *                                                   <<<<<<< HEAD
 *                                                   >>>>>>> .merge_file_rpt3WK
 *                                                   >>>>>>> .merge_file_wDA4Ri
 * @property Collection<int, Status> $statuses
 * @property int|null                $statuses_count
 *
 * <<<<<<< .merge_file_XXsVLF
 *
 * =======
 * @property Collection<int, Status> $statuses
 * @property int|null                $statuses_count
 *
 * >>>>>>> laraxot/dev
 *
 * @method int|string|null                                                 getKey()
 * @method string                                                          getRouteKey()
 * @method string                                                          getRouteKeyName()
 * @method string                                                          getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model>                    with($array)
 * @method list<string>                                                    getFillable()
 * @method static                                                          fill($array)
 * @method \Illuminate\Database\Connection                                 getConnection()
 * @method bool                                                            update($params)
 * @method bool|null                                                       delete()
 * @method int                                                             detach($params)
 * @method void                                                            attach($params)
 * @method bool                                                            save($params)
 * @method array<string, mixed>                                            treeLabel()
 * @method array<string, mixed>                                            treeSons()
 * @method array<string, mixed>                                            toArray()
 *                                                                                           <<<<<<< HEAD
 *                                                                                           =======
 *                                                                                           <<<<<<< .merge_file_RCHBHS
 * @method int|string|null                                                 getKey()
 * @method string                                                          getRouteKey()
 * @method string                                                          getRouteKeyName()
 * @method string                                                          getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model>                    with($array)
 * @method list<string>                                                    getFillable()
 * @method static                                                          fill($array)
 * @method \Illuminate\Database\Connection                                 getConnection()
 * @method bool                                                            update($params)
 * @method bool|null                                                       delete()
 * @method int                                                             detach($params)
 * @method void                                                            attach($params)
 * @method bool                                                            save($params)
 * @method array<string, mixed>                                            treeLabel()
 * @method array<string, mixed>                                            treeSons()
 * @method array<string, mixed>                                            toArray()
 *                                                                                           =======
 * @method mixed                                                           getKey()
 * @method string                                                          getRouteKey()
 * @method string                                                          getRouteKeyName()
 * @method string                                                          getTable()
 * @method mixed                                                           with($array)
 * @method array<string, mixed>                                            getFillable()
 * @method mixed                                                           fill($array)
 * @method mixed                                                           getConnection()
 * @method mixed                                                           update($params)
 * @method mixed                                                           delete()
 * @method mixed                                                           detach($params)
 * @method mixed                                                           attach($params)
 * @method mixed                                                           save($params)
 * @method array<string, mixed>                                            treeLabel()
 * @method array<string, mixed>                                            treeSons()
 * @method array<string, mixed>                                            toArray()
 *                                                                                           >>>>>>> laraxot/dev
 *                                                                                           >>>>>>> .merge_file_rpt3WK
 *                                                                                           >>>>>>> .merge_file_wDA4Ri
 *                                                                                           =======
 * @method int|string|null                                                 getKey()
 * @method string                                                          getRouteKey()
 * @method string                                                          getRouteKeyName()
 * @method string                                                          getTable()
 * @method \Illuminate\Database\Eloquent\Builder<Model>                    with($array)
 * @method list<string>                                                    getFillable()
 * @method static                                                          fill($array)
 * @method \Illuminate\Database\Connection                                 getConnection()
 * @method bool                                                            update($params)
 * @method bool|null                                                       delete()
 * @method int                                                             detach($params)
 * @method void                                                            attach($params)
 * @method bool                                                            save($params)
 * @method array<string, mixed>                                            treeLabel()
 * @method array<string, mixed>                                            treeSons()
 * @method array<string, mixed>                                            toArray()
 *                                                                                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
 *                                                                                           =======
 *                                                                                           >>>>>>> laraxot/dev
 *                                                                                           >>>>>>> laraxot/dev
 *                                                                                           >>>>>>> laraxot/dev
>>>>>>> .merge_file_RZyVlB
>>>>>>> 77b1e2748 (first)
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo<Model, Model> user()
 *
 * @phpstan-require-extends Model
 *
<<<<<<< HEAD
 * @mixin Model
=======
 * @mixin \Eloquent
>>>>>>> 77b1e2748 (first)
 */
interface ModelWithStatusContract
{
    /** @return MorphMany<Model, Model> */
    public function statuses(): MorphMany;

    public function status(): ?Status;

    public function setStatus(string $name, ?string $reason = null): self;
}
