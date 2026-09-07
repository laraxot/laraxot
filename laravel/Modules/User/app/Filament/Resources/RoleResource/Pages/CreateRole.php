<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\RoleResource\Pages;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\User\Filament\Resources\RoleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
<<<<<<< HEAD
<<<<<<< HEAD

class CreateRole extends XotBaseCreateRecord
{
    /** @var Collection<int, string> */
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

class CreateRole extends XotBaseCreateRecord
{
    // //
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

class CreateRole extends XotBaseCreateRecord
{
    /** @var Collection<int, string> */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public Collection $permissions;

    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->permissions = collect($data)
            ->filter(
<<<<<<< HEAD
<<<<<<< HEAD
                static fn ($_permission, $key): bool => ! in_array($key, ['name', 'guard_name', 'select_all'], false) && Str::contains($key, '_'),
            )
            ->keys();

        /** @var array<string, mixed> $res */
        $res = Arr::only($data, ['name', 'guard_name', 'team_id']);
        if (! isset($res['team_id'])) {
=======
=======
>>>>>>> 87273113 (.)
                static fn($_permission, $key): bool => (
                    !in_array($key, ['name', 'guard_name', 'select_all'], false) && Str::contains($key, '_')
                ),
            )
            ->keys();

        $res = Arr::only($data, ['name', 'guard_name', 'team_id']);
        if (!isset($res['team_id'])) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                static fn ($_permission, $key): bool => ! in_array($key, ['name', 'guard_name', 'select_all'], false) && Str::contains($key, '_'),
            )
            ->keys();

        /** @var array<string, mixed> $res */
        $res = Arr::only($data, ['name', 'guard_name', 'team_id']);
        if (! isset($res['team_id'])) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $res['team_id'] = null;
        }

        return $res;
    }

    /*
     *  Modules\User\Filament\Resources\RoleResource\Pages\CreateRole::afterCreate does not exist.
     *
     * private function afterCreate(): void {
     * $permissionModels = collect();
     * $this->permissions->each(function ($permission) use ($permissionModels): void {
     * $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
     *
     * 'name' => $permission,
     * 'guard_name' => $this->data['guard_name'],
     * ]));
     * });
     *
     * $this->record->syncPermissions($permissionModels);
     * }
     */
}
