<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\WorkHourResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class WorkHourInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'employee.name' => TextEntry::make('employee.name'),
            'type' => TextEntry::make('type'),
            'timestamp' => TextEntry::make('timestamp')->dateTime(),
            'location_name' => TextEntry::make('location_name'),
            'status' => TextEntry::make('status'),
            'approved_by' => TextEntry::make('approvedBy.name'),
            'approved_at' => TextEntry::make('approved_at')->dateTime(),
            'notes' => TextEntry::make('notes'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
        ];
    }
}
