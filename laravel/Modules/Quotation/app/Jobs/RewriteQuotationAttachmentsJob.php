<?php

declare(strict_types=1);

namespace Modules\Quotation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Quotation\Actions\RewriteAndAttachQuotationDocumentsAction;
use Modules\Quotation\Import\QuotationNotesRewriterInterface;
use Modules\Quotation\Models\Quotation;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Rewrites the source quotation's attachments into the newly created quotation via
 * the AI, off the web request. The AI call can take tens of seconds (and retries
 * with a stronger model), so doing it inline would time out and crash confirm.
 */
final class RewriteQuotationAttachmentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;

    public int $timeout = 240;

    public function __construct(
        public readonly int $sourceId,
        public readonly int $targetId,
        public readonly string $customerName,
        public readonly ?int $userId = null,
        public readonly ?int $placeholderMediaId = null,
    ) {}

    public function handle(): void
    {
        // Resolve lazily + guard everything: a missing binding (e.g. a queue worker
        // not restarted after deploy) or any runtime error must degrade gracefully
        // to a "failed" placeholder, never crash-loop the worker.
        try {
            $source = Quotation::query()->find($this->sourceId);
            $target = Quotation::query()->find($this->targetId);
            if ($source === null || $target === null || $this->sourceId === $this->targetId) {
                $this->finalizePlaceholder(success: false);

                return;
            }

            $this->recomputeNotes($target);

            $attached = $source->getMedia('documents')->isNotEmpty()
                ? app(RewriteAndAttachQuotationDocumentsAction::class)->execute($source, $target, $this->customerName, $this->userId)
                : 0;

            // Success = the placeholder (if any) got its real document.
            $this->finalizePlaceholder(success: $this->placeholderMediaId === null || $attached > 0);
        } catch (\Throwable $e) {
            report($e);
            $this->finalizePlaceholder(success: false);
        }
    }

    /**
     * Adapts the new quotation's inherited notes (validity dates, payment plans) to
     * its own total/date via the AI. Left as-is when the AI yields nothing.
     */
    private function recomputeNotes(Quotation $target): void
    {
        $notes = is_string($target->notes) ? $target->notes : '';
        if (trim($notes) === '') {
            return;
        }

        $rewritten = app(QuotationNotesRewriterInterface::class)->rewrite($notes, [
            'today' => now()->format('d/m/Y'),
            'subtotal' => is_numeric($target->subtotal) ? (float) $target->subtotal : null,
            'taxable_amount' => is_numeric($target->taxable_amount) ? (float) $target->taxable_amount : null,
            'tax_percent' => is_numeric($target->tax_percent) ? (float) $target->tax_percent : null,
            'tax_amount' => is_numeric($target->tax_amount) ? (float) $target->tax_amount : null,
            'total' => is_numeric($target->total) ? (float) $target->total : null,
        ]);

        if (is_string($rewritten) && trim($rewritten) !== '') {
            $target->notes = $rewritten;
            $target->save();
        }
    }

    /**
     * Called by the queue when the job times out or exhausts retries: make sure the
     * placeholder never stays stuck on "being prepared" — mark it failed.
     */
    public function failed(?\Throwable $exception): void
    {
        $this->finalizePlaceholder(success: false);
    }

    /**
     * Removes the "being prepared" placeholder row once the real attachment exists,
     * or marks it as failed so the operator knows the document wasn't generated.
     */
    private function finalizePlaceholder(bool $success): void
    {
        if ($this->placeholderMediaId === null) {
            return;
        }

        $media = Media::query()->find($this->placeholderMediaId);
        if ($media === null) {
            return;
        }

        if ($success) {
            $media->delete();

            return;
        }

        $media->name = __('quotations.voice.attachment_failed_title');
        $media->save();
    }
}
