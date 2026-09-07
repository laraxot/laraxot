<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\Tenant;
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
    protected ?string $model_class = Tenant::class;

    /**
     * Esegue la migrazione.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('domain')->nullable();
            $table->string('database')->nullable();
            $table->boolean('is_active')->default(true);
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            // Aggiungi colonne mancanti se non esistono
            if (! $this->hasColumn('email_address')) {
                $table->string('email_address')->nullable();
            }
            if (! $this->hasColumn('phone')) {
                $table->string('phone')->nullable();
            }
            if (! $this->hasColumn('mobile')) {
                $table->string('mobile')->nullable();
            }
            if (! $this->hasColumn('address')) {
                $table->text('address')->nullable();
            }
            if (! $this->hasColumn('primary_color')) {
                $table->string('primary_color')->nullable();
            }
            if (! $this->hasColumn('secondary_color')) {
                $table->string('secondary_color')->nullable();
            }

            $this->updateTimestamps($table, true);
        });
    }
};
