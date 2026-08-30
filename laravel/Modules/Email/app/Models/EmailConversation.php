<?php

declare(strict_types=1);

namespace Modules\Email\Models;

use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Email\Database\Factories\EmailConversationFactory;

/**
 * @property int $id
 * @property string $email
 * @property string|null $display_name
 * @property int|null $identity_id
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property string $status
 * @property Carbon|null $last_message_at
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Modules\Email\Database\Factories\EmailConversationFactory factory($count = null, $state = [])
 */
final class EmailConversation extends BaseModel
{
    /** @use HasFactory<EmailConversationFactory> */
    use HasFactory;

    protected $table = 'email_conversations';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<EmailContactIdentity, $this> */
    public function identity(): BelongsTo
    {
        return $this->belongsTo(EmailContactIdentity::class, 'identity_id');
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

    /** @return HasMany<EmailMessage, $this> */
    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class, 'conversation_id');
    }

    protected static function newFactory(): EmailConversationFactory
    {
        return EmailConversationFactory::new();
    }
}
