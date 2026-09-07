<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\ResellerResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Customer\Filament\Resources\ResellerResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class ResellerForm extends XotBaseResourceForm
{
    protected static string $resource = ResellerResource::class;

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('name')
                            ->label(__('resellers.fields.name'))
                            ->required()
                            ->maxLength(255),

                        Select::make('user_id')
                            ->label(__('resellers.fields.user'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                    Textarea::make('notes')
                        ->label(__('resellers.fields.notes'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
