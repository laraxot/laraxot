<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;

class InterventionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InterventionSeeder::class,
            DailyNoteSeeder::class,
            TimePunchSeeder::class,
            IssueReportSeeder::class,
            InterventionMaterialLineSeeder::class,
            InterventionReportShareSeeder::class,
            UserGoogleCalendarSettingSeeder::class,
            InterventionUserAssignmentSeeder::class,
        ]);
    }
}
