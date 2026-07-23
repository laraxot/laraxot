<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;

final class EnergyBrokerPolicy
{
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EnergyBroker');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:EnergyBroker');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EnergyBroker');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('Update:EnergyBroker');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('Delete:EnergyBroker');
    }
}
