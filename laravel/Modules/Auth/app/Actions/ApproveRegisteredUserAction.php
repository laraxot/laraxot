<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use Modules\User\Models\User;
use Modules\User\Models\Role;
use Spatie\QueueableAction\QueueableAction;

final class ApproveRegisteredUserAction
{
    use QueueableAction;

    /**
     * @param  array<int, string>  $roles
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
