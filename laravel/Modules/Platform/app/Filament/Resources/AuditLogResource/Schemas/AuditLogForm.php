<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\AuditLogResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Platform\Filament\Resources\AuditLogResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class AuditLogForm extends XotBaseResourceForm
{
    protected static string $resource = AuditLogResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'grid' => Grid::make(2)->components([
                        'created_at' => DateTimePicker::make('created_at')
                            ->disabled(),
                        'actor_user_id' => TextInput::make('actor_user_id')
                            ->disabled(),
                        'action' => TextInput::make('action')
                            ->disabled(),
                        'subject_type' => TextInput::make('subject_type')
                            ->disabled(),
                        'subject_id' => TextInput::make('subject_id')
                            ->disabled(),
                        'panel_id' => TextInput::make('panel_id')
                            ->disabled(),
                        'route' => TextInput::make('route')
                            ->disabled(),
                        'ip' => TextInput::make('ip')
                            ->disabled(),
                    ]),
                    'user_agent' => Textarea::make('user_agent')
                        ->columnSpanFull()
                        ->disabled()
                        ->rows(2),
                    'before' => KeyValue::make('before')
                        ->columnSpanFull()
                        ->disabled(),
                    'after' => KeyValue::make('after')
                        ->columnSpanFull()
                        ->disabled(),
                ])
                ->columns(2),
        ];
    }
}
