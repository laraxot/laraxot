<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\TeamPermission;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = TeamPermission::class;
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    /**
     * Nome della tabella gestita dalla migrazione.
     */
    protected string $table_name = 'team_permissions';
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Models\TeamPermission;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = TeamPermission::class;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Esegue la migrazione.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('permission'); // The permission key/slug
            $table->string('name')->nullable(); // Human readable name

            // Optional: Add uniqueness if needed, e.g. unique(['team_id', 'permission'])
            $table->unique(['team_id', 'permission']);
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('permission')) {
                $table->string('permission');
            }
            if (! $this->hasColumn('name')) {
                $table->string('name')->nullable();
            }

<<<<<<< HEAD
<<<<<<< HEAD
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
=======
=======
>>>>>>> 87273113 (.)
            $this->updateTimestamps(
                table: $table,
                hasSoftDeletes: true,
            );
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        });
    }
};
