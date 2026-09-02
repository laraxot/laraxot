<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\BannerResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Components\Grid;
use Modules\Blog\Filament\Resources\BannerResource;
use Modules\Blog\Models\Category;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class BannerForm extends XotBaseResourceForm
{
    /**
     * @return array<int|string, SchemaComponent>
     */
    public static function getFormSchema(): array
    {
        return [
            Grid::make()->columns(2)->schema([
                TextInput::make('title')
                    ->label(BannerResource::trans('fields.title'))
                    ->columnSpan(1)
                    ->required(),
                TextInput::make('description')
                    ->columnSpan(1)
                    ->required(),
                // Forms\Components\TextInput::make('action_text')
                //     ->columnSpan(1)
                //     ->required(),
                Select::make('category_id')
                    ->required()
                    ->options(static function (): array {
                        return SafeArrayCastAction::cast(Category::getTreeCategoryOptions());
                    }),
                // Forms\Components\TextInput::make('link')
                //     ->columnSpan(1)
                // ->required(),
                // ->helperText('bla bla bla'),
                // Forms\Components\DateTimePicker::make('start_date')
                //     ->columnSpan(1),
                // Forms\Components\DateTimePicker::make('end_date')
                //     ->columnSpan(1),
                Toggle::make('hot_topic')
                    ->columnSpan(1),
                Toggle::make('landing_banner')
                    ->columnSpan(1),

                SpatieMediaLibraryFileUpload::make('image')
                    // ->image()
                    // ->maxSize(5000)
                    // ->multiple()
                    // ->enableReordering()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    // ->collection('avatars')
                    // ->conversion('thumbnail')
                    ->disk('uploads')
                    ->directory('photos')
                    ->collection('banner'),

                // 'open_markets_count', // : 119,
            ]),
        ];
    }
}
