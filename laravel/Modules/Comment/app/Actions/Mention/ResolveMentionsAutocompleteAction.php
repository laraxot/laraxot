<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Mention;

use Modules\Comment\Datas\CommentConfigData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

class ResolveMentionsAutocompleteAction
{
    use QueueableAction;

    /** @return Collection<int, Model> */
    public function execute(string $query): Collection
    {
        return $this->handle($query);
    }

    /** @return Collection<int, Model> */
    public function handle(string $query): Collection
    {
        $config = CommentConfigData::make();
        $modelClass = $config->models['commentator'] ?? null;

        if (! is_string($modelClass) || $modelClass === '' || ! class_exists($modelClass)) {
            return new Collection;
        }

        $nameField = $config->models['name'] ?? 'name';

        $instance = new $modelClass;

        if (! $instance instanceof Model) {
            return new Collection;
        }

        /** @var Collection<int, Model> $results */
        $results = $instance->newQuery()
            ->where($nameField, 'like', '%'.$query.'%')
            ->limit(10)
            ->get();

        return $results;
    }
}
