<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Quotation\Models\Quotation;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class SiteSurveyForm extends XotBaseResourceForm
{
    protected static string $resource = SiteSurveyResource::class;

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'general' => Section::make('site_surveys.sections.general')
                ->columnSpanFull()
                ->components([
                    'grid' => Grid::make(2)->components([
                        'code' => TextInput::make('code')
                            ->maxLength(255),
                        'status_code' => Select::make('status_code')
                            ->options(fn () => SiteSurveyResource::statusOptions())
                            ->required()
                            ->default('planned'),
                    ]),
                    'title' => TextInput::make('title')
                        ->columnSpanFull()
                        ->maxLength(255),
                    'dates' => Grid::make(2)->components([
                        'scheduled_at' => DateTimePicker::make('scheduled_at')
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                        'performed_at' => DateTimePicker::make('performed_at')
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                    ]),
                ])
                ->columns(2),
            'links' => Section::make('site_surveys.sections.links')
                ->columnSpanFull()
                ->components([
                    'grid' => Grid::make(2)->components([
                        'customer_id' => Select::make('customer_id')
                            ->options(fn (): array => Customer::query()
                                ->orderBy('legal_name')
                                ->pluck('legal_name', 'id')
                                ->all())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(static function (callable $set): void {
                                $set('customer_site_id', null);
                            })
                            ->required(),
                        'customer_site_id' => Select::make('customer_site_id')
                            ->options(static function (callable $get): array {
                                $rawCustomerId = $get('customer_id');
                                $customerId = is_numeric($rawCustomerId) ? (int) $rawCustomerId : 0;
                                if ($customerId <= 0) {
                                    return [];
                                }

                                return CustomerSite::query()
                                    ->where('customer_id', $customerId)
                                    ->whereNotNull('name')
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(static fn (callable $get): bool => blank($get('customer_id'))),
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
                        'quotation_id' => Select::make('quotation_id')
                            ->options(fn (): array => Quotation::query()
                                ->orderByDesc('id')
                                ->limit(200)
                                ->get(['id', 'code', 'title'])
                                ->mapWithKeys(static fn (Quotation $quotation): array => [
                                    $quotation->id => (string) ($quotation->code ?? $quotation->title ?? $quotation->id),
                                ])
                                ->all())
                            ->searchable()
                            ->preload(),
                        'performed_by_user_id' => Select::make('performed_by_user_id')
                            ->relationship('performedBy', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                ])
                ->columns(2),
            'outcome' => Section::make('site_surveys.sections.outcome')
                ->columnSpanFull()
                ->components([
                    'notes' => Textarea::make('notes')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),
        ];
    }
}
