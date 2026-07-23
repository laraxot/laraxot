<?php

declare(strict_types=1);

namespace Modules\Comment\Exceptions;

use Exception;

class CannotCreateComment extends Exception
{
    public static function userIsRequired(): self
    {
        return new self('Creating a comment without a user is not allowed');
    }
}
