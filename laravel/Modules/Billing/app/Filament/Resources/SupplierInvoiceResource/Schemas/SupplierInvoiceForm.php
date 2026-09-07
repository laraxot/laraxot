<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class SupplierInvoiceForm extends XotBaseResourceForm
{
    public static function getFormSchemaColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'grid' => Grid::make(2)->components([
                        'supplier_id' => Select::make('supplier_id')
                            ->options(fn (): array => DeliveryNoteResource::supplierOptions())
                            ->searchable()
                            ->preload()
                            ->required(),
                        'number' => TextInput::make('number')
                            ->required()
                            ->maxLength(255),
                        'issued_on' => DatePicker::make('issued_on')
                            ->displayFormat('d/m/Y'),
                        'total' => TextInput::make('total')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('EUR')
                            ->default(0),
                        'status_code' => Select::make('status_code')
                            ->options(self::statusOptions())
                            ->default('pending')
                            ->required(),
                    ]),

                    'notes' => Textarea::make('notes')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function statusOptions(): array
    {
        return [
            'pending' => __('billing::suppliers.statuses.pending'),
            'reconciled' => __('billing::suppliers.statuses.reconciled'),
            'disputed' => __('billing::suppliers.statuses.disputed'),
        ];
    }
}
