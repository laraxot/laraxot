<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Customer\Models\Customer;
use Modules\Quotation\Domain\DTO\DictatedQuotationItem;
use Modules\Quotation\Domain\DTO\QuotationCustomerDraft;
use Modules\Quotation\Domain\DTO\QuotationDraft;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;

/**
 * Turns an existing quotation into an editable draft to seed the "create a
 * similar quotation" voice flow: same header and line items (with their catalog
 * links preserved), same source customer — which the operator then overrides by
 * voice/online lookup before confirming a brand-new quotation.
 */
final class DuplicateQuotationAsDraftAction
{
    public function execute(Quotation $source): QuotationDraft
    {
        return new QuotationDraft(
            customer: $this->customerDraft($source),
            title: is_string($source->title) && $source->title !== '' ? $source->title : null,
            notes: is_string($source->notes) && $source->notes !== '' ? $source->notes : null,
            items: $this->items($source),
            taxPercent: $source->tax_percent !== null ? (float) $source->tax_percent : null,
            technicalParams: is_array($source->technical_params) ? $source->technical_params : [],
        );
    }

    private function customerDraft(Quotation $source): ?QuotationCustomerDraft
    {
        $customer = Customer::query()->find($source->customer_id);
        if (! $customer instanceof Customer) {
            return null;
        }

        return new QuotationCustomerDraft(
            legalName: (string) $customer->legal_name,
            vatNumber: $customer->vat_number,
            taxCode: $customer->tax_code,
            email: $customer->email,
            phone: $customer->phone,
            addressLine1: $customer->address_line1,
            city: $customer->city,
            postalCode: $customer->postal_code,
            province: $customer->province,
        );
    }

    /**
     * @return list<DictatedQuotationItem>
     */
    private function items(Quotation $source): array
    {
        $items = [];
        foreach ($source->items()->orderBy('sort_order')->orderBy('id')->get() as $item) {
            /** @var QuotationItem $item */
            $items[] = new DictatedQuotationItem(
                description: (string) $item->description,
                quantity: (float) $item->quantity,
                unitPrice: $item->unit_price !== null ? (float) $item->unit_price : null,
                matchedProductId: $item->product_id !== null ? (int) $item->product_id : null,
            );
        }

        return $items;
    }
}
