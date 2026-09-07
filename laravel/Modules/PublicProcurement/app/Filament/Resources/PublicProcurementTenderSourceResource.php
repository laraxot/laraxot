<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages\CreatePublicProcurementTenderSource;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages\EditPublicProcurementTenderSource;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages\ListPublicProcurementTenderSources;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class PublicProcurementTenderSourceResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = PublicProcurementTenderSource::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rss';

    public static function getNavigationGroup(): string
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

    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')->required(),
            'source_type' => Select::make('source_type')->label(__('public_procurement.fields.source_type'))->options(['manual' => 'Manuale', 'portal' => 'Portale', 'rss' => 'RSS/API'])->default('manual'),
            'url' => TextInput::make('url')->label(__('public_procurement.fields.url'))->url(),
            'is_active' => Toggle::make('is_active')->default(true),
            'notes' => Textarea::make('notes')->label(__('public_procurement.fields.notes'))->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('source_type')->label(__('public_procurement.fields.source_type'))->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('url')->label(__('public_procurement.fields.url'))->limit(50),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([DeleteBulkAction::make()]);
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
