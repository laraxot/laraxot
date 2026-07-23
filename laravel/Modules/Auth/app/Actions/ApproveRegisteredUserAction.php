<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use App\Models\User;
use Spatie\Permission\Models\Role;

final class ApproveRegisteredUserAction
{
    /**
     * @param array<int, string> $roles
     */
    public function execute(User $user, User $approver, array $roles = ['operator']): void
    {
        $user->forceFill([
            'approved_at' => now(),
            'approved_by_user_id' => $approver->id,
        ])->save();

        $roleNames = $roles === [] ? ['operator'] : $roles;
        foreach ($roleNames as $roleName) {
            Role::findOrCreate($roleName);
        }

        $user->syncRoles($roleNames);
    }
}
