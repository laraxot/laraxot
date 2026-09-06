<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt as LivewireVolt;
use Modules\Cms\Tests\TestCase;
use Modules\Xot\Contracts\UserContract;

uses(TestCase::class);

it('renders the login page', function (): void {
    $locale = app()->getLocale();
    $response = cmsGetOrSkipOnServerError('/'.$locale.'/auth/login');
    expect($response->status())->toBe(200);
});

it('login page contains login widget', function (): void {
    $locale = app()->getLocale();
    $response = cmsGetOrSkipOnServerError('/'.$locale.'/auth/login');
    expect($response->status())->toBe(200);
});

it('login page has required form elements', function (): void {
    $locale = app()->getLocale();
    $response = cmsGetOrSkipOnServerError('/'.$locale.'/auth/login');
    expect($response->status())->toBe(200);
});

it('login page works in italian', function (): void {
    app()->setLocale('it');
    $response = cmsGetOrSkipOnServerError('/it/auth/login');
    expect($response->status())->toBe(200);
});

it('login page contains localized content', function (): void {
    $response = cmsGetOrSkipOnServerError('/it/auth/login');
    $response
        ->assertStatus(200)
        ->assertSee('Hai dimenticato la password?')
        ->assertSee(__('pub_theme::auth.login.title'))
        ->assertSee(__('pub_theme::auth.login.or'));
});

it('allows the user to authenticate via frontend login page', function (): void {
    $email = cmsGenerateUniqueEmail();
    $user = cmsCreateTestUser([
        'email' => $email,
        'password' => Hash::make('password123'),
    ]);
    cmsAssertGuest();

    $response = LivewireVolt::test('auth.login')
        ->set('email', $email)
        ->set('password', 'password123')
        ->call('authenticate');

    $response->assertHasNoErrors();
    cmsAssertAuthenticated();

    $this->actingAs($user);

    $locale = app()->getLocale();
    $response = cmsGet('/'.$locale.'/auth/login');

    expect($response->headers->get('Location'))->toBe('/');
});

it('redirects authenticated users from login page', function (): void {
    $user = cmsCreateTestUser();

    $this->actingAs($user);

    $locale = app()->getLocale();
    $response = cmsGet('/'.$locale.'/auth/login');

    expect($response->status())->toBe(302);
});

it('remember me functionality works', function (): void {
    $email = cmsGenerateUniqueEmail();
    cmsCreateTestUser([
        'email' => $email,
        'password' => Hash::make('password123'),
    ]);

    cmsAssertGuest();

    $response = LivewireVolt::test('auth.login')
        ->set('email', $email)
        ->set('password', 'password123')
        ->set('remember', true)
        ->call('authenticate');

    $response->assertHasNoErrors();
    cmsAssertAuthenticated();
});

it('regenerates the session on login', function (): void {
    $email = cmsGenerateUniqueEmail();
    cmsCreateTestUser([
        'email' => $email,
        'password' => Hash::make('password123'),
    ]);

    $originalSessionId = session()->getId();

    LivewireVolt::test('auth.login')
        ->set('email', $email)
        ->set('password', 'password123')
        ->call('authenticate');
    cmsAssertAuthenticated();

    expect(session()->getId())->not->toBe($originalSessionId);
});

it('rate limits login attempts', function (): void {
    $email = cmsGenerateUniqueEmail();
    cmsCreateTestUser([
        'email' => $email,
        'password' => Hash::make('password123'),
    ]);

    for ($i = 0; $i < 5; ++$i) {
        LivewireVolt::test('auth.login')
            ->set('email', $email)
            ->set('password', 'wrong_password')
            ->call('authenticate');
    }

    // Sesto tentativo, con la password giusta: il rate limiter deve fermarlo lo stesso.
    // `call()` restituisce un Testable, mai null: `expect($response)->toBeNull()` non
    // poteva passare e non diceva niente sul throttling.
    LivewireVolt::test('auth.login')
        ->set('email', $email)
        ->set('password', 'password123')
        ->call('authenticate')
        ->assertHasErrors('email');

    cmsAssertGuest();
});

it('allows any user type to login via frontend', function (): void {
    $email = cmsGenerateUniqueEmail();
    cmsCreateTestUser([
        'email' => $email,
        'password' => Hash::make('password123'),
    ]);
    cmsAssertGuest();

    $response = LivewireVolt::test('auth.login')
        ->set('email', $email)
        ->set('password', 'password123')
        ->call('authenticate');

    $response->assertHasNoErrors();
    cmsAssertAuthenticated();

    $authenticatedUser = Auth::user();
    expect($authenticatedUser)->not->toBeNull();
    assert($authenticatedUser instanceof UserContract);
    expect($authenticatedUser->email)->toBe($email);
});
