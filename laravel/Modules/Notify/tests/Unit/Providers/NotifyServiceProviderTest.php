<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Providers;

use Illuminate\Support\Facades\Mail;
use Modules\Notify\Providers\NotifyServiceProvider;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Tenant\Actions\Config\ResolveTenantConfigValueAction;

=======
use Modules\Notify\Tests\TestCase;
use Modules\Tenant\Actions\Config\ResolveTenantConfigValueAction;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
test('notify service provider boot sets fallback recipient when configured', function () {
    app()->instance(ResolveTenantConfigValueAction::class, new class
=======
use Modules\Tenant\Actions\Config\ResolveTenantConfigValueAction;

test('notify service provider boot sets fallback recipient when configured', function () {
    app()->instance(ResolveTenantConfigValueAction::class, new class()
>>>>>>> a988596b (first)
    {
        /**
         * @param  string|int|array<string, mixed>|null  $default
         * @return array<string, string>
         */
        public function execute(string $key, string|int|array|null $default = null): array
        {
            return ['fallback_to' => 'fallback@example.test'];
        }
    });

    Mail::shouldReceive('alwaysTo')->once()->with('fallback@example.test');

    $provider = new NotifyServiceProvider(app());
    $provider->boot();
});

test('notify service provider boot skips alwaysTo when fallback is missing', function () {
<<<<<<< HEAD
    app()->instance(ResolveTenantConfigValueAction::class, new class
=======
    app()->instance(ResolveTenantConfigValueAction::class, new class()
>>>>>>> a988596b (first)
    {
        /**
         * @param  string|int|array<string, mixed>|null  $default
         * @return array<empty, empty>
         */
        public function execute(string $key, string|int|array|null $default = null): array
        {
            return [];
        }
    });

    Mail::shouldReceive('alwaysTo')->never();

    $provider = new NotifyServiceProvider(app());
    $provider->boot();
});
