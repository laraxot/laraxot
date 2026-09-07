<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class CustomerSystemForm extends XotBaseResourceForm
{
    protected static string $resource = CustomerSystemResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columns(2)->components([
                'system_type' => Select::make('system_type')
                    ->options(CustomerSystemResource::systemTypeOptions())
                    ->required()
                    ->native(false),
                'code' => TextInput::make('code')->maxLength(255),
                'customer_id' => Select::make('customer_id')
                    ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                    ->searchable()->preload()->live()
                    ->afterStateUpdated(fn (callable $set) => $set('customer_site_id', null))
                    ->required(),
                'customer_site_id' => Select::make('customer_site_id')
                    ->options(function (callable $get): array {
                        $customerId = $get('customer_id');

                        return blank($customerId)
                            ? []
                            : DB::table('customer_sites')->where('customer_id', $customerId)->pluck('name', 'id')->all();
                    })
                    ->searchable()->preload(),
                'description' => TextInput::make('description')->maxLength(255)->columnSpanFull(),
                'brand' => TextInput::make('brand')->maxLength(255),
                'model' => TextInput::make('model')->maxLength(255),
                'serial_number' => TextInput::make('serial_number')->maxLength(255),
                'power_kw' => TextInput::make('power_kw')->numeric()->suffix('kW'),
                'installation_date' => DatePicker::make('installation_date'),
                'is_active' => Toggle::make('is_active')->default(true),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }
}
