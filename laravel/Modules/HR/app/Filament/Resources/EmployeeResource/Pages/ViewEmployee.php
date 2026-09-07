<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\HR\Filament\Resources\EmployeeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewEmployee extends XotBaseViewRecord
{
    protected static string $resource = EmployeeResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'last_name' => TextEntry::make('last_name'),
            'first_name' => TextEntry::make('first_name'),
            'email' => TextEntry::make('email'),
            'is_active' => TextEntry::make('is_active'),
            'updated_at' => TextEntry::make('updated_at'),
        ];
    }
}
