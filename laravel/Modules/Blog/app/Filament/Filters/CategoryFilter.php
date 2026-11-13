<?php

declare(strict_types=1);

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

namespace Modules\Blog\Filament\Filters;

use Modules\Xot\Filament\Tables\Filters\XotBaseSelectFilter;

class CategoryFilter extends XotBaseSelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Filter By Category');
        $this->placeholder('Select a category to filter');
        $this->relationship('categories', 'title');
    }

    public static function getDefaultName(): ?string
    {
        return 'category';
    }
}
