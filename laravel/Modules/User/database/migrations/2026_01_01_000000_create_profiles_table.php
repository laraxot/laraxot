<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\Profile;
use Modules\Xot\Database\Migrations\XotBaseMigration;

<<<<<<< HEAD
<<<<<<< HEAD
return new class extends XotBaseMigration
{
=======
use function Safe\file_put_contents;

return new class extends XotBaseMigration {
>>>>>>> 60a2c9a9 (.)
=======
use function Safe\file_put_contents;

return new class extends XotBaseMigration {
=======
return new class extends XotBaseMigration
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected ?string $model_class = Profile::class;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('uuid', 36)->index()->nullable();
=======
=======
>>>>>>> 87273113 (.)
        $conn = $this->model->getConnectionName();
        $db = $this->getConn()->getConnection()->getDatabaseName();
        $exists = $this->tableExists();
        file_put_contents(base_path('migration_debug.log'), "MIGRATING profiles | CONN: $conn | DB: $db | EXISTS: ".($exists ? 'YES' : 'NO')."\n", FILE_APPEND);

        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->uuid('id')->primary();
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('uuid', 36)->index()->nullable();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $table->string('user_id', 36)->index()->nullable();
            $table->string('type')->index()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 1)->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('timezone')->nullable();
            $table->string('locale')->nullable();
            $table->json('preferences')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('extra')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
<<<<<<< HEAD
<<<<<<< HEAD
            if (! $this->hasColumn('uuid')) {
                $table->string('uuid', 36)->index()->nullable()->after('id');
            }
            if (! $this->hasColumn('user_id')) {
                $table->string('user_id', 36)->index()->nullable()->after('uuid');
=======
            if (! $this->hasColumn('user_id')) {
                $table->string('user_id', 36)->index()->nullable()->after('id');
>>>>>>> 60a2c9a9 (.)
=======
            if (! $this->hasColumn('user_id')) {
                $table->string('user_id', 36)->index()->nullable()->after('id');
=======
            if (! $this->hasColumn('uuid')) {
                $table->string('uuid', 36)->index()->nullable()->after('id');
            }
            if (! $this->hasColumn('user_id')) {
                $table->string('user_id', 36)->index()->nullable()->after('uuid');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }
            if (! $this->hasColumn('email')) {
                $table->string('email')->nullable()->after('last_name');
            }
            if (! $this->hasColumn('phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (! $this->hasColumn('avatar')) {
<<<<<<< HEAD
<<<<<<< HEAD
                $table->string('avatar')->nullable()->after('bio');
            }
            if (! $this->hasColumn('timezone')) {
                $table->string('timezone')->nullable()->after('avatar');
            }
            if (! $this->hasColumn('locale')) {
                $table->string('locale')->nullable()->after('timezone');
            }
            if (! $this->hasColumn('preferences')) {
                $table->json('preferences')->nullable()->after('locale');
            }
            if (! $this->hasColumn('status')) {
                $table->string('status')->nullable()->after('preferences');
            }
            if (! $this->hasColumn('extra')) {
                $table->json('extra')->nullable()->after('status');
=======
=======
>>>>>>> 87273113 (.)
                $table->string('avatar')->nullable();
            }
            if (! $this->hasColumn('timezone')) {
                $table->string('timezone')->nullable();
            }
            if (! $this->hasColumn('locale')) {
                $table->string('locale')->nullable();
            }
            if (! $this->hasColumn('preferences')) {
                $table->json('preferences')->nullable();
            }
            if (! $this->hasColumn('status')) {
                $table->string('status')->nullable();
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
                $table->string('avatar')->nullable()->after('bio');
            }
            if (! $this->hasColumn('timezone')) {
                $table->string('timezone')->nullable()->after('avatar');
            }
            if (! $this->hasColumn('locale')) {
                $table->string('locale')->nullable()->after('timezone');
            }
            if (! $this->hasColumn('preferences')) {
                $table->json('preferences')->nullable()->after('locale');
            }
            if (! $this->hasColumn('status')) {
                $table->string('status')->nullable()->after('preferences');
            }
            if (! $this->hasColumn('extra')) {
                $table->json('extra')->nullable()->after('status');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }
        });
    }
};
