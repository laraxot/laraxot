<?php

declare(strict_types=1);

namespace Modules\Blog\Datas;

use Spatie\LaravelData\Data;

class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
