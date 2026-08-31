<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Modules\AiAssistant\Import\AiQuotationDocumentRewriter;
use Modules\AiAssistant\Import\AiQuotationNotesRewriter;
use Modules\Quotation\Actions\RewriteAndAttachQuotationDocumentsAction;
use Modules\Quotation\Import\QuotationDocumentRewriterInterface;
use Modules\Quotation\Import\QuotationNotesRewriterInterface;

// Guards the exact production failure: the queue worker could not resolve
// QuotationDocumentRewriterInterface. If a binding is missing this test fails.
it('resolves the AI rewriter bindings the queue job depends on', function (): void {
expect(app(QuotationDocumentRewriterInterface::class))->not->toBeNull();
expect(app(QuotationNotesRewriterInterface::class))->not->toBeNull();

    // The whole collaborator chain resolved by the job builds without error.
expect(app(RewriteAndAttachQuotationDocumentsAction::class))->not->toBeNull();
});
