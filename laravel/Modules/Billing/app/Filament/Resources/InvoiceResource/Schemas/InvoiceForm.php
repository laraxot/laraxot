<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Filament\Resources\InvoiceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class InvoiceForm extends XotBaseResourceForm
{
    protected static string $resource = InvoiceResource::class;

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
                        'number' => TextInput::make('number')
                            ->maxLength(255),
                        'issued_on' => DatePicker::make('issued_on'),
                    ]),

                    'customer_id' => Select::make('customer_id')
                        ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('work_order_id', null))
                        ->required(),

                    'work_order_id' => Select::make('work_order_id')
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
                                    $code = is_string($row->code) ? $row->code : '';
                                    $title = is_string($row->title) ? $row->title : '';
                                    $label = $code ?: ($title ?: 'Commessa senza codice');

                                    return [$id => $label];
                                })
                                ->all();
                        })
                        ->searchable()
                        ->preload(),

                    'total' => TextInput::make('total')
                        ->numeric()
                        ->default(0),

                    'notes' => Textarea::make('notes')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
