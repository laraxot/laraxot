<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Modules\Document\Models\Media;
use Modules\Quotation\Import\AiDocumentRewriter;
use Modules\Quotation\Models\Quotation;

/**
 * Copies the source quotation's document attachments to the target quotation,
 * having the AI rewrite each one to reflect the NEW quotation (customer, line
 * items, totals) while keeping the original layout. The AI sees the real document
 * content, so it copes with split runs and numeric value cells that plain text
 * substitution cannot. A document that cannot be rewritten (e.g. PDF, or the AI
 * produced nothing usable) is skipped rather than attached wrong.
 */
final class RewriteAndAttachQuotationDocumentsAction
{
    public function __construct(
        private readonly AiDocumentRewriter $rewriter,
        private readonly AddQuotationAttachmentAction $attach,
    ) {}

    public function execute(Quotation $source, Quotation $target, string $newCustomerName = '', ?int $userId = null): int
    {
        $targetKey = $target->getKey();
        $targetId = is_numeric($targetKey) ? (int) $targetKey : 0;
        $quotationData = $this->quotationData($target, $newCustomerName, $source);

        $attached = 0;
        /** @var Media $media */
        foreach ($source->media()->where('kind', 'document')->get() as $media) {
            $sourceDisk = (string) $media->disk;
            $sourcePath = (string) $media->path;
            if ($sourcePath === '' || ! Storage::disk($sourceDisk)->exists($sourcePath)) {
                continue;
            }

            $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
            $destination = 'quotation-attachments/'.$targetId.'/'.Str::random(20).($extension !== '' ? '.'.$extension : '');
            Storage::disk('local')->makeDirectory(dirname($destination));

            $rewritten = $this->rewriter->rewrite(
                Storage::disk($sourceDisk)->path($sourcePath),
                $extension,
                $quotationData,
                Storage::disk('local')->path($destination),
            );

            if (! $rewritten) {
                Storage::disk('local')->delete($destination);

                continue;
            }

            $title = is_string($media->title) && $media->title !== '' ? $media->title : basename($sourcePath);
            $this->attach->execute($target, $destination, 'local', $userId, $title);
            $attached++;
        }

        return $attached;
    }

    /**
     * The new quotation as a compact payload for the AI rewriter.
     *
     * @return array<string, mixed>
     */
    private function quotationData(Quotation $target, string $newCustomerName, Quotation $source): array
    {
        $customerName = trim($newCustomerName);
        if ($customerName === '') {
            $legal = Customer::query()->whereKey($target->customer_id)->value('legal_name');
            $customerName = is_string($legal) ? $legal : '';
        }

        $oldLegal = Customer::query()->whereKey($source->customer_id)->value('legal_name');

        $lines = $target->items()->orderBy('sort_order')->orderBy('id')->get()->map(static fn (mixed $item): array => [
            'description' => (string) $item->description,
            'quantity' => (float) $item->quantity,
            'unit_price' => (float) $item->unit_price,
            'line_total' => (float) $item->line_total,
        ])->all();

        return [
            // So the AI can recompute validity dates ("valid 15 days" -> today+15).
            'today' => now()->format('d/m/Y'),
            'old_customer_name' => is_string($oldLegal) ? $oldLegal : '',
            'customer_name' => $customerName,
            'title' => $target->title,
            'notes' => $target->notes,
            'tax_percent' => is_numeric($target->tax_percent) ? (float) $target->tax_percent : null,
            'subtotal' => is_numeric($target->subtotal) ? (float) $target->subtotal : null,
            'taxable_amount' => is_numeric($target->taxable_amount) ? (float) $target->taxable_amount : null,
            'tax_amount' => is_numeric($target->tax_amount) ? (float) $target->tax_amount : null,
            'total' => is_numeric($target->total) ? (float) $target->total : null,
            'lines' => $lines,
        ];
    }
}
