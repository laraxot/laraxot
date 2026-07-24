<?php

declare(strict_types=1);

namespace Modules\Intervention\Exceptions;

use DomainException;

final class InvalidTimeInterval extends DomainException
{
    public static function endBeforeStart(): self
    {
        return new self('End time cannot be before start time.');
    }
}
