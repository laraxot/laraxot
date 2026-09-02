<?php

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\PostResource\Filters;

use Filament\Tables\Filters\SelectFilter;

class CategoryFilter extends SelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Filter By Category');
        $this->placeholder('Select a category to filter');
        // $this->relationship('category', 'name');
        $this->relationship('categories', 'title');
        // $this->searchable();
    }

    public static function getDefaultName(): ?string
    {
        return 'category';
    }
}
