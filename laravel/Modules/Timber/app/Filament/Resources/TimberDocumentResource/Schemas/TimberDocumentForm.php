<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Queries\GetTimberProductStockByLotQuery;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberDocumentForm extends XotBaseResourceForm
{
    protected static string $resource = TimberDocumentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'header' => Section::make('Testata')->columns(3)->components([
                'document_type_code' => Select::make('document_type_code')->options(TimberConfigMap::get('timber.document_types'))->default('ddt')->required(),
                'number' => TextInput::make('number')->maxLength(255),
                'document_date' => DatePicker::make('document_date')->default(now())->required(),
                'customer_id' => Select::make('customer_id')->relationship('customer', 'business_name')->searchable()->preload(),
                'status_code' => Select::make('status_code')->options(TimberConfigMap::get('timber.document_statuses'))->default('draft')->required(),
                'reference' => TextInput::make('reference')->maxLength(255),
            ]),
            'lines' => Section::make('Righe')->components([
                Repeater::make('lines')->relationship()->hiddenLabel()->schema([
                    'product_id' => Select::make('product_id')
                        ->options(fn (): array => TimberProduct::query()->where('is_active', true)->orderBy('description')->pluck('description', 'id')->all())
                        ->searchable()->preload()->live()
                        ->afterStateUpdated(function (Set $set, mixed $state): void {
                            $product = is_numeric($state) ? TimberProduct::query()->find($state) : null;
                            if ($product === null) {
                                return;
                            }
                            $set('description', $product->description);
                            $set('unit_of_measure', $product->unit_of_measure);
                            $set('unit_price', (float) ($product->priceForList('ben_b2b') ?? $product->last_cost ?? 0));
                            $lots = app(GetTimberProductStockByLotQuery::class)->execute((int) $product->id);
                            if ($lots !== []) {
                                $best = $lots[0];
                                $set('lot_code', $best['lot_code']);
                                Notification::make()->title('Lotto a magazzino: '.$best['lot_code'])->body('Disponibili '.self::formatQuantity($best['available']))->info()->send();
                            }
                        })
                        ->columnSpan(2),
                    'description' => TextInput::make('description')->required()->columnSpan(2),
                    'quantity' => TextInput::make('quantity')->numeric()->minValue(0)->default(1)->required(),
                    'unit_of_measure' => Select::make('unit_of_measure')->options(TimberConfigMap::get('timber.units_of_measure'))->default('quintali')->required(),
                    'unit_price' => TextInput::make('unit_price')->numeric()->minValue(0)->default(0),
                    'discount_percent' => TextInput::make('discount_percent')->numeric()->minValue(0)->maxValue(100)->default(0),
                    'lot_code' => TextInput::make('lot_code')->maxLength(255)
                        ->helperText(function (Get $get): ?string {
                            $productId = $get('product_id');
                            if (! is_numeric($productId)) {
                                return null;
                            }
                            $total = app(GetTimberProductStockByLotQuery::class)->totalAvailable((int) $productId);

                            return $total > 0 ? 'A magazzino: '.self::formatQuantity($total).' disponibili' : 'Nessuna giacenza a magazzino per questo prodotto';
                        }),
                ])->columns(4)->addActionLabel('Aggiungi riga')->defaultItems(0),
            ]),
            'notes' => Textarea::make('notes')->rows(3)->columnSpanFull(),
        ];
    }

    private static function formatQuantity(float $quantity): string
    {
        return rtrim(rtrim(number_format($quantity, 3, ',', '.'), '0'), ',');
    }
}
