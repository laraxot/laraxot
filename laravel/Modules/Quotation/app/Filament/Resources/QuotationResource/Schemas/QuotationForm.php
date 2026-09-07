<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

use function Safe\json_encode;

final class QuotationForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'code' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')

                            ->maxLength(255),
                        Select::make('status_code')

                            ->options([
                                'draft' => __('quotations.statuses.draft'),
                                'sent' => __('quotations.statuses.sent'),
                                'confirmed' => __('quotations.statuses.confirmed'),
                                'rejected' => __('quotations.statuses.rejected'),
                            ])
                            ->required()
                            ->default('draft'),
                    ]),

                    TextInput::make('project')

                        ->helperText(__('quotations.fields.project_hint'))
                        ->maxLength(255),

                    Select::make('customer_id')

                        ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('work_order_id', null))
                        ->required()
                        ->createOptionForm([
                            TextInput::make('legal_name')->required()->maxLength(255),
                            TextInput::make('vat_number')->maxLength(255),
                            TextInput::make('city')->maxLength(255),
                            TextInput::make('email')->email()->maxLength(255),
                            TextInput::make('phone')->maxLength(255),
                        ])
                        ->createOptionUsing(function (array $data): int {
                            $key = Customer::query()->create([
                                'legal_name' => $data['legal_name'],
                                'vat_number' => $data['vat_number'] ?? null,
                                'city' => $data['city'] ?? null,
                                'email' => $data['email'] ?? null,
                                'phone' => $data['phone'] ?? null,
                                'is_active' => true,
                            ])->getKey();

                            return is_numeric($key) ? (int) $key : 0;
                        }),

                    Select::make('work_order_id')

                        ->options(function (callable $get): array {
                            $q = DB::table('work_orders')->orderByDesc('id');
                            $customerId = $get('customer_id');
                            if (! blank($customerId)) {
                                $q->where('customer_id', $customerId);
                            }

                            return $q
                                ->limit(200)
                                ->get(['id', 'code', 'title'])
                                ->mapWithKeys(function ($row): array {
                                    $id = is_numeric($row->id) ? (int) $row->id : 0;
                                    $label = $row->code;
                                    if (! is_string($label) || $label === '') {
                                        $label = $row->title;
                                    }
                                    if (! is_string($label) || $label === '') {
                                        $label = 'Commessa senza codice';
                                    }

                                    return [$id => $label];
                                })
                                ->all();
                        })
                        ->searchable()
                        ->preload(),

                    TextInput::make('title')

                        ->maxLength(255),

                    TextInput::make('tax_percent')

                        ->numeric()
                        ->minValue(0)
                        ->default(22),

                    Grid::make(2)->components([
                        DateTimePicker::make('issued_at'),
                        DateTimePicker::make('confirmed_at'),
                    ]),

                    Grid::make(2)->components([
                        DateTimePicker::make('delivery_at'),
                        TextInput::make('lead_time_days')

                            ->helperText(__('quotations.fields.lead_time_days_hint'))
                            ->numeric()
                            ->minValue(0),
                    ]),

                    Textarea::make('notes')

                        ->columnSpanFull(),

                    Grid::make(3)->components([
                        TextInput::make('subtotal')

                            ->numeric()
                            ->disabled(),
                        TextInput::make('discount_amount')

                            ->numeric()
                            ->disabled(),
                        TextInput::make('taxable_amount')

                            ->numeric()
                            ->disabled(),
                    ]),
                    Grid::make(2)->components([
                        TextInput::make('tax_amount')

                            ->numeric()
                            ->disabled(),
                        TextInput::make('total')

                            ->numeric()
                            ->disabled(),
                    ]),

                    Grid::make(2)->components([
                        TextInput::make('cost_total')

                            ->helperText(__('quotations.fields.cost_total_hint'))
                            ->numeric()
                            ->minValue(0),
                        TextEntry::make('margin_summary')

                            ->state(fn (?Quotation $record): string => QuotationResource::marginSummary($record)),
                    ]),
                ])
                ->columns(2),
            'voice_transcript' => Section::make(__('quotations.voice.section_title'))
                ->collapsible()
                ->collapsed()
                ->columnSpanFull()
                ->visible(function ($record): bool {
                    if (! $record instanceof Quotation) {
                        return false;
                    }

                    return filled($record->voice_transcript) || filled($record->voice_ai_response);
                })
                ->components([
                    Textarea::make('voice_transcript')

                        ->disabled()
                        ->rows(3)
                        ->columnSpanFull(),
                    Textarea::make('voice_ai_response')

                        ->disabled()
                        ->rows(10)
                        ->formatStateUsing(function (mixed $state): string {
                            if (is_array($state)) {
                                return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            }

                            return is_scalar($state) ? (string) $state : '';
                        })
                        ->columnSpanFull(),
                ]),
        ];
    }
}
