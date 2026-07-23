<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Carbon\CarbonImmutable;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\IssueReport;

final class CreateIssueReportAction
{
    public function execute(Intervention $intervention, int $createdByUserId, string $body, ?CarbonImmutable $createdAt = null): IssueReport
    {
        $createdAt = $createdAt ?? CarbonImmutable::now();

        /** @var IssueReport $report */
        $report = IssueReport::query()->create([
            'intervention_id' => $intervention->id,
            'created_by_user_id' => $createdByUserId,
            'body' => $body,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        return $report;
    }
}
