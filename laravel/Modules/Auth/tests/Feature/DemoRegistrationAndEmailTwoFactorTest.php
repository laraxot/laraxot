<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\Actions\ApproveRegisteredUserAction;
use Modules\Auth\Notifications\EmailTwoFactorCodeNotification;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('shows approval based registration when demo mode is disabled', function (): void {
    config()->set('app.demo_mode', false);

    get('/register')
        ->assertOk()
        ->assertSee('Richiedi accesso');

    config()->set('app.demo_mode', true);

    get('/register')
        ->assertOk()
        ->assertSee('Crea account demo');
});

it('registers a production user as pending approval without roles or login', function (): void {
    Notification::fake();
    config()->set('app.demo_mode', false);
    config()->set('auth.email_2fa.enabled', true);

    post('/register', [
        'name' => 'Utente Da Approvare',
        'email' => 'pending@example.com',
        'password' => 'Password12345',
        'password_confirmation' => 'Password12345',
        'accept_legal' => '1',
    ])->assertRedirect(route('account.registration.pending'));

    $user = User::query()->where('email', 'pending@example.com')->firstOrFail();

    expect($user->isApproved())->toBeFalse()
        ->and($user->roles()->count())->toBe(0)
        ->and(auth()->check())->toBeFalse();

    Notification::assertNothingSent();
});

it('blocks login for users pending admin approval', function (): void {
    $user = User::factory()->pendingApproval()->create([
        'email' => 'blocked@example.com',
        'password' => Hash::make('Password12345'),
    ]);
    $user->syncRoles([Role::findOrCreate('operator')]);

    post('/login', [
        'email' => 'blocked@example.com',
        'password' => 'Password12345',
        'accept_legal' => '1',
    ])->assertSessionHasErrors('email');
});

it('approves a pending user as operator', function (): void {
    $approver = User::factory()->create();
    $pending = User::factory()->pendingApproval()->create();

    app(ApproveRegisteredUserAction::class)->execute($pending, $approver);

    $pending->refresh();

    expect($pending->isApproved())->toBeTrue()
        ->and((int) $pending->approved_by_user_id)->toBe((int) $approver->id)
        ->and($pending->hasRole('operator'))->toBeTrue();
});

it('registers a demo user and sends the email two factor code', function (): void {
    Notification::fake();
    config()->set('app.demo_mode', true);
    config()->set('auth.email_2fa.enabled', true);

    Role::findOrCreate('admin');
    Role::findOrCreate('operator');

    post('/register', [
        'name' => 'Nuovo Demo',
        'email' => 'nuovo.demo@example.com',
        'password' => 'Password12345',
        'password_confirmation' => 'Password12345',
        'accept_legal' => '1',
    ])->assertRedirect(route('email-two-factor.challenge'));

    $user = User::query()->where('email', 'nuovo.demo@example.com')->firstOrFail();

    expect($user->hasRole('admin'))->toBeTrue()
        ->and($user->hasRole('operator'))->toBeTrue();

    Notification::assertSentTo($user, EmailTwoFactorCodeNotification::class);
});

it('requires email two factor verification before accessing a panel', function (): void {
    Notification::fake();
    config()->set('auth.email_2fa.enabled', true);

    $role = Role::findOrCreate('operator');
    $user = User::factory()->create();
    $user->syncRoles([$role]);

    actingAs($user);

    get('/operator')
        ->assertRedirect(route('email-two-factor.challenge'));

    Notification::assertSentTo($user, EmailTwoFactorCodeNotification::class);
});

it('accepts a valid email two factor code', function (): void {
    config()->set('auth.email_2fa.enabled', true);

    $user = User::factory()->create();

    actingAs($user)
        ->withSession([
            'auth.email_2fa.user_id' => $user->id,
            'auth.email_2fa.code_hash' => Hash::make('123456'),
            'auth.email_2fa.expires_at' => now()->addMinutes(10)->timestamp,
            'auth.email_2fa.intended_url' => url('/operator'),
        ]);

    post('/email-two-factor', [
        'code' => '123456',
    ])->assertRedirect('/operator');

    expect(session('auth.email_2fa.verified_user_id'))->toBe($user->id);
});
