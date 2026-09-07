<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberPaymentInstallmentForm extends XotBaseResourceForm
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columns(2)->components([
                'invoice_id' => Select::make('invoice_id')->relationship('invoice', 'number')->searchable()->preload(),
                'customer_id' => Select::make('customer_id')->relationship('customer', 'business_name')->searchable()->preload(),
                'direction' => Select::make('direction')->options(['in' => 'Entrata (incasso)', 'out' => 'Uscita (pagamento)'])->default('in')->required(),
                'due_date' => DatePicker::make('due_date')->required(),
                'amount' => TextInput::make('amount')->numeric()->minValue(0)->required(),
                'payment_method_code' => Select::make('payment_method_code')->options(TimberConfigMap::get('timber.payment_methods')),
                'status_code' => Select::make('status_code')->options(TimberConfigMap::get('timber.payment_statuses'))->default('da_saldare')->required(),
                'notes' => Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ];
    }
}
