<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Modules\Xot\Contracts\HasRecursiveRelationshipsContract;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

<<<<<<< HEAD
=======
/**
 * @template TModel of XotBaseTreeModel
 *
 * @implements HasRecursiveRelationshipsContract<TModel>
 */
>>>>>>> laraxot/dev
abstract class XotBaseTreeModel extends XotBaseModel implements HasRecursiveRelationshipsContract
{
    use HasRecursiveRelationships;
}
