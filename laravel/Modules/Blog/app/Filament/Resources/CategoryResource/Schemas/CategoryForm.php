<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\CategoryResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Modules\Blog\Models\Category;
use Modules\UI\Filament\Forms\Components\IconPicker;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class CategoryForm extends XotBaseResourceForm
{
    /**
     * @return array<int|string, SchemaComponent>
     */
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(2048)
                ->reactive()
                ->unique()
                ->afterStateUpdated(function (Set $set, $state): void {
                    $set('slug', Str::slug((string) $state));
                }),
            TextInput::make('slug')
                ->required()
                ->maxLength(2048),
            Select::make('parent_id')
                ->label('Categoria Padre')
                ->options(static function (): array {
                    return SafeArrayCastAction::cast(Category::getTreeCategoryOptions());
                })
                ->searchable(),
            TextInput::make('description')
                ->maxLength(2048),
            SpatieMediaLibraryFileUpload::make('image')
                // ->image()
                // ->maxSize(5000)
                // ->multiple()
                // ->enableReordering()
                ->enableOpen()
                ->enableDownload()
                ->columnSpanFull()
                ->collection('category')
                // ->conversion('thumbnail')
                ->disk('uploads')
                ->directory('photos'),
            IconPicker::make('icon')
                ->helperText('Visualizza le icone disponibili di https://heroicons.com/')
                ->columnSpanFull()
            // ->layout(\Guava\FilamentIconPicker\Layout::ON_TOP)
            ,
        ];
    }
}
