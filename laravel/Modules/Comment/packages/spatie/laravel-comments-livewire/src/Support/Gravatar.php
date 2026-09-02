<?php

namespace Spatie\LivewireComments\Support;

class Gravatar
{
    public static function url(string $email): string
    {
        $defaultImage = Config::gravatarDefaultImage();
        $segment = md5(strtolower($email));

        return "https://www.gravatar.com/avatar/{$segment}?d={$defaultImage}";
    }
}
