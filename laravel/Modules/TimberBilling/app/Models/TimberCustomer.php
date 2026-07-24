<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberCustomerFactory;

/**
 * @property string|null $code
 * @property string $business_name
 * @property string|null $vat_number
 * @property string|null $tax_code
 * @property bool $is_customer
 * @property bool $is_supplier
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $province
 * @property string $country
 * @property string|null $sdi_recipient_code
 * @property string|null $sdi_admin_ref
 * @property string|null $pec
 * @property string|null $contact_person
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $fax
 * @property string|null $email
 * @property string|null $default_discount_percent
 * @property string|null $price_list_code
 * @property string|null $credit_limit
 * @property int|null $agent_id
 * @property string|null $payment_terms
 * @property string|null $iban
 * @property string|null $our_bank
 * @property string|null $carriage_code
 * @property string|null $vat_rate_code
 * @property string|null $intent_declaration_number
 * @property Carbon|null $intent_declaration_date
 * @property bool $send_documents_by_email
 * @property bool $invoice_with_withholding
 * @property string|null $document_notice
 * @property string|null $document_note
 * @property string|null $notes
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class TimberCustomer extends Model
{
    /** @use HasFactory<TimberCustomerFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'timber_customers';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_customer' => 'bool',
            'is_supplier' => 'bool',
            'send_documents_by_email' => 'bool',
            'invoice_with_withholding' => 'bool',
            'is_active' => 'bool',
            'default_discount_percent' => 'decimal:2',
            'credit_limit' => 'decimal:2',
            'intent_declaration_date' => 'date',
        ];
    }

    /** @return BelongsTo<TimberAgent, $this> */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(TimberAgent::class, 'agent_id');
    }

    /** @return MorphMany<TimberContactNote, $this> */
    public function contactNotes(): MorphMany
    {
        return $this->morphMany(TimberContactNote::class, 'noteable');
    }

    protected static function newFactory(): TimberCustomerFactory
    {
        return TimberCustomerFactory::new();
    }
}
