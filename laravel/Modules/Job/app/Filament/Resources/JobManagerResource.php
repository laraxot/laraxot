<?php

declare(strict_types=1);

/**
 * @see https://github.com/mooxphp/jobs/tree/main
 */

namespace Modules\Job\Filament\Resources;

use Modules\Job\Filament\Resources\JobManagerResource\Widgets\JobStatsOverview;
use Modules\Job\Models\JobManager;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class JobManagerResource extends XotBaseResource
{
    protected static ?string $model = JobManager::class;

    #[Override]
    public static function getWidgets(): array
    {
        return [
            JobStatsOverview::class,
        ];
    }
}
