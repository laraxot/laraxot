<?php

declare(strict_types=1);

namespace Modules\HR\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\HR\Providers\HrServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case modulo HR.
 *
 * Schema sul fixture `database/fixcity_data.sqlite` **prima** di
 * `parent::setUp()` / DatabaseTransactions: altrimenti la migrate resta nella
 * transazione del test e sparisce al rollback (employees=N al run successivo).
 *
 * `employees.user_id` → FK `users` (uuid): stub minimo se manca la tabella users.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'xot'];

    protected function setUp(): void
    {
        $this->prepareSharedFixcitySqliteForTesting();

        $this->ensureUsersStubForHrFk();
        $this->ensureHrSchema();

        parent::setUp();

        config(['auth.providers.users.model' => User::class]);
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            UserServiceProvider::class,
            HrServiceProvider::class,
        ];
    }

    /**
     * Stub `users` solo per soddisfare FK SQLite (id string 36 = User module).
     */
    private function ensureUsersStubForHrFk(): void
    {
        if (Schema::connection('sqlite')->hasTable('users')) {
            return;
        }

        Schema::connection('sqlite')->create('users', static function (Blueprint $table): void {
            $table->string('id', 36)->primary();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Idempotente: schema HR sul fixture sqlite (path-only, no --force).
     */
    private function ensureHrSchema(): void
    {
        if (Schema::connection('sqlite')->hasTable('employees')
            && Schema::connection('sqlite')->hasTable('absence_requests')) {
            return;
        }

        Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--path' => 'Modules/HR/database/migrations',
            '--no-interaction' => true,
        ]);
    }
}
