<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Modules\Xot\Contracts\HasRecursiveRelationshipsContract;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

<<<<<<< HEAD
/**
 * @template TModel of BaseTreeModel
 *
 * @implements HasRecursiveRelationshipsContract<TModel>
 */
=======
>>>>>>> 77b1e2748 (first)
abstract class BaseTreeModel extends BaseModel implements HasRecursiveRelationshipsContract
{
    use HasRecursiveRelationships;
}
