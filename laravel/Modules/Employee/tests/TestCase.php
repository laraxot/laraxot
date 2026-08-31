<?php

declare(strict_types=1);

namespace Modules\Employee\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\User;
use Modules\Employee\Models\WorkHour;
use Modules\Employee\Providers\EmployeeServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;
use PHPUnit\Framework\Assert;

/**
 * Base test case per il modulo Employee.
 *
 * ✅ Configurato per Pest
 * ✅ Performance ottimizzate
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions; // ✅ SEMPRE - Performance 100x migliori

    public ?Employee $employee = null;

    public ?WorkHour $workHour = null;

    public ?User $user = null;

    public ?Carbon $today = null;

    protected function setUp(): void
    {
        // Alcuni test guardano lo schema (es. il tipo delle colonne che indicano
        // una persona): senza il fixture condiviso le tabelle non esistono.
        $this->prepareSharedFixcitySqliteForTesting();

        parent::setUp();

        // ✅ NO migrate manuale - DatabaseTransactions gestisce tutto
        // ✅ NO seeding manuale - Factories gestiscono i dati

        // Setup specifico del modulo se necessario
        $this->withoutExceptionHandling();
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            EmployeeServiceProvider::class,
        ];
    }

    public function employee(): Employee
    {
        Assert::assertNotNull($this->employee);

        return $this->employee;
    }

    public function workHourModel(): WorkHour
    {
        Assert::assertNotNull($this->workHour);

        return $this->workHour;
    }

    public function todayDate(): Carbon
    {
        Assert::assertNotNull($this->today);

        return $this->today;
    }

    public function user(): User
    {
        Assert::assertNotNull($this->user);

        return $this->user;
    }
}
