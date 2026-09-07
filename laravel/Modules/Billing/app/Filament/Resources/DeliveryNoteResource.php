<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\Supplier;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * DDT ricevuti: quello che il fornitore ha davvero consegnato, cioe' il metro
 * su cui si misura quello che poi fattura.
 *
 * Schema form → {@see DeliveryNoteResource\Schemas\DeliveryNoteForm}
 * Schema infolist → {@see DeliveryNoteResource\Schemas\DeliveryNoteInfolist}
 * Tabella → {@see DeliveryNoteResource\Tables\DeliveryNotesTable}
 */
final class DeliveryNoteResource extends XotBaseResource
{
    protected static ?string $model = DeliveryNote::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function getNavigationGroup(): string
    {
        return __('billing::suppliers.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('billing::suppliers.delivery_note');
    }

    public static function getPluralModelLabel(): string
    {
        return __('billing::suppliers.delivery_notes');
    }

    /**
     * @return array<int, string>
     */
    public static function supplierOptions(): array
    {
        return Supplier::query()
            ->orderBy('legal_name')
            ->get(['id', 'legal_name'])
            ->mapWithKeys(static fn (Supplier $supplier): array => [$supplier->id => $supplier->legal_name])
            ->all();
    }
}
