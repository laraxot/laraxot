<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\PlatformSettingResource\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class PlatformSettingForm extends XotBaseResourceForm
{
    protected static string $resource = PlatformSettingResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'key' => TextInput::make('key')
                        ->required()
                        ->maxLength(255)
                        ->disabledOn('edit'),
                    'grid' => Grid::make(1)->components([
                        'value_json' => KeyValue::make('value_json')
                            ->required(),
                    ]),
                ])
                ->columns(1),
        ];
    }
}
