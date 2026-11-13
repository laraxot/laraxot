<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;

use function Safe\shell_exec;

class ImportMdbToMySQL extends Command
{
    /**
     * Il nome e la firma del comando.
     *
     * @var string
     */
    protected $signature = 'xot:import-mdb-to-mysql';

    /**
     * La descrizione del comando.
     *
     * @var string
     */
    protected $description = 'Importa un file .mdb in MySQL';

    /**
     * Esegui il comando.
     */
    public function handle(): int
    {
        $mdbFile = $this->ask('Inserisci il percorso del file .mdb');
        if (! is_string($mdbFile)) {
            throw new \RuntimeException('Il percorso del file deve essere una stringa');
        }

        $mysqlDb = $this->ask('Inserisci il nome del database MySQL');
        if (! is_string($mysqlDb)) {
            throw new \RuntimeException('Il nome del database deve essere una stringa');
        }

        $this->info("File .mdb: {$mdbFile}");
        $this->info("Database MySQL: {$mysqlDb}");

        $this->info('Esportando tabelle dal file .mdb...');
        $tables = $this->exportTablesToSQL($mdbFile);
        if (empty($tables)) {
            $this->error('Nessuna tabella trovata nel file .mdb');

            return Command::FAILURE;
        }

        $this->info('Importando le tabelle in MySQL...');
        $this->importTablesIntoMySQL($tables, $mysqlDb);

        $this->info('Importazione completata con successo!');

        return Command::SUCCESS;
    }

    /**
     * Esporta tutte le tabelle dal file .mdb in formato SQL.
     *
     * @return array<int, string>
     */
    private function exportTablesToSQL(string $mdbFile): array
    {
        $tables = [];
        $tableList = shell_exec("mdb-tables {$mdbFile}");
        if (! $tableList) {
            return [];
        }

        // Esporta ogni tabella in un file SQL
        foreach (explode("\n", trim($tableList)) as $table) {
            if (empty($table)) {
                continue;
            }

            $tables[] = $table;
            $sqlFile = storage_path("app/{$table}.sql");
            shell_exec("mdb-schema {$mdbFile} mysql > {$sqlFile}");
            shell_exec("mdb-export -I mysql {$mdbFile} {$table} >> {$sqlFile}");
        }

        return $tables;
    }

    /**
     * Importa le tabelle in MySQL.
     *
<<<<<<< HEAD
     * @param  array<int, string>  $tables
=======
<<<<<<< .merge_file_uPQCEE
     * @param  array<int, string>  $tables
=======
     * <<<<<<< HEAD
     *
     * @param array<int, string> $tables
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_V88qU4
     * @param array<int, string> $tables
     *                                   =======
     *                                   =======
     *                                   <<<<<<< HEAD
     * @param array<int, string> $tables
     *                                   =======
     *                                   <<<<<<< .merge_file_cbpLum
     * @param array<int, string> $tables
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_V88qU4
     * @param array<int, string> $tables
     *                                   =======
     *                                   >>>>>>> laraxot/dev
     *                                   <<<<<<< .merge_file_8tVWLS
     * @param array<int, string> $tables
     *                                   =======
     *                                   <<<<<<< HEAD
     * @param array<int, string> $tables
     *                                   =======
     * @param array<int, string> $tables
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> .merge_file_REDNjH
     *                                   >>>>>>> .merge_file_JWAepJ
     *                                   =======
     * @param array<int, string> $tables
     *                                   >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                   <<<<<<< HEAD
     *                                   =======
     *                                   >>>>>>> .merge_file_aQREAj
     *                                   >>>>>>> laraxot/dev
     *                                   =======
     * @param array<int, string> $tables
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_LSvRpm
>>>>>>> 77b1e2748 (first)
     */
    private function importTablesIntoMySQL(array $tables, string $mysqlDb): void
    {
        foreach ($tables as $table) {
            $sqlFile = storage_path("app/{$table}.sql");
            $command = "mysql -u root {$mysqlDb} < {$sqlFile}";
            shell_exec($command);
        }
    }
}
