<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * DDT ricevuti: quello che il fornitore ha davvero consegnato, cioe' il metro
 * su cui si misura quello che poi fattura.
 */
final class DeliveryNoteForm extends XotBaseResourceForm
{
    protected static string $resource = DeliveryNoteResource::class;

    public function getFormSchemaColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
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
                        'received_on' => DatePicker::make('received_on')
                            ->displayFormat('d/m/Y'),
                        'work_order_id' => Select::make('work_order_id')
                            ->options(fn (): array => WorkOrder::query()
                                ->orderByDesc('id')
                                ->limit(200)
                                ->get(['id', 'code', 'title'])
                                ->mapWithKeys(static fn (WorkOrder $workOrder): array => [
                                    $workOrder->id => (string) ($workOrder->code ?? $workOrder->title ?? $workOrder->id),
                                ])
                                ->all())
                            ->searchable()
                            ->preload(),
                    ]),

                    'notes' => Textarea::make('notes')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
