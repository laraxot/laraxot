<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\WorkHourResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class WorkHourForm extends XotBaseResourceForm
{
    /**
     * @return array<string|int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make('Time Entry Details')
                ->schema([
                    Select::make('employee_id')->relationship('employee', 'name')->required(),
                    DateTimePicker::make('clock_in')->required(),
                    DateTimePicker::make('clock_out'),
                    Textarea::make('notes')->maxLength(65535),
                ])
                ->columns(2),
        ];
    }
}
