<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Quotation\DTO\SiteSurveyAttachment;
use Modules\Quotation\Models\SiteSurvey;
use Spatie\QueueableAction\QueueableAction;

/**
 * Registra un sopralluogo e i suoi allegati (foto, video, note vocali).
 *
 * Gli allegati sono la sostanza del sopralluogo: senza, resta una riga di
 * calendario. Per questo vengono salvati nella stessa transazione del rilievo.
 */
final class RecordSiteSurveyAction
{
    use QueueableAction;

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
                $collection = match ($attachment->kind) {
                    'photo' => 'photos',
                    'video' => 'videos',
                    'audio' => 'voice_notes',
                    default => 'documents',
                };

                $title = $attachment->title !== null ? trim($attachment->title) : '';
                $name = $title !== '' ? $title : pathinfo($attachment->path, PATHINFO_FILENAME);

                $adder = $survey->addMediaFromDisk($attachment->path, $attachment->disk)->usingName($name);

                if ($attachment->meta !== null) {
                    $adder = $adder->withCustomProperties($attachment->meta);
                }

                $adder->toMediaCollection($collection);
            }

            return $survey->refresh();
        });
    }
}
