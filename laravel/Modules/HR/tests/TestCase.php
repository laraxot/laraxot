<?php

declare(strict_types=1);

namespace Modules\HR\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\HR\Providers\HrServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case per il modulo HR.
 *
 * NOTA: `prepareSharedFixcitySqliteForTesting()` (vedi Modules/Gdpr, Modules/Activity)
 * punta le connessioni sqlite su `database/fixcity_data.sqlite`, un fixture di UN ALTRO
 * progetto (tabelle: assets/customers/contacts/resellers/...). Non contiene `users`,
 * `roles`, `employees`: inutile qui, per questo non viene usato. Il database di test
 * reale per Auth/HR resta debito (vedi Modules/HR/docs/gestionale-commesse-port.md e
 * docs/gestionale-technical-debt.md D6/D13): senza un fixture sqlite pre-migrato per
 * QUESTO monorepo (o senza `techplanner_data_test` in MySQL), i test Feature che
 * scrivono su tabelle reali falliscono con "no such table" anche con questo TestCase.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
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
}
