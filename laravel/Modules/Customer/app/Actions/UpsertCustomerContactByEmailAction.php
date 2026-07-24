<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Contact;
use Modules\Customer\Models\ContactRole;
use Spatie\QueueableAction\QueueableAction;

final class UpsertCustomerContactByEmailAction
{
    use QueueableAction;

    public function execute(
        int $customerId,
        string $fullName,
        string $email,
        ?string $phone = null,
        ?string $position = null,
        ?string $notes = null,
        ?int $customerSiteId = null,
    ): Contact {
        $contactRoleId = null;

        if ($position !== null && trim($position) !== '') {
            $role = ContactRole::query()->firstOrCreate(['name' => trim($position)]);
            $contactRoleId = $role->id;
        }

        return Contact::query()->updateOrCreate(
            ['customer_id' => $customerId, 'email' => $email],
            [
                'customer_site_id' => $customerSiteId,
                'contact_role_id' => $contactRoleId,
                'full_name' => $fullName,
                'phone' => $phone,
                'notes' => $notes,
            ],
        );
    }
}
