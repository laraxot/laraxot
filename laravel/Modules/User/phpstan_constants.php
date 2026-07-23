<?php

declare(strict_types=1);

use function Safe\define;

// https://phpstan.org/user-guide/discovering-symbols

if (! defined('LARAVEL_DIR')) {
    define('LARAVEL_DIR', __DIR__);
}
