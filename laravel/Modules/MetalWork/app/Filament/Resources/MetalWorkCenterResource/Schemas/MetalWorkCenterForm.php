<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalWorkCenterResource\Schemas;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Validation\Rules\Unique;
use Modules\MetalWork\Filament\Resources\MetalWorkCenterResource;
use Modules\MetalWork\Support\MetalWorksCosting;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class MetalWorkCenterForm extends XotBaseResourceForm
{
    protected static string $resource = MetalWorkCenterResource::class;

    /**
     * @return array<string, Component|Field>
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
                            ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule): Unique => $rule->where('context_code', MetalWorksCosting::CONTEXT)),
                        TextInput::make('name')
                            ->required(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('hourly_rate')
                            ->numeric()
                            ->required()
                            ->suffix('€/h'),
                        TextInput::make('default_markup_percent')
                            ->numeric()
                            ->suffix('%'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
                    Grid::make(2)->components([
                        Toggle::make('is_internal')->default(true),
                        Hidden::make('context_code')->default(MetalWorksCosting::CONTEXT),
                        Toggle::make('is_active')->default(true),
                    ]),
                ]),
        ];
    }
}
