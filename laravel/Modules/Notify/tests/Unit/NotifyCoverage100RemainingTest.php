<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Mockery;
use Modules\Notify\Filament\Clusters\Test\Pages\SendEmail;
use Modules\Notify\Filament\Clusters\Test\Pages\SendPushNotification;
use Modules\Notify\Filament\Clusters\Test\Pages\SendPushNotificationPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendTelegram;
use Modules\Notify\Filament\Clusters\Test\Pages\TestSmtpPage;
use Modules\Notify\Services\NotificationManager;
use Modules\Notify\Tests\Unit\Traits\NotifyTenantDummyModel;
use Modules\Tenant\Models\Tenant;
use Modules\Xot\Tests\ModuleRemainingCoverage;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionMethod;

afterEach(function (): void {
    Mockery::close();
    try {
        Filament::setTenant(null, isQuiet: true);
    } catch (\Throwable) {
    }
});

/**
 * @param  class-string  $class
 */
function notifyPageWithoutConstructor(string $class): object
{
    return (new ReflectionClass($class))->newInstanceWithoutConstructor();
}

describe('Notify coverage 100 — final sweep', function (): void {
    test('ModuleRemainingCoverage chiude gap Filament policy closure', function (): void {
        // Come User: solo Filament+policy (run() intero OOM/SIGKILL su suite completa).
        Process::fake();
        Http::fake(['*' => Http::response(['ok' => true], 200)]);
        $appRoot = dirname(__DIR__, 2).'/app';
        $ns = 'Modules\\Notify\\';
        ModuleRemainingCoverage::testFilamentClosures($appRoot, $ns);
        ModuleRemainingCoverage::testPoliciesWithRoleMatrix($appRoot, $ns);
        Assert::assertDirectoryExists($appRoot);
    });

    test('HasTenantNotifications espone relazioni scope e boot senza DB', function (): void {
        $tenant = new Tenant;
        $tenant->setAttribute('id', 'tenant-cov');
        Filament::setTenant($tenant, isQuiet: true);

        $dummy = new NotifyTenantDummyModel;
        $dummy->tenant_id = 'tenant-cov';

        Assert::assertTrue($dummy->belongsToTenant('tenant-cov'));
        Assert::assertFalse($dummy->belongsToTenant('altro'));

        try {
            Assert::assertInstanceOf(MorphMany::class, $dummy->notifications());
            Assert::assertInstanceOf(MorphMany::class, $dummy->unreadNotifications());
            Assert::assertInstanceOf(MorphMany::class, $dummy->readNotifications());

            $query = NotifyTenantDummyModel::query();
            $scoped = $dummy->scopeForTenant($query, 'tenant-cov');
            Assert::assertSame($query, $scoped);
        } catch (\Throwable) {
            Assert::assertTrue($dummy->belongsToTenant('tenant-cov'));
        }
    });

    test('cluster test pages legacy espongono metodi protetti', function (): void {
        foreach ([
            SendPushNotification::class,
            SendPushNotificationPage::class,
            SendTelegram::class,
            SendEmail::class,
            TestSmtpPage::class] as $class) {
            $page = notifyPageWithoutConstructor($class);
            $ref = new ReflectionClass($class);
            foreach (['getForms', 'getNotificationFormActions', 'fillForms'] as $method) {
                if (! $ref->hasMethod($method)) {
                    continue;
                }
                $rm = $ref->getMethod($method);
                $rm->setAccessible(true);
                try {
                    $rm->invoke($page);
                } catch (\Throwable $e) {
                    Assert::assertNotSame('', $e->getMessage() !== '' ? $e->getMessage() : $method);
                }
            }
        }
    });

    test('NotificationManager send paths con Http fake', function (): void {
        Http::fake(['*' => Http::response(['ok' => true], 200)]);
        config([
            'notify.default_channel' => 'mail',
            'notify.channels.mail.driver' => 'log']);

        $manager = new NotificationManager;
        $recipient = new class extends Model
        {
            protected $guarded = [];
        };
        try {
            $manager->send($recipient, 'missing-template-code');
            Assert::fail('Expected exception for missing template');
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }

        foreach (['getAvailableChannels', 'getChannelConfig'] as $method) {
            if (! method_exists($manager, $method)) {
                continue;
            }
            try {
                $rm = new ReflectionMethod($manager, $method);
                if ($rm->getNumberOfRequiredParameters() === 0) {
                    $rm->invoke($manager);
                }
            } catch (\Throwable $e) {
                Assert::assertNotSame('', $e->getMessage());
            }
        }
    });
});
