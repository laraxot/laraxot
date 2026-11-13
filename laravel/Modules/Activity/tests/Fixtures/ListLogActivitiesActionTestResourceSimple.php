<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Modules\Xot\Filament\Resources\XotBaseResource;

final class ListLogActivitiesActionTestResourceSimple extends XotBaseResource
=======

final class ListLogActivitiesActionTestResourceSimple
>>>>>>> 13cf1831 (.)
{
    /**
     * @param  array<string, mixed>  $parameters
     */
<<<<<<< HEAD
    public static function getUrl(?string $name = null, array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false, ?string $configuration = null): string
=======
    public static function getUrl(string $name, array $parameters = []): string
>>>>>>> 13cf1831 (.)
    {
        $record = $parameters['record'] ?? null;
        $key = '';
        if ($record instanceof Model) {
            $modelKey = $record->getKey();
            $key = is_scalar($modelKey) ? (string) $modelKey : '';
        }

        return '/log-activity/'.$key;
    }
}
