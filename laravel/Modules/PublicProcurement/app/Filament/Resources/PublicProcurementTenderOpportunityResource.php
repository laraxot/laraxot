<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
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
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\Customer;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\PublicProcurement\Actions\CreateContractFromOpportunityAction;
use Modules\PublicProcurement\Actions\EvaluateTenderEligibilityAction;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\CreatePublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\EditPublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\ListPublicProcurementTenderOpportunities;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages\ViewPublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\RelationManagers\EligibilityChecksRelationManager;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class PublicProcurementTenderOpportunityResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = PublicProcurementTenderOpportunity::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    public static function getNavigationGroup(): string
    {
        return __('public_procurement.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('public_procurement.tender_opportunity');
    }

    public static function getPluralModelLabel(): string
    {
        return __('public_procurement.tender_opportunities');
    }

    public static function getRelations(): array
    {
        return [
            EligibilityChecksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicProcurementTenderOpportunities::route('/'),
            'create' => CreatePublicProcurementTenderOpportunity::route('/create'),
            'view' => ViewPublicProcurementTenderOpportunity::route('/{record}'),
            'edit' => EditPublicProcurementTenderOpportunity::route('/{record}/edit'),
        ];
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            'new' => __('public_procurement.statuses.new'),
            'to_evaluate' => __('public_procurement.statuses.to_evaluate'),
            'interesting' => __('public_procurement.statuses.interesting'),
            'needs_documents' => __('public_procurement.statuses.needs_documents'),
            'blocked' => __('public_procurement.statuses.blocked'),
            'discarded' => __('public_procurement.statuses.discarded'),
            'participation_started' => __('public_procurement.statuses.participation_started'),
            'awarded' => __('public_procurement.statuses.awarded'),
            'lost' => __('public_procurement.statuses.lost'),
        ];
    }
}
