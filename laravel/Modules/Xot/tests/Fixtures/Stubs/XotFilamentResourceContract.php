<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Fixtures\Stubs;

use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Xot\Models\Cache;

final class XotFilamentResourceContract extends XotBaseResource
{
    protected static ?string $model = Cache::class;

    /**
     * @return array<int|string, Component>
     */
    public static function getFormSchema(): array
    {
        return [];
    }
}
