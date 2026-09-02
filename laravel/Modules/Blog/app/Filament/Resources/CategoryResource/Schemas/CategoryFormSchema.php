<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\CategoryResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Modules\Blog\Models\Category;
use Modules\UI\Filament\Forms\Components\IconPicker;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;

final class CategoryFormSchema
{
    /**
     * @return array<int|string, Component>
     */
    public static function build(): array
    {
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(2048)
                ->reactive()
                ->unique()
                ->afterStateUpdated(static function (Set $set, $state): void {
                    $set('slug', Str::slug((string) $state));
                }),
            TextInput::make('slug')
                ->required()
                ->maxLength(2048),
            Select::make('parent_id')
                ->label('Categoria Padre')
                ->options(static fn (): array => SafeArrayCastAction::cast(Category::getTreeCategoryOptions()))
                ->searchable(),
            TextInput::make('description')
                ->maxLength(2048),
            SpatieMediaLibraryFileUpload::make('image')
                ->enableOpen()
                ->enableDownload()
                ->columnSpanFull()
                ->collection('category')
                ->disk('uploads')
                ->directory('photos'),
            IconPicker::make('icon')
                ->helperText('Visualizza le icone disponibili di https://heroicons.com/')
                ->columnSpanFull(),
        ];
    }
}
