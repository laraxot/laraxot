<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Catalog\Filament\Resources\MetelCatalogResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class MetelCatalogForm extends XotBaseResourceForm
{
    protected static string $resource = MetelCatalogResource::class;

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('code')
                        ->label(__('metel.fields.code'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    TextInput::make('brand_code')
                        ->label(__('metel.fields.brand_code'))
                        ->maxLength(3)
                        ->helperText(__('metel.fields.brand_code_hint')),
                    TextInput::make('name')
                        ->label(__('metel.fields.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('manufacturer')
                        ->label(__('metel.fields.manufacturer'))
                        ->maxLength(255),
                    Select::make('source_type')
                        ->label(__('metel.fields.source_type'))
                        ->options([
                            'file' => __('metel.sources.file'),
                            'api' => __('metel.sources.api'),
                        ])
                        ->default('file')
                        ->required(),
                    TextInput::make('version')
                        ->label(__('metel.fields.version'))
                        ->maxLength(255),
                    Toggle::make('is_active')
                        ->label(__('metel.fields.is_active'))
                        ->default(true),
                ]),
        ];
    }
}
