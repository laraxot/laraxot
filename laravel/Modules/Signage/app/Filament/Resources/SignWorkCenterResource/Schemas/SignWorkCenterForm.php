<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignWorkCenterResource\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Validation\Rules\Unique;
use Modules\Signage\Filament\Resources\SignWorkCenterResource;
use Modules\Signage\Support\SignageCosting;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Work centres and the markup each one sells at. Editable because the list is
 * the customer's, not ours: they add the ones we did not guess.
 */
final class SignWorkCenterForm extends XotBaseResourceForm
{
    protected static string $resource = SignWorkCenterResource::class;

    public function getFormColumns(): int
    {
        return 1;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule): Unique => $rule->where('context_code', SignageCosting::CONTEXT)),
                        TextInput::make('name')
                            ->required(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('default_markup_percent')
                            ->helperText(__('signage::signage.pricing.markup_hint'))
                            ->numeric()
                            ->suffix('%'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_internal')
                            ->default(true),
                    ]),
                    Hidden::make('context_code')->default(SignageCosting::CONTEXT),
                    Toggle::make('is_active')->default(true),
                ]),
        ];
    }
}
