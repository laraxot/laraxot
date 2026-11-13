<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Modules\Xot\Filament\Resources\XotBaseResource;

final class ListLogActivitiesActionTestResource extends XotBaseResource
{
   

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function getUrl(?string $name = null, array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false, ?string $configuration = null): string
=======

final class ListLogActivitiesActionTestResource
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function getUrl(string $name, array $parameters = []): string
>>>>>>> 13cf1831 (.)
    {
        $record = $parameters['record'] ?? null;
        $key = '';
        if ($record instanceof Model) {
            $modelKey = $record->getKey();
            $key = is_scalar($modelKey) ? (string) $modelKey : '';
        }

        return '/log-activity/'.$name.'/'.$key;
    }
}
