<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use Illuminate\Database\Eloquent\Model;
use Modules\Billing\Models\Supplier;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Anagrafica fornitori: la testa del ciclo passivo.
 *
 * Schema form → {@see SupplierResource\Schemas\SupplierForm}
 * Schema infolist → {@see SupplierResource\Schemas\SupplierInfolist}
 * Tabella → {@see SupplierResource\Tables\SuppliersTable}
 */
final class SupplierResource extends XotBaseResource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $recordTitleAttribute = 'legal_name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function getNavigationGroup(): string
    {
        return __('billing::suppliers.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('billing::suppliers.supplier');
    }

    public static function getPluralModelLabel(): string
    {
        return __('billing::suppliers.suppliers');
    }

    /**
     * @return array<string, string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['legal_name', 'vat_number', 'email', 'phone'];
    }

    /**
     * @return array<string, string|null>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Supplier $record */
        return array_filter([
            'P.IVA' => $record->vat_number,
            'Email' => $record->email,
        ]);
    }
}
