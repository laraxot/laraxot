<?php

declare(strict_types=1);

namespace Modules\Email\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Email\Database\Factories\EmailContactIdentityFactory;

/**
 * @property int $id
 * @property string $email
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Modules\Email\Database\Factories\EmailContactIdentityFactory factory($count = null, $state = [])
 */
final class EmailContactIdentity extends BaseModel
{
    /** @use HasFactory<EmailContactIdentityFactory> */
    use HasFactory;

    protected $table = 'email_contact_identities';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'meta' => 'array',
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

    /** @return BelongsTo<Contact, $this> */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): EmailContactIdentityFactory
    {
        return EmailContactIdentityFactory::new();
    }
}
