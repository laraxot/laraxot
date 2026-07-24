<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\UserGoogleCalendarSettingFactory;
use Modules\Intervention\Models\UserGoogleCalendarSetting;

class UserGoogleCalendarSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (UserGoogleCalendarSetting::query()->exists()) {
            return;
        }

        UserGoogleCalendarSettingFactory::new()->count(2)->create();
    }
}
