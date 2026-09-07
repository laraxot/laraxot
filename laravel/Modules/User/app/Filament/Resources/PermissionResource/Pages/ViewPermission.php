<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\PermissionResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Components\Component;
=======
use Filament\Support\Components\Component;
use Override;
use Filament\Infolists\Components\TextEntry;
>>>>>>> f548be94 (.)
=======
use Filament\Support\Components\Component;
use Override;
use Filament\Infolists\Components\TextEntry;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\PermissionResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewPermission extends XotBaseViewRecord
{
    protected static string $resource = PermissionResource::class;
<<<<<<< HEAD

    /**
<<<<<<< HEAD
     * @return array<string, Component>
     */
    #[\Override]
    public function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'guard_name' => TextEntry::make('guard_name'),
            'active' => TextEntry::make('active')
                ->formatStateUsing(fn ($state): string => $state ? __('user::common.yes') : __('user::common.no')),
            'created_at' => TextEntry::make('created_at')
=======
     * @return array<int, Component>
     */
    #[Override]
    public function getInfolistSchema(): array
    {
        return [
            TextEntry::make('name')->label(__('user::permission.fields.name.label')),
            TextEntry::make('guard_name')->label(__('user::permission.fields.guard_name.label')),
            TextEntry::make('active')
                ->label(__('user::permission.fields.active.label'))
                ->formatStateUsing(fn ($state): string => $state ? __('user::common.yes') : __('user::common.no')),
            TextEntry::make('created_at')
                ->label(__('user::permission.fields.created_at.label'))
>>>>>>> f548be94 (.)
                ->dateTime(),
        ];
    }
=======
>>>>>>> 2024e2e7 (.)
}
