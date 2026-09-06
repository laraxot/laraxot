<?php

/**
 * @see https://medium.com/technology-hits/how-to-import-a-csv-excel-file-in-laravel-d50f93b98aa4
 */

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Spatie\QueueableAction\ActionJob;

use Spatie\QueueableAction\QueueableAction;

/**
 * Class ConfigService.
 */
class ConfigAction
{
    use QueueableAction;
    private static ?self $instance = null;

    public function __construct()
    {
        // ---
        // require_once __DIR__.'/vendor/autoload.php';
    }

    /**
     * Undocumented function.
     */
    public static function getInstance(): self
    {
        if (! (self::$instance instanceof self)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Undocumented function.
     */
    public static function make(): self
    {
        return static::getInstance();
    }
}
