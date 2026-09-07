<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignEstimationRuleResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Filament\Resources\SignEstimationRuleResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class SignEstimationRuleForm extends XotBaseResourceForm
{
    protected static string $resource = SignEstimationRuleResource::class;

    public function getFormColumns(): int
    {
        return 1;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(3)->components([
                        Select::make('role_code')
                            ->options(__('signage::signage.roles'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('unit_code')
                            ->options(__('signage::signage.units'))
                            ->required(),
                        Select::make('strategy_code')
                            ->options(__('signage::signage.strategies'))
                            ->helperText(__('signage::signage.help.strategy'))
                            ->required(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('factor')
                            ->numeric()
                            ->default(1),
                        TextInput::make('base_value')
                            ->numeric()
                            ->default(0),
                        TextInput::make('min_per_piece')
                            ->numeric()
                            ->nullable(),
                    ]),
                    Textarea::make('notes')
                        ->helperText(__('signage::signage.help.formula'))
                        ->rows(2),
                    Toggle::make('is_active')
                        ->default(true),
                ]),
        ];
    }
}
