<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class TimberWoodSpeciesForm extends XotBaseResourceForm
{
    protected static string $resource = TimberWoodSpeciesResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columns(2)->components([
                'name' => TextInput::make('name')->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state, ?string $old, Get $get): void {
                        $code = $get('code');
                        if (($code === null || $code === '') && is_string($state)) {
                            $set('code', Str::slug($state, '_'));
                        }
                    }),
                'code' => TextInput::make('code')->required()->maxLength(255)->unique(ignoreRecord: true),
                'grid' => Grid::make(2)->components([
                    'sort_order' => TextInput::make('sort_order')->integer()->default(0),
                    'is_active' => Toggle::make('is_active')->default(true),
                ]),
            ]),
        ];
    }
}
