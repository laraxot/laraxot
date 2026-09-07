<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources;

use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Customer\Models\Customer;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\PublicProcurement\Actions\GenerateProgressReportFromMeasurementsAction;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\CreatePublicProcurementContract;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\EditPublicProcurementContract;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\ListPublicProcurementContracts;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages\ViewPublicProcurementContract;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\DeadlinesRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\DocumentRequirementsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\GuaranteesRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\MeasurementsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\OfficialRecordsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\PriceItemsRelationManager;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers\ProgressReportsRelationManager;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class PublicProcurementContractResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = PublicProcurementContract::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationGroup(): string
    {
        return __('public_procurement.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('public_procurement.contract');
    }

    public static function getPluralModelLabel(): string
    {
        return __('public_procurement.contracts');
    }

    public static function getRelations(): array
    {
        return [
            DocumentRequirementsRelationManager::class,
            PriceItemsRelationManager::class,
            MeasurementsRelationManager::class,
            ProgressReportsRelationManager::class,
            DeadlinesRelationManager::class,
            OfficialRecordsRelationManager::class,
            GuaranteesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicProcurementContracts::route('/'),
            'create' => CreatePublicProcurementContract::route('/create'),
            'view' => ViewPublicProcurementContract::route('/{record}'),
            'edit' => EditPublicProcurementContract::route('/{record}/edit'),
        ];
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            'draft' => __('public_procurement.statuses.draft'),
            'tender_participation' => __('public_procurement.statuses.tender_participation'),
            'active' => __('public_procurement.statuses.active'),
            'completed' => __('public_procurement.statuses.completed'),
            'lost' => __('public_procurement.statuses.lost'),
        ];
    }
}
