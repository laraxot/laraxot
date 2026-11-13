<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Parental\HasParent;

/**
 * Notizia / Novita' del front office: Article con `type` = post (Parental STI).
 */
class Post extends Article
{
    use HasParent;
}
