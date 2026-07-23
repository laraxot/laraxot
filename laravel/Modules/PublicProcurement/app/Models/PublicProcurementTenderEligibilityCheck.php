<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class PublicProcurementTenderEligibilityCheck extends Model
{
    use SoftDeletes;

    protected $table = 'public_procurement_tender_eligibility_checks';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'required_documents' => 'array',
            'missing_documents' => 'array',
            'expired_documents' => 'array',
            'warnings' => 'array',
            'actions' => 'array',
            'evaluated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementTenderOpportunity, $this> */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementTenderOpportunity::class, 'tender_opportunity_id');
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }

    /** @return BelongsTo<User, $this> */
    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by_user_id');
    }
}
