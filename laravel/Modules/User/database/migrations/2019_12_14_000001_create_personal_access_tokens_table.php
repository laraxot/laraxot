<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    /**
     * Run the migrations.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function up(): void
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return void
     */
    public function up()
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
     */
    public function up(): void
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        // -- CREATE --
        $this->tableCreate(
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
            });

        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table) {
<<<<<<< HEAD
<<<<<<< HEAD
                // if (! $this->hasColumn('email'
=======
                // if (! $this->hasColumn('email')) {
>>>>>>> 60a2c9a9 (.)
=======
                // if (! $this->hasColumn('email')) {
=======
                // if (! $this->hasColumn('email'
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                //    $table->string('email')->nullable();
                // }
            }
        );
    }
};
