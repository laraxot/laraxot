<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\ParentChilds;

use Illuminate\Database\Eloquent\Collection;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Menu;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetTreeOptions
{
    use QueueableAction;

    private static function optionArrayKey(bool|float|int|string $scalar): int|string
    {
        return match (true) {
            is_int($scalar) => $scalar,
            is_string($scalar) => $scalar,
            is_float($scalar) => (string) $scalar,
            default => $scalar ? 1 : 0,
        };
    }

    /**
     * @return array<int|string, string>
     */
    public function execute(Category|Menu $model): array
    {
        return $this->buildOptionsFromModels($this->resolveTreeModels($model));
    }

    /**
     * @return array<int, Category|Menu>
     */
    private function resolveTreeModels(Category|Menu $model): array
    {
        $collection = $model->tree()->get();
        Assert::isInstanceOf($collection, Collection::class, 'tree()->get() must return a collection');

        if (! method_exists($collection, 'toTree')) {
            return $collection->all();
        }

        /** @var Collection<int, Category|Menu> $models */
        $models = $collection->toTree();

        return $models->all();
    }

    /**
     * @param array<int, Category|Menu> $models
     *
     * @return array<int|string, string>
     */
    private function buildOptionsFromModels(array $models): array
    {
        $results = [];
        foreach ($models as $mod) {
            $this->appendModelOptions($results, $mod, '');
        }

        return $results;
    }

    /**
     * @param array<int|string, string> $results
     */
    private function appendModelOptions(array &$results, Category|Menu $mod, string $prefix): void
    {
        $id = $mod->getAttribute('id');
        $title = $mod->getAttribute('title');
        Assert::scalar($id, 'ID must be scalar');
        Assert::string($title, 'Title must be string');

        $results[self::optionArrayKey($id)] = $prefix.$title;

        $children = $mod->getAttribute('children');
        if (! is_iterable($children)) {
            return;
        }

        foreach ($children as $child) {
            if (! $child instanceof Category && ! $child instanceof Menu) {
                continue;
            }

            $this->appendModelOptions($results, $child, $prefix.'--------->');
        }
    }
}
