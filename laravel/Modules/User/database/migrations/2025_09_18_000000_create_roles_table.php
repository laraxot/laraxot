<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\Role;
use Modules\Xot\Database\Migrations\XotBaseMigration;

<<<<<<< HEAD
<<<<<<< HEAD
return new class extends XotBaseMigration
{
=======
return new class extends XotBaseMigration {
>>>>>>> 60a2c9a9 (.)
=======
return new class extends XotBaseMigration {
=======
return new class extends XotBaseMigration
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected ?string $model_class = Role::class;

    public function up(): void
    {
        // This migration behaves as a **schema extension** for the roles table.
        // The authoritative CREATE is defined in 2024_01_01_000011_create_roles_table.php.

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            // Laraxot extensions with hasColumn checks - DRY + KISS
            if (! $this->hasColumn('display_name')) {
                $table->string('display_name')->nullable();
            }

            if (! $this->hasColumn('description')) {
                $table->text('description')->nullable();
            }
            $this->updateTimestamps($table);
        });
    }
};
