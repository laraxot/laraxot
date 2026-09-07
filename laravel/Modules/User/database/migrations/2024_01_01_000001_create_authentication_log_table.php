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
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            // $table->morphs('authenticatable');
<<<<<<< HEAD
<<<<<<< HEAD
            $table->uuidMorphs('authenticatable', 'k_auth_log_morph');
=======
            $table->uuidMorphs('authenticatable', 'k_authenticatable');
>>>>>>> f548be94 (.)
=======
            $table->uuidMorphs('authenticatable', 'k_authenticatable');
=======
            $table->uuidMorphs('authenticatable', 'k_auth_log_morph');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->boolean('login_successful')->default(false);
            $table->timestamp('logout_at')->nullable();
            $table->boolean('cleared_by_user')->default(false);
            $table->json('location')->nullable();
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
<<<<<<< HEAD
<<<<<<< HEAD
            // if (! $this->hasColumn('email'))
=======
            // if (! $this->hasColumn('email')) {
>>>>>>> f548be94 (.)
=======
            // if (! $this->hasColumn('email')) {
=======
            // if (! $this->hasColumn('email'))
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            //    $table->string('email')->nullable();
            // }
            $this->updateTimestamps($table);
        });
    }
};
