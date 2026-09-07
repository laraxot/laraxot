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
