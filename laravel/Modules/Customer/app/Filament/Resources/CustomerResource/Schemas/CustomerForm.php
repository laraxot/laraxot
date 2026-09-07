<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class CustomerForm extends XotBaseResourceForm
{
    protected static string $resource = CustomerResource::class;

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    TextInput::make('legal_name')
                        ->label(__('customers.fields.legal_name'))
                        ->required()
                        ->maxLength(255),

                    TextInput::make('vat_number')
                        ->label(__('customers.fields.vat_number'))
                        ->maxLength(255),

                    TextInput::make('tax_code')
                        ->label(__('customers.fields.tax_code'))
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label(__('customers.fields.email'))
                        ->email()
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label(__('customers.fields.phone'))
                        ->tel()
                        ->maxLength(255),

                    TextInput::make('address_line1')
                        ->label(__('customers.fields.address_line1'))
                        ->maxLength(255),

                    TextInput::make('address_line2')
                        ->label(__('customers.fields.address_line2'))
                        ->maxLength(255),

                    Grid::make(3)->components([
                        TextInput::make('city')
                            ->label(__('customers.fields.city'))
                            ->maxLength(255),
                        TextInput::make('postal_code')
                            ->label(__('customers.fields.postal_code'))
                            ->maxLength(255),
                        TextInput::make('province')
                            ->label(__('customers.fields.province'))
                            ->maxLength(255),
                    ]),

                    Select::make('country_code')
                        ->label(__('customers.fields.country_code'))
                        ->options([
                            'IT' => 'Italia',
                            'SM' => 'San Marino',
                            'CH' => 'Svizzera',
                            'DE' => 'Germania',
                            'FR' => 'Francia',
                            'AT' => 'Austria',
                        ])
                        ->default('IT')
                        ->searchable()
                        ->preload(),

                    Select::make('payment_method')
                        ->label(__('customers.fields.payment_method'))
                        ->options([
                            'bonifico' => __('customers.payment_methods.bonifico'),
                            'sepa' => __('customers.payment_methods.sepa'),
                            'carta' => __('customers.payment_methods.carta'),
                            'contanti' => __('customers.payment_methods.contanti'),
                        ])
                        ->default('bonifico')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),

                    TextInput::make('iban')
                        ->label(__('customers.fields.iban'))
                        ->maxLength(34)
                        ->visible(function (callable $get): bool {
                            $paymentMethod = $get('payment_method');

                            return is_string($paymentMethod) && $paymentMethod === 'bonifico';
                        })
                        ->dehydrateStateUsing(function (?string $state, callable $get): ?string {
                            $paymentMethod = $get('payment_method');

                            return is_string($paymentMethod) && $paymentMethod === 'bonifico' ? $state : null;
                        }),

                    Textarea::make('notes')
                        ->label(__('customers.fields.notes'))
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label(__('customers.fields.is_active'))
                        ->default(true),
                ])
                ->columns(2),
        ];
    }
}
