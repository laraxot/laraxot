<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_role_id
 * @property string $full_name
 * @property string|null $email
 * @property string|null $phone
 *
 * @method static \Modules\Customer\Database\Factories\ContactFactory factory($count = null, $state = [])
 */
final class Contact extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return BelongsTo<CustomerSite, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(CustomerSite::class, 'customer_site_id');
    }

    /** @return BelongsTo<ContactRole, $this> */
    public function role(): BelongsTo
    {
        return $this->belongsTo(ContactRole::class, 'contact_role_id');
    }
}
