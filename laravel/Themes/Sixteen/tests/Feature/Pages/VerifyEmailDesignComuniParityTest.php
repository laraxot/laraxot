<?php

declare(strict_types=1);

use Modules\Fixcity\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

uses(TestCase::class);

it('renders the authenticated verification notice inside the Design Comuni shell', function (): void {
    $user = UserFactory::new()->createOne(['email_verified_at' => null]);

    $html = $this->actingAs($user)->get('/it/auth/verify')->assertOk()->getContent();

    expect($html)
        ->toContain('<header')
        ->toContain('<main')
        ->toContain('<footer')
        ->toContain('Verifica il tuo indirizzo email');
});

it('shows resend loading feedback while requesting a verification email', function (): void {
    $user = UserFactory::new()->createOne(['email_verified_at' => null]);

    $html = $this->actingAs($user)->get('/it/auth/verify')->assertOk()->getContent();

    expect($html)
        ->toContain('wire:loading.attr="disabled"')
        ->toContain('wire:loading.attr="aria-busy"')
        ->toContain('wire:target="resend"')
        ->toContain('invio in corso');
});

it('keeps the public auth pages inside the same Design Comuni shell', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    expect($html)
        ->toContain('<header')
        ->toContain('<main')
        ->toContain('<footer')
        ->toContain('data-dc')
        ->not->toContain('card shadow-sm');
})->with([
    '/it/auth/login',
    '/it/auth/register',
    '/it/auth/password/reset',
]);

it('renders password confirmation in the authenticated Design Comuni shell', function (): void {
    $user = UserFactory::new()->createOne();

    $html = $this->actingAs($user)->get('/it/auth/password/confirm')->assertOk()->getContent();

    expect($html)
        ->toContain('<header')
        ->toContain('<main')
        ->toContain('<footer')
        ->toContain('auth-password-confirm-heading')
        ->not->toContain('card shadow-sm');
});
