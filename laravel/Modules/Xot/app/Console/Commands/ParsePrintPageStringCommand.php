<?php

declare(strict_types=1);
/**
<<<<<<< HEAD
 * Mirror di Modules\DbForge\Console\Commands\ParsePrintPageStringCommand (canonico, stessa signature).
 *
=======
>>>>>>> 77b1e2748 (first)
 * @see ---
 */

namespace Modules\Xot\Console\Commands;

<<<<<<< HEAD
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Modules\Xot\Actions\ParsePrintPageStringAction;

use function Safe\json_encode;

=======
use Illuminate\Console\Command;
use Modules\Xot\Actions\ParsePrintPageStringAction;

>>>>>>> 77b1e2748 (first)
class ParsePrintPageStringCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xot:parse-print-page {str}';

    /**
     * The console command description.
     *
     * @var string
     */
<<<<<<< HEAD
    protected $description = "Espande una stringa di pagine di stampa (es. 1-4,6) nell'elenco dei numeri di pagina";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
=======
    protected $description = ' esplode';

    /**
     * Create a new command instance.
     */
>>>>>>> 77b1e2748 (first)

    /**
     * Execute the console command.
     */
<<<<<<< HEAD
    public function handle(): int
    {
        $str = $this->argument('str');
        if (! is_string($str)) {
            throw new Exception('argument str must be a string');
        }

        try {
            $pages = app(ParsePrintPageStringAction::class)->execute($str);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->line(json_encode($pages));

        return self::SUCCESS;
=======
    public function handle(): void
    {
        $str = $this->argument('str');
        if (! is_string($str)) {
            throw new \Exception('argument str must be a string');
        }
        dddx(app(ParsePrintPageStringAction::class)->execute($str));
>>>>>>> 77b1e2748 (first)
    }
}
