<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Query;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

final class GetFieldnamesByTablenameAction
{
    use QueueableAction;

    /**
     * Get column names from a table with specific database connection.
     *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_I8Idnw
>>>>>>> 77b1e2748 (first)
     * @param  string  $table  Table name to get columns from
     * @param  string|null  $connectionName  Database connection name (optional)
     * @return list<string>
     *
     * @throws \InvalidArgumentException
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD
     *
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     *                                    <<<<<<< HEAD
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     * @param string      $table          Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     *
     * @throws \InvalidArgumentException
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_Jfzn7Q
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   >>>>>>> laraxot/dev
     * @throws \InvalidArgumentException
     *                                   =======
     *                                   =======
     * @throws \InvalidArgumentException
     *                                   =======
     *                                   <<<<<<< .merge_file_9lJc4S
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< .merge_file_Jfzn7Q
     *                                   >>>>>>> .merge_file_G3Zryu
     * @throws \InvalidArgumentException
     *                                   <<<<<<< .merge_file_9lJc4S
     *                                   =======
     *                                   =======
     *                                   >>>>>>> laraxot/dev
     *                                   <<<<<<< .merge_file_AaGd7c
     * @throws \InvalidArgumentException
     *                                   =======
     *                                   <<<<<<< HEAD
     * @throws \InvalidArgumentException
     *                                   =======
     * @throws \InvalidArgumentException
     * @throws \InvalidArgumentException
     *                                   >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                   <<<<<<< HEAD
     *                                   =======
     * @throws \InvalidArgumentException
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
     *
     * @return list<string>
     * @return list<string>
     * @return list<string>
     * @return list<string>
     * @return list<string>
     *                      <<<<<<< HEAD
     *                      =======
     * @return list<string>
     *                      >>>>>>> laraxot/dev
     *                      >>>>>>> laraxot/dev
     *                      >>>>>>> .merge_file_46hKcJ
     *                      >>>>>>> .merge_file_hUuUgC
     *                      =======
     * @return list<string>
     *                      <<<<<<< HEAD
     *                      =======
     *                      >>>>>>> .merge_file_G3Zryu
     *                      >>>>>>> laraxot/dev
     *                      =======
     * @return list<string>
     *                      >>>>>>> laraxot/dev
     *                      >>>>>>> laraxot/dev
>>>>>>> .merge_file_VNYnnU
>>>>>>> 77b1e2748 (first)
     */
    public function execute(string $table, ?string $connectionName = null): array
    {
        // Validate table name
        if (empty(trim($table))) {
            throw new \InvalidArgumentException('Table name cannot be empty.');
        }

        // Use default connection if none is provided
        Assert::string($connectionName ??= config('database.default'));

        // Validate database connection
        if (! $this->isValidConnection($connectionName)) {
            throw new \InvalidArgumentException(sprintf('Invalid database connection: %s', $connectionName));
        }

        // Check if table exists in the database
        if (! Schema::connection($connectionName)->hasTable($table)) {
            throw new \InvalidArgumentException(sprintf('Table "%s" does not exist in connection "%s".', $table, $connectionName));
        }

        // Get and return column listing
        try {
            $columns = Schema::connection($connectionName)->getColumnListing($table);

            return array_values($columns);
            // $columns = array_map('strval', $columns);
            // return array_values(array_map(static fn ($value): string => is_string($value) ? $value : (string) $value, $columns));
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(sprintf('Error fetching columns from table "%s": %s', $table, $e->getMessage()));
        }
    }

    /**
     * Check if a given database connection is valid.
     */
    private function isValidConnection(string $connectionName): bool
    {
        try {
            DB::connection($connectionName)->getPdo();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
