<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\RoleResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Component;
use Override;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Actions\EditAction;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\RoleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewRole extends XotBaseViewRecord
{
    protected static string $resource = RoleResource::class;
<<<<<<< HEAD

    /**
<<<<<<< HEAD
     * @return array<string, Component>
     */
    #[\Override]
    protected function getInfolistSchema(): array
    {
        return [
            'role_info' => Section::make()->schema([
                'id' => TextEntry::make('id'),
                'name' => TextEntry::make('name'),
                'guard_name' => TextEntry::make('guard_name'),
                'team_id' => TextEntry::make('team_id'),
                'uuid' => TextEntry::make('uuid'),
                'created_at' => TextEntry::make('created_at'),
                'updated_at' => TextEntry::make('updated_at'),
=======
     * @return array<Component>
     */
    #[Override]
    protected function getInfolistSchema(): array
    {
        return [
            Section::make()->schema([
                TextEntry::make('id'),
                TextEntry::make('name'),
                TextEntry::make('guard_name'),
                TextEntry::make('team_id'),
                TextEntry::make('uuid'),
                TextEntry::make('created_at'),
                TextEntry::make('updated_at'),
>>>>>>> f548be94 (.)
            ]),
        ];
    }
=======
>>>>>>> 2024e2e7 (.)
}
