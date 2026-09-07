<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Modules\Timber\Filament\Resources\TimberAgentResource;
use Modules\Timber\Models\TimberProduct;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class TimberAgentForm extends XotBaseResourceForm
{
    protected static string $resource = TimberAgentResource::class;

    /** @return array<int|string, Component> */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->maxLength(255),

                        TextInput::make('zone')
                            ->maxLength(255),

                        TextInput::make('commission_percent')
                            ->helperText('Usata quando non c\'e una regola piu specifica')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%'),

                        Toggle::make('is_active')
                            ->default(true),
                    ]),

                    Textarea::make('notes')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Provvigioni per categoria / prodotto')
                ->components([
                    Repeater::make('commissions')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('scope_type')
                                ->options([
                                    'default' => 'Predefinita',
                                    'category' => 'Per categoria',
                                    'product' => 'Per prodotto',
                                ])
                                ->default('default')
                                ->live()
                                ->required(),
                            TextInput::make('category')
                                ->maxLength(255)
                                ->visible(fn (Get $get): bool => $get('scope_type') === 'category')
                                ->required(fn (Get $get): bool => $get('scope_type') === 'category'),
                            Select::make('product_id')
                                ->options(fn (): array => TimberProduct::query()
                                    ->orderBy('description')
                                    ->pluck('description', 'id')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get): bool => $get('scope_type') === 'product')
                                ->required(fn (Get $get): bool => $get('scope_type') === 'product'),
                            TextInput::make('rate_percent')
                                ->numeric()
                                ->step(0.01)
                                ->suffix('%')
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Aggiungi regola')
                        ->defaultItems(0),
                ]),
        ];
    }
}
