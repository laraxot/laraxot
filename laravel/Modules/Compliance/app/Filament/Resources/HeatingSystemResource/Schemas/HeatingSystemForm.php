<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Filament\Resources\HeatingSystemResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class HeatingSystemForm extends XotBaseResourceForm
{
    protected static string $resource = HeatingSystemResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        $fuelOptions = collect((array) config('heating.fuel_types', []))
            ->mapWithKeys(function ($f): array {
                $key = is_string($f) ? $f : '';

                return [$key => __('heating.fuels.'.$key)];
            })
            ->all();

        return [
            'main' => Section::make()->columns(2)->components([
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
                'description' => TextInput::make('description')->maxLength(255),
                'fuel_type' => Select::make('fuel_type')->options($fuelOptions),
                'total_power_kw' => TextInput::make('total_power_kw')->numeric()->suffix('kW'),
                'plant_registry_code' => TextInput::make('plant_registry_code')->maxLength(255),
                'installation_date' => DatePicker::make('installation_date'),
                'is_active' => Toggle::make('is_active')->default(true),
                'notes' => Grid::make(1)->components([
                    Textarea::make('notes')->columnSpanFull(),
                ]),
            ]),
        ];
    }
}
