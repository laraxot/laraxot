<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Intervention\Models\Intervention;

/**
 * Generates the single-intervention report ("rapportino") PDF, embedding the
 * work photos and the customer's signature as inline data URIs so the file is
 * fully self-contained and shareable.
 */
final class GenerateInterventionReportPdfAction
{
    /**
     * @return array{content: string, mime: string, filename: string}
     */
    public function execute(Intervention $intervention): array
    {
        $intervention->loadMissing([
            'workOrder.customer',
            'customer',
            'performedBy',
            'assignedUsers',
            'materialLines',
            'media',
            'signatures.media',
        ]);

        $operator = $intervention->assignedUsers->pluck('name')->filter()->implode(', ');
        if ($operator === '') {
            $operator = $intervention->performedBy->name ?? '—';
        }

        $content = Pdf::loadView('interventions::reports.intervention-report', [
            'intervention' => $intervention,
            'customer' => $this->resolveCustomerName($intervention),
            'operator' => $operator,
            'materialLines' => $intervention->materialLines,
            'photos' => $this->photoDataUris($intervention),
            'signature' => $this->signatureDataUri($intervention),
        ])
            ->setPaper('a4', 'portrait')
            ->output();

        return [
            'content' => $content,
            'mime' => 'application/pdf',
            'filename' => sprintf('rapportino_intervento_%d.pdf', $intervention->id),
        ];
    }

    private function resolveCustomerName(Intervention $intervention): string
    {
        // @phpstan-ignore-next-line nullsafe.neverNull — the customer belongsTo is nullable at runtime
        return $intervention->workOrder?->customer?->legal_name ?? $intervention->customer?->legal_name ?? '—';
    }

    /**
     * @return list<string>
     */
    private function photoDataUris(Intervention $intervention): array
    {
        $uris = [];
        foreach ($intervention->media->where('kind', 'photo') as $media) {
            $uri = $this->mediaDataUri($media);
            if ($uri !== null) {
                $uris[] = $uri;
            }
        }

        return $uris;
    }

    private function signatureDataUri(Intervention $intervention): ?string
    {
        $media = $intervention->signatures->sortByDesc('id')->first()?->media;

        return $media !== null ? $this->mediaDataUri($media) : null;
    }

    private function mediaDataUri(Media $media): ?string
    {
        $disk = Storage::disk($media->disk);
        if (! $disk->exists($media->path)) {
            return null;
        }

        // Signatures are stored without a mime type but are always PNG.
        $mime = $media->mime_type ?? 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode((string) $disk->get($media->path));
    }
}
