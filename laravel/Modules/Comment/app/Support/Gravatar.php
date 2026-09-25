<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Modules\Comment\Datas\CommentConfigData;

class Gravatar
{
    public static function url(string $email): string
    {
        $defaultImage = (string) (CommentConfigData::make()->gravatar['default_image'] ?? 'mp');
        $segment = md5(strtolower($email));

        return "https://www.gravatar.com/avatar/{$segment}?d={$defaultImage}";
    }
}
