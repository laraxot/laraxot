<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Signage\Filament\Resources\SignEstimationRuleResource\Pages;
use Modules\Signage\Models\SignEstimationRule;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class SignEstimationRuleResource extends XotBaseResource
{
    protected static ?string $model = SignEstimationRule::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?int $navigationSort = 90;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.estimation_rule');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.estimation_rules');
    }

    /**
     * @return array<string, Component|Field>
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role_code')
                    ->formatStateUsing(fn (string $state): string => __('signage::signage.roles.'.$state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('strategy_code')
                    ->formatStateUsing(fn (string $state): string => __('signage::signage.strategies.'.$state)),
                Tables\Columns\TextColumn::make('unit_code'),
                Tables\Columns\TextColumn::make('factor')->numeric(),
                Tables\Columns\TextColumn::make('base_value')->numeric(),
                Tables\Columns\TextColumn::make('min_per_piece')->numeric()->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignEstimationRules::route('/'),
        ];
    }
}
