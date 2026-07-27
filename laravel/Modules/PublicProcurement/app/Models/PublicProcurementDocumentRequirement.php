<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\PublicProcurement\Database\Factories\PublicProcurementDocumentRequirementFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static PublicProcurementDocumentRequirementFactory factory($count = null, $state = [])
 */
final class PublicProcurementDocumentRequirement extends XotBaseModel
{
    /** @phpstan-use HasXotFactory<PublicProcurementDocumentRequirementFactory> */
    use HasXotFactory;

    use SoftDeletes;

    protected $table = 'public_procurement_document_requirements';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'due_on' => 'date',
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }

    /** @return BelongsTo<PublicProcurementTenderOpportunity, $this> */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementTenderOpportunity::class, 'tender_opportunity_id');
    }

    /** @return BelongsTo<ComplianceDocument, $this> */
    public function complianceDocument(): BelongsTo
    {
        return $this->belongsTo(ComplianceDocument::class, 'compliance_document_id');
    }
}
