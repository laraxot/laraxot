<?php

declare(strict_types=1);

namespace Modules\Intervention\Queries;

use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Data\ExtraMaterialsRecap;

/**
 * How much material left the van beyond what the quotation covered, in a given
 * period, and what was decided about it.
 *
 * This is not an estimate: `extra_quantity` and `extra_amount` are computed by
 * DetectExtraQuotationMaterialsAction against the actual quotation, one line at
 * a time. It is the one figure about the report that survives somebody checking
 * it, which is exactly why it is worth showing.
 *
 * Note on attribution: material lines can also be typed straight into the
 * intervention form, so this counts extras from every source and not only from
 * dictated reports. Calling it "earned thanks to the voice report" would be a
 * claim the schema cannot back.
 */
final class GetExtraMaterialsRecapQuery
{
    public function execute(DateTimeInterface $from, DateTimeInterface $to): ExtraMaterialsRecap
    {
        $rows = DB::table('intervention_material_lines as lines')
            ->join('interventions as i', 'i.id', '=', 'lines.intervention_id')
            ->whereNull('i.deleted_at')
            ->where('lines.extra_quantity', '>', 0)
            ->whereBetween(DB::raw('COALESCE(i.started_at, i.scheduled_at, i.created_at)'), [$from, $to])
            ->groupBy('lines.extra_status_code')
            ->select('lines.extra_status_code')
            ->selectRaw('SUM(lines.extra_amount) as amount')
            ->selectRaw('COUNT(*) as lines_count')
            ->get();

        $byStatus = ['to_bill' => 0.0, 'pending' => 0.0, 'waived' => 0.0];
        $lineCount = 0;

        foreach ($rows as $row) {
            $status = (string) ($row->extra_status_code ?? 'pending');
            $amount = is_numeric($row->amount) ? (float) $row->amount : 0.0;
            $lineCount += (int) $row->lines_count;

            // An unknown status is money nobody has decided on yet, so it lands
            // in `pending` rather than being dropped from the total.
            $bucket = array_key_exists($status, $byStatus) ? $status : 'pending';
            $byStatus[$bucket] += $amount;
        }

        return new ExtraMaterialsRecap(
            toBill: round($byStatus['to_bill'], 2),
            pending: round($byStatus['pending'], 2),
            waived: round($byStatus['waived'], 2),
            lineCount: $lineCount,
            issueCount: $this->countIssues($from, $to),
        );
    }

    /**
     * Faults noticed on site and written down: the raw material of the next
     * quotation, and the other thing the report produces that nothing else does.
     */
    private function countIssues(DateTimeInterface $from, DateTimeInterface $to): int
    {
        return DB::table('issue_reports as r')
            ->join('interventions as i', 'i.id', '=', 'r.intervention_id')
            ->whereNull('i.deleted_at')
            ->whereBetween(DB::raw('COALESCE(i.started_at, i.scheduled_at, i.created_at)'), [$from, $to])
            ->count();
    }
}
