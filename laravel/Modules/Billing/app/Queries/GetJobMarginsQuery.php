<?php

declare(strict_types=1);

namespace Modules\Billing\Queries;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\CalculateJobMarginAction;
use Modules\Billing\Datas\JobMarginReport;
use Modules\Platform\Settings\SettingsRepository;

/**
 * Works out what every open job earned against what it cost.
 *
 * Where each figure comes from, since none of it is obvious:
 *  - revenue is the interventions' own totals, not invoices, because an
 *    invoice can cover several jobs and splitting it across them would be a
 *    guess;
 *  - labour is minutes worked at the company hourly cost, a single rate held
 *    in platform settings;
 *  - materials are what suppliers actually charged, read off the delivery
 *    notes attached to the job — the intervention lines carry selling prices,
 *    which would make every job look free.
 *
 * Subcontractor cost stays zero for now: supplier invoices are not attributed
 * to a job anywhere in the schema, and inventing an allocation would be worse
 * than reporting nothing.
 */
final class GetJobMarginsQuery
{
    public const HOURLY_LABOUR_COST_SETTING = 'accounting.hourly_labour_cost_cents';

    /**
     * Fully loaded cost of an hour of a qualified installer — pay, contributions
     * and the rest — used until someone enters the company's own figure. It is
     * deliberately not zero: costing labour at nothing makes every job look
     * profitable, which is a more expensive lie than an approximate rate.
     */
    public const DEFAULT_HOURLY_LABOUR_COST_CENTS = 3000;

    public function __construct(
        private readonly CalculateJobMarginAction $calculateMargin,
        private readonly SettingsRepository $settings,
    ) {}

    public function execute(): JobMarginReport
    {
        $configuredCents = $this->settings->getInt(self::HOURLY_LABOUR_COST_SETTING, 0);
        $usesDefaultRate = $configuredCents <= 0;
        $hourlyCostCents = $usesDefaultRate ? self::DEFAULT_HOURLY_LABOUR_COST_CENTS : $configuredCents;

        $labourByJob = $this->sumBy(
            DB::table('interventions')
                ->whereNull('deleted_at')
                ->whereNotNull('work_order_id')
                ->groupBy('work_order_id')
                ->select('work_order_id')
                ->selectRaw('SUM(COALESCE(work_minutes, 0)) as aggregate')
                ->get(),
        );

        $revenueByJob = $this->sumBy(
            DB::table('interventions')
                ->whereNull('deleted_at')
                ->whereNotNull('work_order_id')
                ->groupBy('work_order_id')
                ->select('work_order_id')
                ->selectRaw('SUM(COALESCE(total_amount, 0)) as aggregate')
                ->get(),
        );

        $materialsByJob = $this->sumBy(
            DB::table('delivery_note_lines')
                ->join('delivery_notes', 'delivery_notes.id', '=', 'delivery_note_lines.delivery_note_id')
                ->whereNull('delivery_notes.deleted_at')
                ->whereNotNull('delivery_notes.work_order_id')
                ->groupBy('delivery_notes.work_order_id')
                ->select('delivery_notes.work_order_id')
                ->selectRaw('SUM(COALESCE(delivery_note_lines.quantity, 0) * COALESCE(delivery_note_lines.unit_price, 0)) as aggregate')
                ->get(),
        );

        $jobs = DB::table('work_orders')
            ->whereNull('deleted_at')
            ->select(['id', 'title'])
            ->get();

        $margins = [];

        foreach ($jobs as $job) {
            $jobId = (int) $job->id;
            $minutes = $labourByJob[$jobId] ?? 0.0;

            $margins[] = $this->calculateMargin->execute(
                workOrderId: $jobId,
                workOrderTitle: is_string($job->title) ? $job->title : '',
                revenue: $revenueByJob[$jobId] ?? 0.0,
                labourCost: ($minutes / 60) * ($hourlyCostCents / 100),
                materialCost: $materialsByJob[$jobId] ?? 0.0,
                externalCost: 0.0,
            );
        }

        return new JobMarginReport(
            margins: $this->calculateMargin->worstFirst($margins),
            hourlyLabourCostCents: $hourlyCostCents,
            usesDefaultRate: $usesDefaultRate,
        );
    }

    /**
     * Le query raw tornano stdClass: normalizziamo qui invece di spargere cast
     * lungo la pagina, cosi' un cambio di driver non si porta dietro sorprese.
     *
     * @param  \Illuminate\Support\Collection<int, \stdClass>  $rows
     * @return array<int, float>
     */
    private function sumBy(Collection $rows): array
    {
        $totals = [];

        foreach ($rows as $row) {
            /** @var array<string, mixed> $values */
            $values = (array) $row;

            $workOrderId = $values['work_order_id'] ?? null;
            $aggregate = $values['aggregate'] ?? 0;

            if (! is_int($workOrderId) && ! is_string($workOrderId)) {
                continue;
            }

            $totals[(int) $workOrderId] = is_numeric($aggregate) ? (float) $aggregate : 0.0;
        }

        return $totals;
    }
}
