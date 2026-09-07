<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
// ---- models ---
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class CreateRolesTable.
 */
<<<<<<< HEAD
<<<<<<< HEAD
return new class extends XotBaseMigration
{
=======
return new class extends XotBaseMigration {
>>>>>>> f548be94 (.)
=======
return new class extends XotBaseMigration {
=======
return new class extends XotBaseMigration
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->nullable()->index();
            $table->string('name');
            $table->string('guard_name')->default('web');
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
<<<<<<< HEAD
<<<<<<< HEAD
            if (! $this->hasColumn('id')) {
                $table->id();
            }
            if (! $this->hasColumn('team_id')) {
=======
=======
>>>>>>> 87273113 (.)
            if (!$this->hasColumn('id')) {
                $table->id();
            }
            if (!$this->hasColumn('team_id')) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            if (! $this->hasColumn('id')) {
                $table->id();
            }
            if (! $this->hasColumn('team_id')) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $table->foreignId('team_id')->nullable()->index();
            }
            $this->updateTimestamps($table);
        });
    }
};
