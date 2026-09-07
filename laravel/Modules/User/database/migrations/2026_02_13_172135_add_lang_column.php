<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\User;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = User::class;

    public function up(): void
    {
        // Add the `lang` column to the users table when it is missing.
        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('lang')) {
                $table->string('lang', 5)->nullable();
            }
        });
    }
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('user')->table('users', function (Blueprint $table): void {
            if (! $this->hasColumn('lang')) {
                $table->string('lang', 5)->default('it')->after('state');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('user')->table('users', function (Blueprint $table): void {
            $table->dropColumn('lang');
        });
    }

    private function hasColumn(string $column): bool
    {
        $connection = Schema::connection('user')->getConnection();
        $result = $connection->select('SHOW COLUMNS FROM users WHERE Field = ?', [$column]);

        return ! empty($result);
    }
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\User;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = User::class;

    public function up(): void
    {
        // Add the `lang` column to the users table when it is missing.
        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('lang')) {
                $table->string('lang', 5)->nullable();
            }
        });
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
};
