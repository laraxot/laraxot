<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Quotation\Import\QuotationDocumentTextExtractor;
use Modules\Quotation\Models\Quotation;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;

/**
 * Imports a quotation from an uploaded document (ODF/OOXML/PDF/CSV): reads its
 * text, lets the AI extract the customer and line items, resolves or CREATES the
 * customer, and persists a new quotation. The operator reviews it before sending.
 */
final class ImportQuotationFromFileAction
{
    use QueueableAction;

    public function __construct(
        private readonly QuotationDocumentTextExtractor $extractor,
        private readonly BuildQuotationDraftAction $build,
        private readonly ResolveOrCreateCustomerFromDraftAction $resolveCustomer,
        private readonly CreateQuotationFromDraftAction $create,
    ) {}

    public function execute(string $path, string $originalName): Quotation
    {
        $text = $this->extractor->extract($path, $originalName);
        if (trim($text) === '') {
            throw new RuntimeException(__('quotations.import.empty_document'));
        }

        $draft = $this->build->execute($text);

        if ($draft->customer === null || ! $draft->customer->hasIdentity()) {
            throw new RuntimeException(__('quotations.import.no_customer'));
        }

        $customerId = $this->resolveCustomer->execute($draft->customer);

        return $this->create->execute($draft, $customerId);
    }
}
