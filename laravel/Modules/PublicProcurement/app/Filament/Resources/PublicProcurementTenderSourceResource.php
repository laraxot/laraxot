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

    public static function getPages(): array
    {
        return [
            'index' => ListPublicProcurementTenderSources::route('/'),
            'create' => CreatePublicProcurementTenderSource::route('/create'),
            'edit' => EditPublicProcurementTenderSource::route('/{record}/edit'),
        ];
    }
}
