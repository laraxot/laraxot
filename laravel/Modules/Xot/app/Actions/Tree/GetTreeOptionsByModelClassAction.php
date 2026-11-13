<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Tree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Contracts\HasRecursiveRelationshipsContract;
use Spatie\QueueableAction\QueueableAction;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Collection as TreeCollection;
<<<<<<< HEAD
use Webmozart\Assert\Assert;
=======
>>>>>>> 77b1e2748 (first)

class GetTreeOptionsByModelClassAction
{
    use QueueableAction;

    /** @var array<int|string, string> */
    public array $options = [];

    /**
<<<<<<< HEAD
     * @param  class-string<HasRecursiveRelationshipsContract<Model>>  $class
=======
     * @param  class-string<HasRecursiveRelationshipsContract>  $class
>>>>>>> 77b1e2748 (first)
     * @return array<int|string, string>
     */
    public function execute(string $class, Model|callable|null $_where = null): array
    {
<<<<<<< HEAD
        $model = new $class;

        $collection = $model->newQuery()->get();
        Assert::isInstanceOf($collection, TreeCollection::class);
=======
        /** @var HasRecursiveRelationshipsContract $model */
        $model = new $class;

        /** @var TreeCollection<int, Model&HasRecursiveRelationshipsContract> $collection */
        $collection = $model->newQuery()->get();
>>>>>>> 77b1e2748 (first)
        $rows = $collection->toTree();

        foreach ($rows as $row) {
            if (! $row instanceof HasRecursiveRelationshipsContract) {
                continue;
            }
            $key = $row->getKey();
            $this->options[SafeStringCastAction::cast($key)] = $row->getLabel();
            $this->parse($row);
        }

        return $this->options;
    }

<<<<<<< HEAD
    /**
     * @param  HasRecursiveRelationshipsContract<Model>  $model
     */
    public function parse(HasRecursiveRelationshipsContract $model): void
    {
        foreach ($model->children as $child) {
            /** @var HasRecursiveRelationshipsContract<Model> $child */
=======
    public function parse(HasRecursiveRelationshipsContract $model): void
    {
        foreach ($model->children as $child) {
            /** @var HasRecursiveRelationshipsContract $child */
>>>>>>> 77b1e2748 (first)
            $key = $child->getKey();
            $this->options[SafeStringCastAction::cast($key)] =
                Str::repeat('---', $child->depth).'   '.$child->getLabel();
        }
    }
}
