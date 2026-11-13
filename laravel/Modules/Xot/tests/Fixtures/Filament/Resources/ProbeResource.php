<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Fixtures\Filament\Resources;

use Filament\Schemas\Components\Wizard\Step;
use Modules\Xot\Filament\Resources\XotBaseResource;
<<<<<<< HEAD
=======
use Modules\Xot\Tests\Fixtures\Models\Probe;
>>>>>>> laraxot/dev

class ProbeResource extends XotBaseResource
{
    protected static string $module = 'Xot';

<<<<<<< HEAD
    protected static ?string $model = null;
=======
    protected static ?string $model = Probe::class;
>>>>>>> laraxot/dev

    /**
     * @return array<int, string>
     */
    public static function getCustomStepSchema(): array
    {
        return ['ok'];
    }

    public static function callGetKeyTrans(string $key): string
    {
        return static::getKeyTrans($key);
    }

    public static function callGetStepByName(string $name): Step
    {
        return static::getStepByName($name);
    }

    public static function resetModelCache(): void
    {
<<<<<<< HEAD
        static::$model = null;
=======
        static::$model = Probe::class;
>>>>>>> laraxot/dev
    }
}
