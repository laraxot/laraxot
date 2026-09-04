<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Billing\Filament\Resources\SupplierResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSupplier extends XotBaseViewRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'legal_name' => TextEntry::make('legal_name'),
            'vat_number' => TextEntry::make('vat_number'),
            'email' => TextEntry::make('email'),
            'phone' => TextEntry::make('phone'),
            'delivery_notes_count' => TextEntry::make('delivery_notes_count'),
            'invoices_count' => TextEntry::make('invoices_count'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
