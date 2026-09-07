<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\CustomerSite;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class WorkOrderForm extends XotBaseResourceForm
{
    protected static string $resource = WorkOrderResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->maxLength(255),

                        Select::make('status_id')
                            ->options(fn () => WorkOrderStatus::query()->whereNotNull('name')->orderBy('sort_order')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload(),
                    ]),

                    Select::make('customer_id')
                        ->options(fn () => WorkOrderResource::getCustomerOptionsForCurrentPanel())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (callable $set): void {
                            $set('customer_site_id', null);
                            $set('primary_contact_id', null);
                        })
                        ->required(),

                    Grid::make(2)->components([
                        Select::make('customer_site_id')
                            ->options(function (callable $get): array {
                                $customerId = is_int($get('customer_id')) ? (int) $get('customer_id') : 0;
                                if ($customerId <= 0) {
                                    return [];
                                }

                                return CustomerSite::query()
                                    ->where('customer_id', $customerId)
                                    ->whereNotNull('name')
                                    ->orderBy('id')
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn (callable $get): bool => blank($get('customer_id')))
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('primary_contact_id', null)),

                        Select::make('primary_contact_id')
                            ->options(function (callable $get): array {
                                $customerId = is_int($get('customer_id')) ? (int) $get('customer_id') : 0;
                                if ($customerId <= 0) {
                                    return [];
                                }

                                $siteId = is_int($get('customer_site_id')) ? (int) $get('customer_site_id') : 0;

                                return Contact::query()
                                    ->where('customer_id', $customerId)
                                    ->when($siteId > 0, fn ($q) => $q->where('customer_site_id', $siteId))
                                    ->orderBy('full_name')
                                    ->pluck('full_name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn (callable $get): bool => blank($get('customer_id'))),
                    ]),

                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->columnSpanFull(),

                    Grid::make(2)->components([
                        DateTimePicker::make('opened_at'),
                        DateTimePicker::make('scheduled_at'),
                        DateTimePicker::make('delivery_due_at')

                            ->helperText(__('workorder::work_orders.fields.delivery_due_at_hint')),
                        DateTimePicker::make('started_at'),
                        DateTimePicker::make('completed_at'),
                    ]),

                    Grid::make(2)->components([
                        TextInput::make('planned_minutes')
                            ->helperText(__('workorder::work_orders.effort.hint'))
                            ->numeric()
                            ->minValue(0)
                            ->step(1),
                        TextInput::make('progress_percent')

                            ->helperText(__('workorder::work_orders.progress.hint'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(5)
                            ->default(0)
                            ->suffix('%'),

                        TextEntry::make('effort_summary')
                            ->state(fn (?WorkOrder $record): string => WorkOrderResource::effortSummary($record)),
                    ]),
                ])
                ->columns(2),
        ];
    }
}
