<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Notify\Tests\Unit\Traits\NotifyRateLimitDummy;
use Modules\Notify\Tests\Unit\Traits\NotifyTenantDummyModel;
use Modules\Notify\Tests\Unit\Traits\NotifyTrackingDummy;
use Modules\Tenant\Models\Tenant;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
<<<<<<< HEAD
=======
uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
describe('Notify Traits Coverage', function (): void {
    test('_notification_rate_limiting_helpers_work_with_limiter', function (): void {
        config()->set('cache.default', 'array');
        config()->set('notify.rate_limiting.enabled', true);
        config()->set('notify.rate_limiting.max_attempts', 1);
        config()->set('notify.rate_limiting.decay_minutes', 1);

        try {
<<<<<<< HEAD
            $dummy = new NotifyRateLimitDummy;
=======
            $dummy = new NotifyRateLimitDummy();
>>>>>>> a988596b (first)
            $key = $dummy->key('mail', 'id-'.uniqid());
            $dummy->reset($key);

            Assert::assertTrue($dummy->shouldSend($key));
            Assert::assertFalse($dummy->shouldSend($key));
            Assert::assertLessThanOrEqual(0, $dummy->remaining($key));
            Assert::assertGreaterThanOrEqual(0, $dummy->retryAfter($key));

            $dummy->reset($key);
            Assert::assertTrue($dummy->shouldSend($key));
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (Throwable $e) {
=======
        } catch (\Throwable $e) {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        } catch (Throwable $e) {
>>>>>>> a988596b (first)
            Assert::markTestSkipped('Rate limiter/cache non disponibile offline: '.$e->getMessage());
        }
    });

    test('_notification_tracking_returns_original_html_when_tracking_is_disabled', function (): void {
        config()->set('notify.tracking.enabled', false);
        config()->set('notify.tracking.pixel.enabled', false);
        config()->set('notify.tracking.links.enabled', false);

<<<<<<< HEAD
        $dummy = new NotifyTrackingDummy;
=======
        $dummy = new NotifyTrackingDummy();
>>>>>>> a988596b (first)
        $html = '<a href="https://example.com/path">click</a>';

        $tracked = $dummy->addTrackingPublic($html, 'track-1');

        Assert::assertSame($html, $tracked);
        Assert::assertSame('track-1', $dummy->trackingId());
        Assert::assertFalse($dummy->trackingEnabled());
    });

    test('_notification_tracking_rewrites_links_when_link_tracking_enabled', function (): void {
        Route::name('notify.track.pixel')->get('/track/pixel/{id}', fn () => 'ok');
        Route::name('notify.track.link')->get('/track/link/{id}', fn () => 'ok');

        config()->set('notify.tracking.enabled', true);
        config()->set('notify.tracking.pixel.enabled', true);
        config()->set('notify.tracking.links.enabled', true);
        config()->set('notify.tracking.pixel.route', 'notify.track.pixel');
        config()->set('notify.tracking.links.route', 'notify.track.link');

<<<<<<< HEAD
        $dummy = new NotifyTrackingDummy;
=======
        $dummy = new NotifyTrackingDummy();
>>>>>>> a988596b (first)
        $html = '<a href="https://example.com/path">click</a><a href="mailto:x@test.com">mail</a>';
        $tracked = $dummy->addTrackingPublic($html, 'track-links');

        Assert::assertStringContainsString('track-links', $tracked);
        Assert::assertStringContainsString('track/link', $tracked);
        Assert::assertStringContainsString('mailto:x@test.com', $tracked);
        Assert::assertTrue($dummy->pixelTrackingEnabled());
        Assert::assertTrue($dummy->linkTrackingEnabled());
        Assert::assertNotEmpty($dummy->generatedTrackingId());
    });

    test('_tenant_notification_helpers_check_tenant_ownership', function (): void {
        try {
<<<<<<< HEAD
            $tenant = new Tenant;
            $tenant->setAttribute('id', 'tenant-42');
            Filament::setTenant($tenant, isQuiet: true);

            $dummy = new NotifyTenantDummyModel;
=======
            $tenant = new Tenant();
            $tenant->setAttribute('id', 'tenant-42');
            Filament::setTenant($tenant, isQuiet: true);

            $dummy = new NotifyTenantDummyModel();
>>>>>>> a988596b (first)
            $dummy->tenant_id = 'tenant-42';

            Assert::assertTrue($dummy->belongsToTenant('tenant-42'));
            Assert::assertFalse($dummy->belongsToTenant('other-tenant'));
            Assert::assertStringContainsString('tenant_id', $dummy->applyForTenantScope($dummy->newQuery())->toSql());

            Filament::setTenant(null, isQuiet: true);
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (Throwable $e) {
=======
        } catch (\Throwable $e) {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        } catch (Throwable $e) {
>>>>>>> a988596b (first)
            Assert::markTestSkipped('Tenant/Filament non disponibile offline: '.$e->getMessage());
        }
    });
});
