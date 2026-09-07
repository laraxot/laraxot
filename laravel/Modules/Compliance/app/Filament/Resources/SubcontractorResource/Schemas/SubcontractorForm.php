<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Compliance\Filament\Resources\SubcontractorResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class SubcontractorForm extends XotBaseResourceForm
{
    protected static string $resource = SubcontractorResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(2)->components([
                    'business_name' => TextInput::make('business_name')->required()->maxLength(255),
                    'status' => Select::make('status')->default('active')->options([
                        'active' => 'Attivo',
                        'suspended' => 'Sospeso',
                        'blocked' => 'Bloccato',
                    ]),
                    'vat_number' => TextInput::make('vat_number')->maxLength(50),
                    'tax_code' => TextInput::make('tax_code')->maxLength(50),
                    'contact_name' => TextInput::make('contact_name')->maxLength(255),
                    'email' => TextInput::make('email')->email()->maxLength(255),
                    'phone' => TextInput::make('phone')->tel()->maxLength(255),
                ]),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }
}
