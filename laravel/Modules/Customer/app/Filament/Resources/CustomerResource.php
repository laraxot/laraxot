<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Filament\Resources\CustomerResource\Pages;
use Modules\Customer\Filament\Resources\CustomerResource\RelationManagers\ContactsRelationManager;
use Modules\Customer\Filament\Resources\CustomerResource\RelationManagers\CustomerSitesRelationManager;
use Modules\Customer\Filament\Resources\CustomerResource\RelationManagers\WorkOrdersRelationManager;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class CustomerResource extends XotBaseResource
{
    protected static ?string $model = Customer::class;

    protected static ?string $recordTitleAttribute = 'legal_name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['legal_name', 'vat_number', 'tax_code', 'email', 'phone', 'city'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Customer $record */
        return array_filter([
            (string) __('customers.fields.vat_number') => is_string($record->vat_number) ? $record->vat_number : null,
            (string) __('customers.fields.city') => is_string($record->city) ? $record->city : null,
            (string) __('customers.fields.phone') => is_string($record->phone) ? $record->phone : null,
        ]);
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function getNavigationGroup(): string
    {
        return (string) __('customers.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('customers.customer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('customers.customers');
    }

    public static function getRelations(): array
    {
        return [
            WorkOrdersRelationManager::class,
            CustomerSitesRelationManager::class,
            ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if (request()->is('admin/*') && $user instanceof User && AccountFeatures::hasAssignedPerimeter($user)) {
            AccountFeatures::scopeAssignedCustomers($query, $user);
        }

        return $query;
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.customers',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: ! request()->is('customer/*') && ! request()->is('supplier/*'),
        );
    }
}
