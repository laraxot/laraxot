<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Customer\Models\Customer;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class PublicProcurementContractForm extends XotBaseResourceForm
{
    protected static string $resource = PublicProcurementContractResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(3)->components([
                    'code' => TextInput::make('code')->label(__('public_procurement.fields.code'))->maxLength(255),
                    'status' => Select::make('status')
                        ->label(__('public_procurement.fields.status'))
                        ->options(PublicProcurementContractResource::statuses())
                        ->default('draft')
                        ->required(),
                    'work_order_id' => Select::make('work_order_id')
                        ->label(__('public_procurement.fields.work_order'))
                        ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(300)->pluck('title', 'id')->all())
                        ->searchable(),
                ]),
                'title' => TextInput::make('title')->label(__('public_procurement.fields.title'))->required()->maxLength(255)->columnSpanFull(),
                'grid2' => Grid::make(3)->components([
                    'contracting_authority' => TextInput::make('contracting_authority')->label(__('public_procurement.fields.contracting_authority'))->required()->maxLength(255),
                    'customer_id' => Select::make('customer_id')
                        ->label(__('public_procurement.fields.customer'))
                        ->options(fn (): array => Customer::query()->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    'procedure_type' => TextInput::make('procedure_type')->label(__('public_procurement.fields.procedure_type'))->maxLength(255),
                    'cig' => TextInput::make('cig')->label(__('public_procurement.fields.cig'))->maxLength(255),
                    'cup' => TextInput::make('cup')->label(__('public_procurement.fields.cup'))->maxLength(255),
                    'rup_name' => TextInput::make('rup_name')->label(__('public_procurement.fields.rup_name'))->maxLength(255),
                    'works_director_name' => TextInput::make('works_director_name')->label(__('public_procurement.fields.works_director_name'))->maxLength(255),
                    'safety_coordinator_name' => TextInput::make('safety_coordinator_name')->label(__('public_procurement.fields.safety_coordinator_name'))->maxLength(255),
                ]),
                'grid3' => Grid::make(4)->components([
                    'contract_amount' => TextInput::make('contract_amount')->label(__('public_procurement.fields.contract_amount'))->numeric()->default(0),
                    'security_costs' => TextInput::make('security_costs')->label(__('public_procurement.fields.security_costs'))->numeric()->default(0),
                    'vat_rate' => TextInput::make('vat_rate')->label(__('public_procurement.fields.vat_rate'))->numeric()->default(22),
                    'retention_rate' => TextInput::make('retention_rate')->label(__('public_procurement.fields.retention_rate'))->numeric()->default(0),
                ]),
                'grid4' => Grid::make(3)->components([
                    'awarded_on' => DatePicker::make('awarded_on'),
                    'signed_on' => DatePicker::make('signed_on'),
                    'site_handover_on' => DatePicker::make('site_handover_on'),
                    'works_start_on' => DatePicker::make('works_start_on'),
                    'works_due_on' => DatePicker::make('works_due_on')->label(__('public_procurement.fields.works_due_on')),
                    'works_completed_on' => DatePicker::make('works_completed_on'),
                ]),
                'description' => Textarea::make('description')->columnSpanFull(),
                'notes' => Textarea::make('notes')->label(__('public_procurement.fields.notes'))->columnSpanFull(),
            ]),
        ];
    }
}
