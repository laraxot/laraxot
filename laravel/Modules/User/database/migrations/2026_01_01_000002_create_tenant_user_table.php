<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use function Safe\file_put_contents;

>>>>>>> 60a2c9a9 (.)
=======
use function Safe\file_put_contents;

=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
return new class extends XotBaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
        $conn = $this->getConn()->getConnection()->getName();
        $db = $this->getConn()->getConnection()->getDatabaseName();
        $exists = $this->tableExists();
        file_put_contents(base_path('migration_debug.log'), "MIGRATING tenant_user | CONN: $conn | DB: $db | EXISTS: ".($exists ? 'YES' : 'NO')."\n", FILE_APPEND);

<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            // $table->uuid('id')->primary();
            $table->id();
            $table->foreignId('tenant_id');
            $table->uuid('user_id')->nullable()->index();

            // $table->foreignIdFor(\Modules\Xot\Datas\XotData::make()->getUserClass());
            // $table->string('role')->nullable();
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
        });
    }
};
