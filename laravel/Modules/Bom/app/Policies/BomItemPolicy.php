<?php

declare(strict_types=1);

namespace Modules\Bom\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Bom\Models\BomItem;

final class BomItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BomItem');
    }

    public function view(AuthUser $authUser, BomItem $item): bool
    {
        return $authUser->can('View:BomItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BomItem');
    }

    public function update(AuthUser $authUser, BomItem $item): bool
    {
        return $authUser->can('Update:BomItem');
    }

    public function delete(AuthUser $authUser, BomItem $item): bool
    {
        return $authUser->can('Delete:BomItem');
    }
}

