<?php

declare(strict_types=1);

/**
 * @see https://laravel.com/docs/11.x/pennant
 */

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
=======
        // -- CREATE --
>>>>>>> f548be94 (.)
=======
        // -- CREATE --
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('scope');
            $table->text('value');
<<<<<<< HEAD
<<<<<<< HEAD
            $table->unique(['name', 'scope']);
            $table->timestamps();
            $table->softDeletes();
=======
=======
>>>>>>> 87273113 (.)

            $table->unique(['name', 'scope']);
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(
                table: $table,
                hasSoftDeletes: true,
            );
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            $table->unique(['name', 'scope']);
            $table->timestamps();
            $table->softDeletes();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        });
    }
};
