<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Compliance\Filament\Resources\EquipmentResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class EquipmentForm extends XotBaseResourceForm
{
    protected static string $resource = EquipmentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(3)->components([
                    'name' => TextInput::make('name')->required()->maxLength(255),
                    'type' => TextInput::make('type')->maxLength(255),
                    'serial_number' => TextInput::make('serial_number')->maxLength(255),
                    'status' => Select::make('status')->default('available')->options([
                        'available' => 'Disponibile',
                        'assigned' => 'Assegnata',
                        'maintenance' => 'In manutenzione',
                        'blocked' => 'Bloccata',
                    ]),
                    'last_checked_on' => DatePicker::make('last_checked_on'),
                    'next_check_due_on' => DatePicker::make('next_check_due_on'),
                    'maintenance_due_on' => DatePicker::make('maintenance_due_on'),
                    'assigned_work_order_id' => Select::make('assigned_work_order_id')
                        ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                        ->searchable(),
                ]),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }
}
