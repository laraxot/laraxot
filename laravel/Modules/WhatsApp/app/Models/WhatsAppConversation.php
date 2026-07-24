<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;

/**
 * @property int $id
 * @property string $phone_e164
 * @property string|null $wa_id
 * @property string|null $display_name
 * @property int|null $identity_id
 * @property int|null $customer_id
 * @property int|null $customer_site_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property string $status
 * @property int|null $active_proposal_id
 * @property int|null $approved_up_to_message_id
 * @property \Illuminate\Support\Carbon|null $last_message_at
 * @property array<string, mixed>|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
final class WhatsAppConversation extends Model
{
    protected $table = 'whatsapp_conversations';

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

    /** @return BelongsTo<WhatsAppContactIdentity, $this> */
    public function identity(): BelongsTo
    {
        return $this->belongsTo(WhatsAppContactIdentity::class, 'identity_id');
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

    /** @return HasMany<WhatsAppMessage, $this> */
    public function messages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class, 'conversation_id');
    }
}
