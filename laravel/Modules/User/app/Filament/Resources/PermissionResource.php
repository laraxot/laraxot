<?php

/**
 * @see https://github.com/Althinect/filament-spatie-roles-permissions/tree/2.x
 * @see https://github.com/phpsa/filament-authentication/blob/main/src/resources/PermissionResource.php
 */

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Override;
>>>>>>> f548be94 (.)
=======
use Override;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Modules\User\Filament\Resources\PermissionResource\Pages\CreatePermission;
use Modules\User\Filament\Resources\PermissionResource\Pages\EditPermission;
use Modules\User\Filament\Resources\PermissionResource\Pages\ListPermissions;
use Modules\User\Models\Permission;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\XotBaseResource;

class PermissionResource extends XotBaseResource
{
<<<<<<< HEAD
<<<<<<< HEAD
    protected static ?string $model = Permission::class;

    #[\Override]
=======
=======
>>>>>>> 87273113 (.)
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-lock-closed';

    protected static null|string $model = Permission::class;

    #[Override]
>>>>>>> f548be94 (.)
    public static function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')->required()->maxLength(255),
            'guard_name' => TextInput::make('guard_name')->required()->maxLength(255),
            'active' => Toggle::make('active')->required(),
        ];
    }

<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    protected static ?string $model = Permission::class;

    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public static function getRelations(): array
    {
        return [];
    }

<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
