<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources;

use Filament\Schemas\Components\Component;
use Modules\Blog\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Modules\Blog\Filament\Resources\CategoryResource\Pages\EditCategory;
use Modules\Blog\Filament\Resources\CategoryResource\Pages\ListCategories;
use Modules\Blog\Filament\Resources\CategoryResource\Schemas\CategoryFormSchema;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Webmozart\Assert\Assert;

class CategoryResource extends XotBaseResource
{
    // use Translatable; // Temporarily disabled until lara-zeus package is working

    // protected static ?string $model = Category::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationGroup = 'Content';

    /**
     * @return array<int, string>
     */
    public static function getTranslatableLocales(): array
    {
        return ['it', 'en'];
    }

    /**
     * @return array<int|string, Component>
     */
    public static function getFormFields(): array
    {
        return CategoryFormSchema::build();
    }

    /**
     * @return array<string|int, Component>
     */
    public static function getFormSchema(): array
    {
        /** @var array<string|int, Component> $fields */
        $fields = static::getFormFields();
        Assert::isArray($fields, 'getFormFields must return array');

        return $fields;
    }

    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ManageCategories::route('/'),
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
