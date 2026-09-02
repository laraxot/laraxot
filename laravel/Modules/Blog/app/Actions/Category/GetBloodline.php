<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Category;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Modules\Blog\Models\Category;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetBloodline
{
    use QueueableAction;

    /**
     * @return EloquentCollection<int, Category>
     */
    public function execute(?int $categoryId): EloquentCollection
    {
        if (null === $categoryId) {
            return new EloquentCollection();
        }
        Assert::notNull($category = Category::find($categoryId), '['.__LINE__.']['.__FILE__.']');

        return $category->ancestorsAndSelf()->get()->reverse()->values();
    }
}
