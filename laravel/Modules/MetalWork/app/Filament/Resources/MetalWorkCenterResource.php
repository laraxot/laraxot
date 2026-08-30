<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
use Modules\Costing\Models\WorkCenter;
use Modules\MetalWork\Support\MetalWorksCosting;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;

/**
 * The workshop's cost centres. The hourly rate and the markup here are what every
 * machining row of every estimate is computed from, so this is the one screen
 * that moves the price of the whole catalogue.
 */
final class MetalWorkCenterResource extends GatedXotBaseResource
{
    protected static ?string $model = WorkCenter::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 40;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metalwork::metalwork.work_center');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metalwork::metalwork.work_centers');
    }

    /** @return Builder<WorkCenter> */
    public static function getEloquentQuery(): Builder
    {
        /** @var Builder<WorkCenter> $query */
        $query = parent::getEloquentQuery()->where('context_code', MetalWorksCosting::CONTEXT);

        return $query;
    }

    /**
     * @return array<string, Component|Field>
     */
    public static function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->label(__('metalwork::metalwork.fields.code'))
                            ->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule): Unique => $rule->where('context_code', MetalWorksCosting::CONTEXT)),
                        TextInput::make('name')
                            ->label(__('metalwork::metalwork.fields.name'))
                            ->required(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('hourly_rate')
                            ->label(__('metalwork::metalwork.fields.hourly_rate'))
                            ->numeric()
                            ->required()
                            ->suffix('€/h'),
                        TextInput::make('default_markup_percent')
                            ->label(__('metalwork::metalwork.fields.default_markup_percent'))
                            ->numeric()
                            ->suffix('%'),
                        TextInput::make('sort_order')
                            ->label(__('metalwork::metalwork.fields.sort_order'))
                            ->numeric()
                            ->default(0),
                    ]),
                    Grid::make(2)->components([
                        Toggle::make('is_internal')->label(__('metalwork::metalwork.fields.is_internal'))->default(true),
                        Hidden::make('context_code')->default(MetalWorksCosting::CONTEXT),
                        Toggle::make('is_active')->label(__('metalwork::metalwork.fields.is_active'))->default(true),
                    ]),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('code')->label(__('metalwork::metalwork.fields.code'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label(__('metalwork::metalwork.fields.name'))->searchable(),
                Tables\Columns\TextColumn::make('hourly_rate')
                    ->label(__('metalwork::metalwork.fields.hourly_rate'))
                    ->numeric(2)
                    ->suffix(' €/h')
                    ->sortable(),
                Tables\Columns\TextColumn::make('default_markup_percent')
                    ->label(__('metalwork::metalwork.fields.default_markup_percent'))
                    ->placeholder('—')
                    ->suffix('%'),
                Tables\Columns\IconColumn::make('is_internal')->label(__('metalwork::metalwork.fields.is_internal'))->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label(__('metalwork::metalwork.fields.is_active'))->boolean(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => MetalWorkCenterResource\Pages\ListMetalWorkCenters::route('/'),
        ];
    }
}
