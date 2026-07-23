<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Quotation\Jobs\RewriteQuotationAttachmentsJob;
use Modules\Quotation\Models\Quotation;

/**
 * Queues the AI rewrite of a quotation's attachment (and notes recompute) from its
 * source quotation. Used both right after a voice quotation is created and by the
 * "re-prepare attachment" button. Adds a "being prepared" placeholder row when
 * there's a document to rewrite. Returns false when there's nothing to do.
 */
final readonly class QueueQuotationAttachmentRewriteAction
{
    public function __construct(
        private AddQuotationAttachmentAction $attach,
    ) {}

    public function execute(Quotation $target, string $customerName = '', ?int $userId = null): bool
    {
        $sourceId = is_numeric($target->source_quotation_id) ? (int) $target->source_quotation_id : 0;
        if ($sourceId <= 0) {
            return false;
        }

        $source = Quotation::query()->find($sourceId);
        if ($source === null || (int) $source->id === (int) $target->id) {
            return false;
        }

        $hasDocuments = $source->media()->where('kind', 'document')->exists();
        $hasNotes = trim((string) $target->notes) !== '';
        if (! $hasDocuments && ! $hasNotes) {
            return false;
        }

        $placeholderId = $hasDocuments ? $this->createPlaceholder($target, $userId) : null;

        RewriteQuotationAttachmentsJob::dispatch(
            (int) $source->id,
            (int) $target->id,
            $customerName,
            $userId,
            $placeholderId,
        );

        return true;
    }

    private function createPlaceholder(Quotation $target, ?int $userId): int
    {
        $this->removeStalePlaceholders($target);

        $path = 'quotation-attachments/'.$target->id.'/'.Str::random(20).'.txt';
        Storage::disk('local')->put($path, __('quotations.voice.attachment_preparing_body'));

        $media = $this->attach->execute(
            $target,
            $path,
            'local',
            $userId,
            __('quotations.voice.attachment_preparing_title'),
        );

        $id = $media->getKey();

        return is_numeric($id) ? (int) $id : 0;
    }

    /**
     * Removes leftover placeholder rows (still "being prepared" or already marked
     * "not generated") before a retry, so they don't pile up in the table.
     */
    private function removeStalePlaceholders(Quotation $target): void
    {
        $titles = [
            __('quotations.voice.attachment_preparing_title'),
            __('quotations.voice.attachment_failed_title'),
        ];

        foreach ($target->media()->where('kind', 'document')->whereIn('title', $titles)->get() as $media) {
            Storage::disk((string) $media->disk)->delete((string) $media->path);
            $media->delete();
        }
    }
}
