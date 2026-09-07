<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Schemas;

use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Modules\Catalog\Models\Product;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberInvoiceForm extends XotBaseResourceForm
{
    protected static string $resource = TimberInvoiceResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'header' => Section::make('Testata')->columns(3)->components([
                'kind' => Select::make('kind')->options(TimberConfigMap::get('timber.invoice_kinds'))->default('fattura')->required()->disabledOn('edit'),
                'number' => TextInput::make('number')->maxLength(255),
                'invoice_date' => DatePicker::make('invoice_date')->default(now())->required(),
                'customer_id' => Select::make('customer_id')->relationship('customer', 'business_name')->searchable()->preload(),
                'payment_method_code' => Select::make('payment_method_code')->options(TimberConfigMap::get('timber.payment_methods')),
                'payment_terms' => TextInput::make('payment_terms')->maxLength(255),
            ]),
            'lines' => Section::make('Righe')->components([
                Repeater::make('lines')->relationship()->hiddenLabel()->schema([
                    'product_id' => Select::make('product_id')
                        ->options(fn (): array => Product::query()->where('is_active', true)->orderBy('description')->pluck('description', 'id')->all())
                        ->searchable()->preload()->live()
                        ->afterStateUpdated(function (Set $set, mixed $state): void {
                            $product = is_numeric($state) ? Product::query()->find($state) : null;
                            if ($product instanceof Product) {
                                $description = $product->getAttribute('description');
                                if (is_string($description) && $description !== '') {
                                    $set('description', $description);
                                }
                                $unitPrice = $product->getAttribute('unit_price');
                                if (is_numeric($unitPrice)) {
                                    $set('unit_price', (float) $unitPrice);
                                }
                            }
                        })
                        ->columnSpan(2),
                    'description' => TextInput::make('description')->required()->columnSpan(2),
                    'quantity' => TextInput::make('quantity')->numeric()->minValue(0)->default(1)->required()
                        ->rule(static function (): Closure {
                            return static function (string $attribute, mixed $value, Closure $fail): void {
                                if (! is_numeric($value) || (float) $value <= 0) {
                                    $fail('La quantita deve essere positiva.');
                                }
                            };
                        }),
                    'unit_of_measure' => Select::make('unit_of_measure')->options(TimberConfigMap::get('timber.units_of_measure'))->default('quintali')->required(),
                    'unit_price' => TextInput::make('unit_price')->numeric()->minValue(0)->default(0),
                    'discount_percent' => TextInput::make('discount_percent')->numeric()->minValue(0)->maxValue(100)->default(0),
                    'vat_rate' => Select::make('vat_rate')->options(TimberConfigMap::get('timber.vat_rates'))->default('22')->required(),
                    'lot_code' => TextInput::make('lot_code')->maxLength(255),
                ])->columns(4)->addActionLabel('Aggiungi riga')->defaultItems(0),
            ]),
            'notes' => Textarea::make('notes')->rows(3)->columnSpanFull(),
        ];
    }
}
