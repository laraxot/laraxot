<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Modules\Xot\Contracts\HasRecursiveRelationshipsContract;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

<<<<<<< HEAD
=======
/**
 * @template TModel of BaseTreeModel
 *
 * @implements HasRecursiveRelationshipsContract<TModel>
 */
>>>>>>> laraxot/dev
abstract class BaseTreeModel extends BaseModel implements HasRecursiveRelationshipsContract
{
    use HasRecursiveRelationships;
}
