<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Actions;

use Carbon\CarbonImmutable;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementProgressReport;

final class GenerateProgressReportFromMeasurementsAction
{
    public function execute(
        PublicProcurementContract $contract,
        string $number,
        CarbonImmutable $periodFrom,
        CarbonImmutable $periodTo,
    ): PublicProcurementProgressReport {
        $workAmount = (float) $contract->measurements()
            ->where('status', 'approved')
            ->whereBetween('measured_on', [$periodFrom->toDateString(), $periodTo->toDateString()])
            ->sum('amount');

        $securityAmount = round($workAmount > 0 ? ((float) $contract->security_costs * min($this->progressRatio($contract), 1.0)) : 0, 2);
        $retentionAmount = round(($workAmount + $securityAmount) * ((float) $contract->retention_rate / 100), 2);
        $certificateAmount = round($workAmount + $securityAmount - $retentionAmount, 2);
        $progressPercentage = round($this->progressRatio($contract) * 100, 2);

        /** @var PublicProcurementProgressReport $report */
        $report = PublicProcurementProgressReport::query()->updateOrCreate(
            [
                'public_procurement_contract_id' => $contract->id,
                'number' => $number,
            ],
            [
                'status' => 'draft',
                'issued_on' => CarbonImmutable::now()->toDateString(),
                'period_from' => $periodFrom->toDateString(),
                'period_to' => $periodTo->toDateString(),
                'work_amount' => $workAmount,
                'security_amount' => $securityAmount,
                'retention_amount' => $retentionAmount,
                'certificate_amount' => $certificateAmount,
                'progress_percentage' => $progressPercentage,
            ],
        );

        return $report;
    }

    private function progressRatio(PublicProcurementContract $contract): float
    {
        $contractAmount = (float) $contract->contract_amount;
        if ($contractAmount <= 0.0) {
            return 0.0;
        }

        $approvedAmount = (float) $contract->measurements()
            ->where('status', 'approved')
            ->sum('amount');

        return $approvedAmount / $contractAmount;
    }
}
