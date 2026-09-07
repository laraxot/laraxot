<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class AbsenceRequestInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'user.name' => TextEntry::make('user.name')->label(__('employee::absence_request.fields.user')),
            'type' => TextEntry::make('type')->label(__('employee::absence_request.fields.type')),
            'status' => TextEntry::make('status')->label(__('employee::absence_request.fields.status')),
            'starts_at' => TextEntry::make('starts_at')->label(__('employee::absence_request.fields.starts_at'))->dateTime(),
            'ends_at' => TextEntry::make('ends_at')->label(__('employee::absence_request.fields.ends_at'))->dateTime(),
            'notes' => TextEntry::make('notes')->label(__('employee::absence_request.fields.notes')),
            'decidedBy.name' => TextEntry::make('decidedBy.name')->label(__('employee::absence_request.fields.decided_by')),
            'decided_at' => TextEntry::make('decided_at')->label(__('employee::absence_request.fields.decided_at'))->dateTime(),
        ];
    }
}
