<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Anagrafica fornitori: la testa del ciclo passivo.
 */
final class SupplierForm extends XotBaseResourceForm
{
    public function getFormSchemaColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'legal_name' => TextInput::make('legal_name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    'grid' => Grid::make(2)->components([
                        'vat_number' => TextInput::make('vat_number')
                            ->maxLength(255),
                        'phone' => TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        'email' => TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        'is_active' => Toggle::make('is_active')
                            ->default(true),
                    ]),
                ])
                ->columns(2),
        ];
    }
}
