<?php

declare(strict_types=1);

namespace Modules\UI\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\UI\Datas\UserData;
<<<<<<< HEAD
use Modules\User\Models\Profile;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Contracts\UserContract;
=======
use Modules\User\Models\User;
>>>>>>> f5ffd8b4 (.)
use Spatie\Permission\Contracts\Permission;
use Spatie\QueueableAction\QueueableAction;

class GetUserDataAction
{
    use QueueableAction;

    public function execute(): ?UserData
    {
        $user = Auth::user();

        if (! $user instanceof UserContract) {
            return null;
        }

<<<<<<< HEAD
        $avatar = null;
        $profile = $user->relationLoaded('profile') ? $user->profile : null;
        if ($profile instanceof Profile) {
            $avatarUrl = $profile->getAvatarUrl();
            $avatar = $avatarUrl !== '' ? $avatarUrl : null;
        }

        if ($avatar === null) {
            $profilePhotoPath = $user->getAttribute('profile_photo_path');
            if (is_string($profilePhotoPath) && $profilePhotoPath !== '') {
                $avatar = $profilePhotoPath;
=======
        // PHPStan L10: tenancy() è helper function, ma PHPStan non la riconosce
        // Rimuoviamo questa logica se non è necessaria per GetUserDataAction
        // Se necessario, usare Filament::getTenant() invece

        // Get avatar from profile_photo_path or profile relation
        $avatarValue = null;
        if (isset($user->profile_photo_path) && is_string($user->profile_photo_path)) {
            $avatarValue = $user->profile_photo_path;
        } elseif ($user->relationLoaded('profile') && null !== $user->profile) {
            $profile = $user->profile;
            if (is_object($profile) && method_exists($profile, 'getAvatarUrl')) {
                $avatarValue = $profile->getAvatarUrl();
            } elseif (is_object($profile) && isset($profile->avatar) && is_string($profile->avatar)) {
                $avatarValue = $profile->avatar;
>>>>>>> f5ffd8b4 (.)
            }
        }

        // PHPStan L10: getRoleNames() restituisce Collection, ma PHPStan non lo riconosce dal trait
        /** @var Collection<int, string> $roleNames */
        $roleNames = $user->getRoleNames();
        $firstRole = $roleNames->isNotEmpty() ? $roleNames->first() : null;
        $roleValue = is_string($firstRole) ? $firstRole : null;

        // Get settings - could be in profile or extra attributes
        /** @var array<string, mixed> $settingsArray */
        $settingsArray = [];
        if ($user->relationLoaded('profile') && null !== $user->profile) {
            $profile = $user->profile;
            if (is_object($profile) && isset($profile->extra)) {
                $extra = $profile->extra;
                if (is_array($extra)) {
                    /** @var array<string, mixed> $typedExtra */
                    $typedExtra = $extra;
                    $settingsArray = $typedExtra;
                }
            }
        }

        // PHPStan L10: getAllPermissions() restituisce Collection, ma PHPStan non lo riconosce dal trait
        // method_exists() è sempre true perché User ha HasPermissions trait
        /** @var Collection<int, Permission> $allPermissions */
        $allPermissions = $user->getAllPermissions();
        /** @var array<int, string> $permissions */
        $permissions = $allPermissions->pluck('name')->toArray();

<<<<<<< HEAD
        // getAttribute, non property_exists: name ed email sono attributi magici Eloquent, per cui
        // property_exists e' sempre false e UserData usciva con nome ed email vuoti.
        $userName = $user->getAttribute('name');
        $userEmail = $user->getAttribute('email');

        return new UserData(
            id: SafeIntCastAction::cast($user->id),
            name: is_string($userName) ? $userName : '',
            email: is_string($userEmail) ? $userEmail : '',
            avatar: is_string($avatar) ? $avatar : null,
            role: $roleValue,
            permissions: $permissions,
=======
        return new UserData(
            id: (int) $user->id,
            name: (string) ($user->name ?? ''),
            email: (string) ($user->email ?? ''),
            avatar: null !== $avatarValue ? (string) $avatarValue : null,
            role: null !== $roleValue ? (string) $roleValue : null,
            permissions: $permissions ?? [],
>>>>>>> f5ffd8b4 (.)
            settings: $settingsArray,
        );
    }
}
