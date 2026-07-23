<?php

declare(strict_types=1);

namespace Modules\Compliance\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Compliance\Models\ComplianceDocument;

final class ComplianceDocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ComplianceDocument');
    }

    public function view(AuthUser $authUser, ComplianceDocument $complianceDocument): bool
    {
        return $authUser->can('View:ComplianceDocument');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ComplianceDocument');
    }

    public function update(AuthUser $authUser, ComplianceDocument $complianceDocument): bool
    {
        return $authUser->can('Update:ComplianceDocument');
    }

    public function delete(AuthUser $authUser, ComplianceDocument $complianceDocument): bool
    {
        return $authUser->can('Delete:ComplianceDocument');
    }
}
