<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource\Schemas;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Le fasi di una commessa sono diverse in ogni officina (preventivo →
 * approvazione → produzione → spedizione → chiusa per un insegnista, un'altra
 * lista per un idraulico). Modificabili qui invece che in un seeder
 * irraggiungibile.
 */
class WorkOrderStatusForm extends XotBaseResourceForm
{
    protected static string $resource = WorkOrderStatusResource::class;

    /**
     * @return array<int|string, Component|Field>
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('code')
                ->helperText(__('workorder::work_orders.status_help.code'))
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Select::make('color')
                ->options([
                    'gray' => 'gray',
                    'blue' => 'blue',
                    'amber' => 'amber',
                    'green' => 'green',
                    'red' => 'red',
                ]),
            TextInput::make('sort_order')
                ->numeric()
                ->default(0),
            Toggle::make('is_terminal')
                ->helperText(__('workorder::work_orders.status_help.is_terminal_hint')),
        ];
    }
}
