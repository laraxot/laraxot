<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
use Modules\User\Models\User;
=======
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\Schema;
use Modules\User\Models\User;
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
    protected ?string $model_class = User::class;

=======
return new class extends XotBaseMigration {
>>>>>>> f548be94 (.)
=======
return new class extends XotBaseMigration {
=======
return new class extends XotBaseMigration
{
    protected ?string $model_class = User::class;

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
<<<<<<< HEAD
<<<<<<< HEAD
            $table->string('name')->nullable();
=======
            $table->string('name');
>>>>>>> f548be94 (.)
=======
            $table->string('name');
=======
            $table->string('name')->nullable();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // se entra con sso
<<<<<<< HEAD
<<<<<<< HEAD
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
=======
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
=======
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
>>>>>>> 87273113 (.)
            $table->string('lang', 3)->nullable();
            $table->string('type')->index()->nullable();
            $table->string('state')->index()->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_otp')->default(false);
            $table->timestamp('password_expires_at')->nullable();
<<<<<<< HEAD
=======
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
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

            if (! $this->hasColumn('type')) {
                $table->string('type')->index()->nullable();
            }

            if (! $this->hasColumn('state')) {
                $table->string('state')->index()->nullable();
            }

            if (! $this->hasColumn('is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (! $this->hasColumn('is_otp')) {
                $table->boolean('is_otp')->default(false);
            }

            if (! $this->hasColumn('password_expires_at')) {
                $table->timestamp('password_expires_at')->nullable();
            }

            if (! $this->hasColumn('two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('password');
            }
            if (! $this->hasColumn('two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            }
            if (! $this->hasColumn('two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            }

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

            if (!$this->hasColumn('type')) {
                $table->string('type')->index()->nullable();
            }

            if (!$this->hasColumn('is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (!$this->hasColumn('is_otp')) {
                $table->boolean('is_otp')->default(false);
            }

            if (!$this->hasColumn('password_expires_at')) {
                $table->timestamp('password_expires_at')->nullable();
            }
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

            if (! $this->hasColumn('type')) {
                $table->string('type')->index()->nullable();
            }

            if (! $this->hasColumn('state')) {
                $table->string('state')->index()->nullable();
            }

            if (! $this->hasColumn('is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (! $this->hasColumn('is_otp')) {
                $table->boolean('is_otp')->default(false);
            }

            if (! $this->hasColumn('password_expires_at')) {
                $table->timestamp('password_expires_at')->nullable();
            }

            if (! $this->hasColumn('two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('password');
            }
            if (! $this->hasColumn('two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            }
            if (! $this->hasColumn('two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            }

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
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
