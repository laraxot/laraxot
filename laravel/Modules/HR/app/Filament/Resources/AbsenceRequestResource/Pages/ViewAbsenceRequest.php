<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewAbsenceRequest extends XotBaseViewRecord
{
    protected static string $resource = AbsenceRequestResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'type' => TextEntry::make('type'),
            'starts_at' => TextEntry::make('starts_at'),
            'ends_at' => TextEntry::make('ends_at'),
            'status' => TextEntry::make('status'),
        ];
    }
}
