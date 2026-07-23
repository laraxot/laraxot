<?php

declare(strict_types=1);

namespace Modules\Comment\Exceptions;

use Exception;

class InvalidConfig extends Exception
{
    public static function couldNotDetermineCommentatorModelName(): self
    {
        return new self('Could not determine the commentator model name. Make sure you specified a valid commentator model in the comments config file');
    }
}
