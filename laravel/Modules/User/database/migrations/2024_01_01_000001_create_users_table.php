<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
=======
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\Schema;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class CreateLiveuserUsersTable.
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
            // $table->uuid('id')->primary();
            $table->string('id', 36)->primary();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // se entra con sso
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->softDeletes();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
<<<<<<< HEAD
<<<<<<< HEAD
            if (! $this->hasColumn('first_name')) {
=======
            if (!$this->hasColumn('first_name')) {
>>>>>>> f548be94 (.)
=======
            if (!$this->hasColumn('first_name')) {
=======
            if (! $this->hasColumn('first_name')) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $table->string('first_name')->after('name')->nullable();
            } else {
                $table->string('first_name')->nullable()->change();
            }

<<<<<<< HEAD
<<<<<<< HEAD
            if (! $this->hasColumn('last_name')) {
=======
            if (!$this->hasColumn('last_name')) {
>>>>>>> f548be94 (.)
=======
            if (!$this->hasColumn('last_name')) {
=======
            if (! $this->hasColumn('last_name')) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $table->string('last_name')->after('name')->nullable();
            } else {
                $table->string('last_name')->nullable()->change();
            }

<<<<<<< HEAD
<<<<<<< HEAD
            if (! $this->hasColumn('current_team_id')) {
                $table->foreignId('current_team_id')->nullable();
            }

            if (! $this->hasColumn('profile_photo_path')) {
                $table->string('profile_photo_path', 2048)->nullable();
            }

            if (! $this->hasColumn('lang')) {
                $table->string('lang', 3)->nullable();
            }

            if (! $this->hasColumn('is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (! $this->hasColumn('is_otp')) {
                $table->boolean('is_otp')->default(false);
            }

            if (! $this->hasColumn('password_expires_at')) {
=======
=======
>>>>>>> 87273113 (.)
            if (!$this->hasColumn('current_team_id')) {
                $table->foreignId('current_team_id')->nullable();
            }

            if (!$this->hasColumn('profile_photo_path')) {
                $table->string('profile_photo_path', 2048)->nullable();
            }

            if (!$this->hasColumn('lang')) {
                $table->string('lang', 3)->nullable();
            }

            if (!$this->hasColumn('is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (!$this->hasColumn('is_otp')) {
                $table->boolean('is_otp')->default(false);
            }

            if (!$this->hasColumn('password_expires_at')) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            if (! $this->hasColumn('current_team_id')) {
                $table->foreignId('current_team_id')->nullable();
            }

            if (! $this->hasColumn('profile_photo_path')) {
                $table->string('profile_photo_path', 2048)->nullable();
            }

            if (! $this->hasColumn('lang')) {
                $table->string('lang', 3)->nullable();
            }

            if (! $this->hasColumn('is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (! $this->hasColumn('is_otp')) {
                $table->boolean('is_otp')->default(false);
            }

            if (! $this->hasColumn('password_expires_at')) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $table->timestamp('password_expires_at')->nullable();
            }
            if ($this->hasColumn('password')) {
                $table->string('password')->nullable()->change();
            }

            if ($this->getColumnType('id') === 'uuid') {
                Schema::disableForeignKeyConstraints();

                $table->dropPrimary(['id']);
                $table->string('id', 36)->nullable()->change();
                $table->primary('id');

                Schema::enableForeignKeyConstraints();
            }
            // $this->updateUser($table);
            $this->updateTimestamps($table, true);
        });
    }
};
