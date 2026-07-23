<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Intervention\Models\Intervention;

/**
 * Builds the data for the public, client-facing intervention report page
 * (photos and signature as public URLs, not embedded).
 */
final class GetInterventionReportPublicDataAction
{
    /**
     * @return array{
     *   id: int,
     *   customer: string,
     *   operator: string,
     *   scheduled_at: string|null,
     *   work_minutes: int|null,
     *   outcome_code: string,
     *   outcome_label: string,
     *   notes: string,
     *   materials: list<array{description: string, quantity: string, unit: string, unit_price: string, line_total: string}>,
     *   materials_total: string,
     *   photos: list<string>,
     *   signature: string|null,
     *   total: string,
     *   total_is_payable: bool,
     *   has_signature: bool,
     *   is_paid: bool,
     *   paid_at: string|null,
     *   payments_enabled: bool,
     * }
     */
    public function execute(Intervention $intervention): array
    {
        $intervention->loadMissing([
            'workOrder.customer', 'customer', 'performedBy', 'assignedUsers', 'materialLines', 'media', 'signatures.media',
        ]);

        $operator = $intervention->assignedUsers->pluck('name')->filter()->implode(', ');
        $performer = $intervention->performedBy;
        if ($operator === '' && $performer !== null) {
            $operator = $performer->name;
        }

        $materials = [];
        $materialsTotal = 0.0;
        foreach ($intervention->materialLines as $line) {
            $lineTotal = (float) $line->line_total;
            $materialsTotal += $lineTotal;
            $materials[] = [
                'description' => (string) $line->description,
                'quantity' => $this->formatQuantity((string) $line->quantity),
                'unit' => (string) ($line->unit ?? 'pz'),
                'unit_price' => number_format((float) $line->unit_price, 2, ',', '.'),
                'line_total' => number_format($lineTotal, 2, ',', '.'),
            ];
        }

        $outcome = (string) $intervention->outcome_code;

        return [
            'id' => (int) $intervention->id,
            'customer' => $this->resolveCustomerName($intervention),
            'operator' => $operator,
            'scheduled_at' => $this->formatScheduledAt($intervention),
            'work_minutes' => $intervention->work_minutes,
            'outcome_code' => $outcome,
            'outcome_label' => match ($outcome) {
                'completed' => 'Concluso',
                'suspended' => 'Sospeso',
                'quotation' => 'Preventivo',
                default => $outcome !== '' ? $outcome : '—',
            },
            'notes' => (string) ($intervention->notes ?? ''),
            'materials' => $materials,
            'materials_total' => number_format($materialsTotal, 2, ',', '.'),
            'photos' => $this->photoUrls($intervention),
            'signature' => $this->signatureUrl($intervention),
            'total' => number_format((float) $intervention->total_amount, 2, ',', '.'),
            'total_is_payable' => (float) $intervention->total_amount > 0,
            'has_signature' => $intervention->signatures->isNotEmpty(),
            'is_paid' => $intervention->report_paid_at !== null,
            'paid_at' => $this->formatPaidAt($intervention),
            'payments_enabled' => filled(config('cashier.secret')),
        ];
    }

    private function formatPaidAt(Intervention $intervention): ?string
    {
        $paidAt = $intervention->report_paid_at;
        if ($paidAt === null) {
            return null;
        }

        return CarbonImmutable::parse($paidAt)->format('d/m/Y');
    }

    private function formatScheduledAt(Intervention $intervention): ?string
    {
        $scheduledAt = $intervention->scheduled_at;
        if ($scheduledAt === null) {
            return null;
        }

        return CarbonImmutable::parse($scheduledAt)->format('d/m/Y H:i');
    }

    /**
     * Formats a decimal(2) quantity string trimming trailing zeros:
     * "2.50" → "2,5", "3.00" → "3", "1.25" → "1,25".
     */
    private function formatQuantity(string $quantity): string
    {
        return str_replace('.', ',', rtrim(rtrim($quantity, '0'), '.'));
    }

    private function resolveCustomerName(Intervention $intervention): string
    {
        // @phpstan-ignore-next-line nullsafe.neverNull — the customer belongsTo is nullable at runtime
        return $intervention->workOrder?->customer?->legal_name ?? $intervention->customer?->legal_name ?? '—';
    }

    /**
     * @return list<string>
     */
    private function photoUrls(Intervention $intervention): array
    {
        $urls = [];
        foreach ($intervention->media->where('kind', 'photo') as $media) {
            $url = $this->mediaUrl($media);
            if ($url !== null) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    private function signatureUrl(Intervention $intervention): ?string
    {
        $media = $intervention->signatures->sortByDesc('id')->first()?->media;

        return $media !== null ? $this->mediaUrl($media) : null;
    }

    private function mediaUrl(Media $media): ?string
    {
        $disk = Storage::disk($media->disk);
        if (! $disk->exists($media->path)) {
            return null;
        }

        return $disk->url($media->path);
    }
}
