<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Document\Models\Media;
use Modules\Quotation\DTO\SiteSurveyAttachment;
use Modules\Quotation\Models\SiteSurvey;

/**
 * Registra un sopralluogo e i suoi allegati (foto, video, note vocali).
 *
 * Gli allegati sono la sostanza del sopralluogo: senza, resta una riga di
 * calendario. Per questo vengono salvati nella stessa transazione del rilievo.
 */
final class RecordSiteSurveyAction
{
    /**
     * @param  array<int, SiteSurveyAttachment>  $attachments
     */
    public function execute(SiteSurvey $survey, array $attachments = [], ?string $notes = null): SiteSurvey
    {
        return DB::transaction(function () use ($survey, $attachments, $notes): SiteSurvey {
            $survey->forceFill([
                'status_code' => 'done',
                'performed_at' => $survey->performed_at ?? now(),
                'notes' => $notes ?? $survey->notes,
            ])->save();

            foreach ($attachments as $attachment) {
                Media::query()->create([
                    'mediable_type' => $survey->getMorphClass(),
                    'mediable_id' => $survey->getKey(),
                    'uploaded_by_user_id' => $survey->performed_by_user_id,
                    'kind' => $attachment->kind,
                    'title' => $attachment->title,
                    'disk' => $attachment->disk,
                    'path' => $attachment->path,
                    'meta' => $attachment->meta,
                ]);
            }

            return $survey->refresh();
        });
    }
}
