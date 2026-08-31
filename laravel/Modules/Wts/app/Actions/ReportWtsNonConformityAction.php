<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Mail\WtsNonConformityReportedMail;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsNonConformityReport;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

final class ReportWtsNonConformityAction
{
    public function execute(WtsInboundLot $lot, string $note, int|string|null $reportedByUserId): WtsNonConformityReport
    {
        $normalizedNote = trim($note);
        if ($normalizedNote === '') {
            throw new InvalidArgumentException('La nota di non conformita e obbligatoria.');
        }

        $lot->loadMissing('inboundRecord');
        $record = $lot->inboundRecord;

        /** @var WtsNonConformityReport $report */
        $report = WtsNonConformityReport::query()->create([
            'wts_inbound_lot_id' => $lot->id,
            'wts_inbound_record_id' => $record?->id,
            'form_number' => $record?->form_number,
            'note' => $normalizedNote,
            'recipient_email' => $this->recipientEmail(),
            'reported_by_user_id' => $reportedByUserId,
            'reported_at' => now(),
        ]);

        $report->load(['lot', 'inboundRecord']);

        if ($report->recipient_email !== null && $report->recipient_email !== '') {
            Mail::to($report->recipient_email)->send(new WtsNonConformityReportedMail($report));
            $report->forceFill([
                'email_sent_at' => now(),
            ])->save();
        }

        return $report->refresh()->load(['lot', 'inboundRecord']);
    }

    private function recipientEmail(): string
    {
        $configuredValue = data_get(config('wts.operational_parameters.non_conformity_recipient'), 'value');

        return trim(is_scalar($configuredValue) ? (string) $configuredValue : '') ?: 'qualita@wts.example';
    }
}
