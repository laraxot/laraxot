<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Modules\Blog\Filament\Resources\TextWidgetResource\Pages\CreateTextWidget;
use Modules\Blog\Filament\Resources\TextWidgetResource\Pages\EditTextWidget;
use Modules\Blog\Filament\Resources\TextWidgetResource\Pages\ListTextWidgets;
use Modules\Blog\Filament\Resources\TextWidgetResource\Pages\ViewTextWidget;
use Modules\Xot\Filament\Resources\XotBaseResource;

class TextWidgetResource extends XotBaseResource
{
    // protected static ?string $model = TextWidget::class;

    // protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string|\BackedEnum|null $navigationIcon = 'ui-widgets';

    // protected static ?string $navigationGroup = 'Content';
    /**
     * @return array<string|int, Component>
     */
    public static function getFormSchema(): array
    {
        return static::getFormFields();
    }

    /**
     * Ritorna i campi del form (compatibilità con XotBaseResource).
     *
     * @return array<string|int, Component>
     */
    public static function getFormFields(): array
    {
        return [
            TextInput::make('key')
                ->required()
                ->maxLength(255),
            // Forms\Components\FileUpload::make('image'),
            SpatieMediaLibraryFileUpload::make('image')
                // ->image()
                // ->maxSize(5000)
                // ->multiple()
                // ->enableReordering()
                ->enableOpen()
                ->enableDownload()
                ->columnSpanFull()
                // ->collection('avatars')
                // ->conversion('thumbnail')
                ->disk('uploads')
                ->directory('photos'),
            TextInput::make('title')
                ->maxLength(2048),
            RichEditor::make('content'),
            Toggle::make('active')
                ->required(),
        ];
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTextWidgets::route('/'),
            'create' => CreateTextWidget::route('/create'),
            'view' => ViewTextWidget::route('/{record}'),
            'edit' => EditTextWidget::route('/{record}/edit'),
        ];
    }
}
