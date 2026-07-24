<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Customer\Models\Customer;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\DTO\QuotationDraft;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Spatie\QueueableAction\QueueableAction;

/**
 * Turns an existing quotation into an editable draft to seed the "create a
 * similar quotation" voice flow: same header and line items (with their catalog
 * links preserved), same source customer — which the operator then overrides by
 * voice/online lookup before confirming a brand-new quotation.
 */
final class DuplicateQuotationAsDraftAction
{
    use QueueableAction;

    public function execute(Quotation $source): QuotationDraft
    {
        return new QuotationDraft(
            customer: $this->customerDraft($source),
            title: is_string($source->title) && $source->title !== '' ? $source->title : null,
            notes: is_string($source->notes) && $source->notes !== '' ? $source->notes : null,
            items: $this->items($source),
            taxPercent: $source->tax_percent !== null ? (float) $source->tax_percent : null,
            technicalParams: $this->technicalParams($source),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function technicalParams(Quotation $source): array
    {
        $params = $source->technical_params;
        if (! is_array($params)) {
            return [];
        }

        $result = [];
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
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
            addressLine1: $this->nullableString($customer->getAttribute('address_line1')),
            city: $customer->city,
            postalCode: $this->nullableString($customer->getAttribute('postal_code')),
            province: $this->nullableString($customer->getAttribute('province')),
        );
    }

    private function nullableString(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
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
