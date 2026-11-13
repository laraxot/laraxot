<?php

declare(strict_types=1);
/**
<<<<<<< HEAD
=======
 * Mirror di Modules\DbForge\Console\Commands\ParsePrintPageStringCommand (canonico, stessa signature).
 *
>>>>>>> laraxot/dev
 * @see ---
 */

namespace Modules\Xot\Console\Commands;

<<<<<<< HEAD
use Illuminate\Console\Command;
use Modules\Xot\Actions\ParsePrintPageStringAction;

=======
use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Modules\Xot\Actions\ParsePrintPageStringAction;

use function Safe\json_encode;

>>>>>>> laraxot/dev
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
    protected $description = ' esplode';

    /**
     * Create a new command instance.
     */
=======
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
>>>>>>> laraxot/dev

    /**
     * Execute the console command.
     */
<<<<<<< HEAD
    public function handle(): void
    {
        $str = $this->argument('str');
        if (! is_string($str)) {
            throw new \Exception('argument str must be a string');
        }
        dddx(app(ParsePrintPageStringAction::class)->execute($str));
=======
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
>>>>>>> laraxot/dev
    }
}
