<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $teams = (bool) config('permission.teams');
        $tableNames = (array) config('permission.table_names');
        $columnNames = (array) config('permission.column_names');
        $pivotRole = (string) ($columnNames['role_pivot_key'] ?? 'role_id');
        $pivotPermission = (string) ($columnNames['permission_pivot_key'] ?? 'permission_id');
        $teamForeignKey = (string) ($columnNames['team_foreign_key'] ?? '');
        $modelMorphKey = (string) ($columnNames['model_morph_key'] ?? '');
        $permissionsTable = (string) ($tableNames['permissions'] ?? '');
        $rolesTable = (string) ($tableNames['roles'] ?? '');
        $modelHasPermissionsTable = (string) ($tableNames['model_has_permissions'] ?? '');
        $modelHasRolesTable = (string) ($tableNames['model_has_roles'] ?? '');
        $roleHasPermissionsTable = (string) ($tableNames['role_has_permissions'] ?? '');

        throw_if(empty($tableNames), Exception::class, 'Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        throw_if($teams && empty($columnNames['team_foreign_key'] ?? null), Exception::class, 'Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');

        Schema::create($permissionsTable, static function (Blueprint $table) {
            // $table->engine('InnoDB');
            $table->bigIncrements('id'); // permission id
            $table->string('name');       // For MyISAM use string('name', 225); // (or 166 for InnoDB with Redundant/Compact row format)
            $table->string('guard_name'); // For MyISAM use string('guard_name', 25);
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($rolesTable, static function (Blueprint $table) use ($teams, $teamForeignKey) {
            // $table->engine('InnoDB');
            $table->bigIncrements('id'); // role id
            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger($teamForeignKey)->nullable();
                $table->index($teamForeignKey, 'roles_team_foreign_key_index');
            }
            $table->string('name');       // For MyISAM use string('name', 225); // (or 166 for InnoDB with Redundant/Compact row format)
            $table->string('guard_name'); // For MyISAM use string('guard_name', 25);
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unique([$teamForeignKey, 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create($modelHasPermissionsTable, static function (Blueprint $table) use ($permissionsTable, $modelMorphKey, $teamForeignKey, $pivotPermission, $teams) {
            $table->unsignedBigInteger($pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($modelMorphKey);
            $table->index([$modelMorphKey, 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($permissionsTable)
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($teamForeignKey);
                $table->index($teamForeignKey, 'model_has_permissions_team_foreign_key_index');

                $table->primary([$teamForeignKey, $pivotPermission, $modelMorphKey, 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            } else {
                $table->primary([$pivotPermission, $modelMorphKey, 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            }

        });

        Schema::create($modelHasRolesTable, static function (Blueprint $table) use ($rolesTable, $modelMorphKey, $teamForeignKey, $pivotRole, $teams) {
            $table->unsignedBigInteger($pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($modelMorphKey);
            $table->index([$modelMorphKey, 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($rolesTable)
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($teamForeignKey);
                $table->index($teamForeignKey, 'model_has_roles_team_foreign_key_index');

                $table->primary([$teamForeignKey, $pivotRole, $modelMorphKey, 'model_type'],
                    'model_has_roles_role_model_type_primary');
            } else {
                $table->primary([$pivotRole, $modelMorphKey, 'model_type'],
                    'model_has_roles_role_model_type_primary');
            }
        });

        Schema::create($roleHasPermissionsTable, static function (Blueprint $table) use ($permissionsTable, $rolesTable, $pivotRole, $pivotPermission) {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($permissionsTable)
                ->onDelete('cascade');

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($rolesTable)
                ->onDelete('cascade');

            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        $cacheStore = (string) config('permission.cache.store');
        $cacheKey = (string) config('permission.cache.key');

        app('cache')
            ->store($cacheStore !== 'default' ? $cacheStore : null)
            ->forget($cacheKey);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = (array) config('permission.table_names');

        throw_if(empty($tableNames), Exception::class, 'Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');

        Schema::drop((string) ($tableNames['role_has_permissions'] ?? ''));
        Schema::drop((string) ($tableNames['model_has_roles'] ?? ''));
        Schema::drop((string) ($tableNames['model_has_permissions'] ?? ''));
        Schema::drop((string) ($tableNames['roles'] ?? ''));
        Schema::drop((string) ($tableNames['permissions'] ?? ''));
    }
};

