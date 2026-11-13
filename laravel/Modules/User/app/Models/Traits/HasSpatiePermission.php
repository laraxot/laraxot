<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

use Modules\User\Models\Permission;
<<<<<<< .merge_file_6yJoNa
use Spatie\Permission\Traits\HasPermissions;
=======
>>>>>>> .merge_file_wXlS0r
use Spatie\Permission\Traits\HasRoles;

trait HasSpatiePermission
{
    use HasRoles;

    public function hasPermissionToOrCreate(
        string $permission,
        ?string $guardName = null,
    ): bool {
        $guardName ??= $this->getDefaultGuardName();

        Permission::findOrCreate($permission, $guardName);

        return $this->hasPermissionTo($permission, $guardName);
    }
}
