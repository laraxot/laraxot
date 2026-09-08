<?php

declare(strict_types=1);

use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (! is_array($tableNames) || [] === $tableNames) {
            throw new Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        $teamForeignKey = is_array($columnNames) ? ($columnNames['team_foreign_key'] ?? null) : null;

        if ($teams && empty($teamForeignKey)) {
            throw new Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        $cache_store = config('permission.cache.store');
        $store = is_string($cache_store) && 'default' !== $cache_store ? $cache_store : null;

        $cache_key = config('permission.cache.key');

        try {
            // Verifica se l'applicazione è completamente inizializzata
            if (app()->bound('cache') && is_string($cache_key)) {
                app('cache')->store($store)->forget($cache_key);
            }
        } catch (Exception $e) {
            // Silently ignore cache errors during package discovery
            // echo $e->getMessage();
        }
    }

    /* -- is in xotbasemigration
     * public function down(): void
     * {
     * $tableNames = config('permission.table_names');
     *
     * if (empty($tableNames)) {
     * throw new Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
     * }
     *
     * Schema::drop($tableNames['role_has_permissions']);
     * Schema::drop($tableNames['model_has_roles']);
     * Schema::drop($tableNames['model_has_permissions']);
     * Schema::drop($tableNames['roles']);
     * Schema::drop($tableNames['permissions']);
     * }
     */
};
