<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Parental\HasParent;

/**
 * Evento del front office: Article con `type` = event (Parental STI).
 */
class Event extends Article
{
    use HasParent;
}
