<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\RoleResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\User\Actions\Shield\GetPermissionModelAction;
use Modules\User\Filament\Resources\RoleResource;
use Modules\User\Models\Role;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\User\Filament\Resources\RoleResource;
use Modules\User\Models\Role;
use Modules\User\Support\Utils;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\User\Actions\Shield\GetPermissionModelAction;
use Modules\User\Filament\Resources\RoleResource;
use Modules\User\Models\Role;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Webmozart\Assert\Assert;

class EditRole extends XotBaseEditRecord
{
<<<<<<< HEAD
<<<<<<< HEAD
    /** @var Collection<int, string> */
=======
    // //
>>>>>>> f548be94 (.)
=======
    // //
=======
    /** @var Collection<int, string> */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public Collection $permissions;

    // public Role $record;
    protected static string $resource = RoleResource::class;

    /**
     *  ---.
     */
    public function afterSave(): void
    {
        $permissionModels = collect();
        Assert::isArray($data = $this->data);
        $this->permissions->each(static function ($permission) use ($permissionModels, $data): void {
<<<<<<< HEAD
<<<<<<< HEAD
            $permissionModels->push(app(GetPermissionModelAction::class)->execute()::firstOrCreate([
=======
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
>>>>>>> f548be94 (.)
=======
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
=======
            $permissionModels->push(app(GetPermissionModelAction::class)->execute()::firstOrCreate([
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                'name' => $permission,
                'guard_name' => $data['guard_name'] ?? 'web',
            ]));
        });
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::isInstanceOf($this->record, Role::class, '['.__LINE__.']['.class_basename($this).']');
=======
        Assert::isInstanceOf($this->record, Role::class, '[' . __LINE__ . '][' . class_basename($this) . ']');
>>>>>>> f548be94 (.)
=======
        Assert::isInstanceOf($this->record, Role::class, '[' . __LINE__ . '][' . class_basename($this) . ']');
=======
        Assert::isInstanceOf($this->record, Role::class, '['.__LINE__.']['.class_basename($this).']');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $this->record->syncPermissions($permissionModels);
    }

    protected function getHeaderActions(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'view' => ViewAction::make(),
            'delete' => DeleteAction::make(),
=======
            ViewAction::make(),
            DeleteAction::make(),
>>>>>>> f548be94 (.)
=======
            ViewAction::make(),
            DeleteAction::make(),
=======
            'view' => ViewAction::make(),
            'delete' => DeleteAction::make(),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissions = collect($data)
            ->filter(
<<<<<<< HEAD
<<<<<<< HEAD
                static fn ($_permission, $key): bool => ! \in_array($key, ['name', 'guard_name', 'select_all'], false) && Str::contains($key, '_'),
            )
            ->keys();

        /** @var array<string, mixed> $result */
        $result = Arr::only($data, ['name', 'guard_name']);

        return $result;
=======
=======
>>>>>>> 87273113 (.)
                static fn($_permission, $key): bool => (
                    !\in_array($key, ['name', 'guard_name', 'select_all'], false) && Str::contains($key, '_')
                ),
            )
            ->keys();

        return Arr::only($data, ['name', 'guard_name']);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                static fn ($_permission, $key): bool => ! \in_array($key, ['name', 'guard_name', 'select_all'], false) && Str::contains($key, '_'),
            )
            ->keys();

        /** @var array<string, mixed> $result */
        $result = Arr::only($data, ['name', 'guard_name']);

        return $result;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
