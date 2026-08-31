<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Fixtures;

final class ListLogActivitiesNonSchemaFormResource
{
    public static function form(mixed $schema): object
    {
        return new \stdClass();
    }

    public static function canRestore(mixed $record): bool
    {
        return false;
    }
}
