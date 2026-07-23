<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages\CreatePublicProcurementTenderSource;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages\EditPublicProcurementTenderSource;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages\ListPublicProcurementTenderSources;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;

final class PublicProcurementTenderSourceResource extends Resource
{
    protected static ?string $model = PublicProcurementTenderSource::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rss';

    public static function getNavigationGroup(): ?string
    {
        return __('public_procurement.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('public_procurement.source');
    }

    public static function getPluralModelLabel(): string
    {
        return __('public_procurement.sources');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nome')->required(),
            Select::make('source_type')->label(__('public_procurement.fields.source_type'))->options(['manual' => 'Manuale', 'portal' => 'Portale', 'rss' => 'RSS/API'])->default('manual'),
            TextInput::make('url')->label(__('public_procurement.fields.url'))->url(),
            Toggle::make('is_active')->label('Attiva')->default(true),
            Textarea::make('notes')->label(__('public_procurement.fields.notes'))->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('source_type')->label(__('public_procurement.fields.source_type'))->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Attiva')->boolean(),
                Tables\Columns\TextColumn::make('url')->label(__('public_procurement.fields.url'))->limit(50),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicProcurementTenderSources::route('/'),
            'create' => CreatePublicProcurementTenderSource::route('/create'),
            'edit' => EditPublicProcurementTenderSource::route('/{record}/edit'),
        ];
    }
}
