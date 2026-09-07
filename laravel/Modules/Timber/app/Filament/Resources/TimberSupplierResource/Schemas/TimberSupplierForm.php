<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Timber\Filament\Resources\TimberSupplierResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberSupplierForm extends XotBaseResourceForm
{
    protected static string $resource = TimberSupplierResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    'legal_name' => TextInput::make('legal_name')->required()->maxLength(255),
                    'vat_number' => TextInput::make('vat_number')->maxLength(255),
                    'tax_code' => TextInput::make('tax_code')->maxLength(255),
                    'email' => TextInput::make('email')->email()->maxLength(255),
                    'phone' => TextInput::make('phone')->maxLength(255),
                    'certifications' => TextInput::make('certifications')->maxLength(255),
                    'city' => TextInput::make('city')->maxLength(255),
                    'province' => TextInput::make('province')->maxLength(255),
                    'is_active' => Toggle::make('is_active')->default(true),
                ]),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
            'contactNotes' => Section::make('Foglio appunti')->description('Annotazioni datate sul fornitore')->columnSpanFull()->components([
                Repeater::make('contactNotes')->relationship()->hiddenLabel()->schema([
                    Textarea::make('body')->required()->rows(2),
                ])->addActionLabel('Aggiungi appunto')->defaultItems(0),
            ]),
        ];
    }
}
