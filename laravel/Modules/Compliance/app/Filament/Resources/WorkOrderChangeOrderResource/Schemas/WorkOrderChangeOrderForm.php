<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class WorkOrderChangeOrderForm extends XotBaseResourceForm
{
    protected static string $resource = WorkOrderChangeOrderResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'work_order_id' => Select::make('work_order_id')
                    ->required()
                    ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                    ->searchable(),
                'grid' => Grid::make(3)->components([
                    'title' => TextInput::make('title')->required()->maxLength(255),
                    'estimated_amount' => TextInput::make('estimated_amount')->numeric()->prefix('EUR'),
                    'status' => Select::make('status')->default('proposed')->options(self::statuses()),
                ]),
                'description' => Textarea::make('description')->columnSpanFull(),
            ]),
        ];
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            'proposed' => 'Proposta',
            'pending_approval' => 'Da approvare',
            'approved' => 'Approvata',
            'executed' => 'Eseguita',
            'billed' => 'Fatturata',
            'rejected' => 'Respinta',
        ];
    }
}
