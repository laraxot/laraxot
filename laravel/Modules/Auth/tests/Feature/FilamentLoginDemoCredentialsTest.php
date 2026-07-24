<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use function Pest\Laravel\get;

it('does not show shared demo credentials on filament login when demo mode is enabled', function (): void {
    config()->set('app.demo_mode', true);

    get('/admin/login')
        ->assertOk()
        ->assertDontSee('Credenziali demo')
        ->assertDontSee('demo@cavalliniservice.com')
        ->assertDontSee('Demo123!')
        ->assertSee('accept_legal', escape: false)
        ->assertDontSee('Ricordami');
});

it('hides demo registration prompts on configured blocked registration hosts', function (): void {
    config()->set('app.demo_mode', true);
    config()->set('auth.registration.blocked_hosts', ['blocked-registration.example.test']);
    $host = 'blocked-registration.example.test';

    get('http://'.$host.'/admin/login')
        ->assertOk()
        ->assertDontSee('Credenziali demo')
        ->assertDontSee('demo@cavalliniservice.com')
        ->assertDontSee('Demo123!');

    get('http://'.$host.'/login')
        ->assertOk()
        ->assertDontSee('Credenziali demo')
        ->assertDontSee('demo@cavalliniservice.com')
        ->assertDontSee('Demo123!');
});
