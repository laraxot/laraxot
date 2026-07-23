<?php

declare(strict_types=1);

namespace Modules\Quotation\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Quotation\Models\QuotationTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationTemplatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuotationTemplate');
    }

    public function view(AuthUser $authUser, QuotationTemplate $quotationTemplate): bool
    {
        return $authUser->can('View:QuotationTemplate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuotationTemplate');
    }

    public function update(AuthUser $authUser, QuotationTemplate $quotationTemplate): bool
    {
        return $authUser->can('Update:QuotationTemplate');
    }

    public function delete(AuthUser $authUser, QuotationTemplate $quotationTemplate): bool
    {
        return $authUser->can('Delete:QuotationTemplate');
    }

    public function restore(AuthUser $authUser, QuotationTemplate $quotationTemplate): bool
    {
        return $authUser->can('Restore:QuotationTemplate');
    }

    public function forceDelete(AuthUser $authUser, QuotationTemplate $quotationTemplate): bool
    {
        return $authUser->can('ForceDelete:QuotationTemplate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuotationTemplate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuotationTemplate');
    }

    public function replicate(AuthUser $authUser, QuotationTemplate $quotationTemplate): bool
    {
        return $authUser->can('Replicate:QuotationTemplate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuotationTemplate');
    }

}