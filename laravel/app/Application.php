<?php

/**
 * ---.
 */
declare(strict_types=1);

namespace App;

class Application extends \Illuminate\Foundation\Application
{
    public function publicPath($path = ''): string
    {
        $tmp = $this->basePath.'/../public_html/'.$path;
        $tmp = str_replace(['/', '\\'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $tmp);
        if (realpath($tmp) === false) {
            return realpath($this->basePath.'/../public_html/').'/'.$path;
        }

        return realpath($tmp);
    }
}
