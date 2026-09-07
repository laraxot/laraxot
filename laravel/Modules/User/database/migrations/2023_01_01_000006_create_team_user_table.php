<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            // $table->uuid('id')->primary();
            $table->id();
            $table->foreignId('team_id');
            $table->uuid('user_id')->nullable()->index();
            // $table->foreignIdFor(\Modules\Xot\Datas\XotData::make()->getUserClass());
            $table->string('role')->nullable();

            // $table->unique(['team_id', 'user_id']);
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
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

            // $this->updateUser($table);
        });
    }
};
