<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Customer\Support\CustomerSimilarity;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Domain\DTO\QuotationCustomerDraft;

/**
 * Resolves the target customer for a voice quotation: reuses an existing customer
 * matched by VAT number (preferred), exact legal name, or a very similar name
 * (typo-tolerant), otherwise creates a new one. Avoids duplicating anagraphics.
 */
final class ResolveOrCreateCustomerFromDraftAction
{
    public function execute(QuotationCustomerDraft $draft): int
    {
        $vat = $draft->vatNumber !== null ? trim($draft->vatNumber) : '';
        if ($vat !== '') {
            $byVat = Customer::query()->where('vat_number', $vat)->orderBy('id')->first();
            if ($byVat instanceof Customer) {
                return (int) $byVat->id;
            }
        }

        $legalName = trim($draft->legalName);
        if ($legalName !== '') {
            $byName = Customer::query()
                ->whereRaw('LOWER(legal_name) = ?', [mb_strtolower($legalName)])
                ->orderBy('id')
                ->first();
            if ($byName instanceof Customer) {
                return (int) $byName->id;
            }

            $similar = CustomerSimilarity::find($legalName);
            if ($similar instanceof Customer) {
                return (int) $similar->id;
            }
        }

        /** @var Customer $customer */
        $customer = Customer::query()->create([
            ...$draft->toCustomerAttributes(),
            'is_active' => true,
        ]);

        return (int) $customer->id;
    }
}
