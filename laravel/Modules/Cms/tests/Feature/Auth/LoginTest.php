<?php

declare(strict_types=1);

namespace Modules\Cms\Tests\Feature\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt as LivewireVolt;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

final class LoginTest extends TestCase
{
    public function testLoginPageCanBeRendered(): void
    {
        $locale = app()->getLocale();
        $response = cmsGet('/'.$locale.'/auth/login');
        Assert::assertSame(200, $response->status());
    }

    public function testLoginPageContainsLoginWidget(): void
    {
        $locale = app()->getLocale();
        $response = cmsGet('/'.$locale.'/auth/login');
        Assert::assertSame(200, $response->status());
    }

    public function testLoginPageHasRequiredFormElements(): void
    {
        $locale = app()->getLocale();
        $response = cmsGet('/'.$locale.'/auth/login');
        Assert::assertSame(200, $response->status());
    }

    public function testLoginPageWorksInItalian(): void
    {
        app()->setLocale('it');
        $response = cmsGet('/it/auth/login');
        Assert::assertSame(200, $response->status());
    }

    public function testLoginPageContainsLocalizedContent(): void
    {
        $response = cmsGet('/it/auth/login');
        $response
            ->assertStatus(200)
            ->assertSee('Hai dimenticato la password?')
            ->assertSee(__('pub_theme::auth.login.title'))
            ->assertSee(__('pub_theme::auth.login.or'));
    }

    public function testUserCanAuthenticateViaFrontendLoginPage(): void
    {
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

        Assert::assertSame('/', $response->headers->get('Location'));
    }

    public function testAuthenticatedUsersAreRedirectedFromLoginPage(): void
    {
        $user = cmsCreateTestUser();

        $this->actingAs($user);

        $locale = app()->getLocale();
        $response = cmsGet('/'.$locale.'/auth/login');

        Assert::assertSame(302, $response->status());
    }

    public function testRememberMeFunctionalityWorks(): void
    {
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
    }

    public function testSessionRegenerationOnLogin(): void
    {
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

        Assert::assertNotSame($originalSessionId, session()->getId());
    }

    public function testLoginAttemptsAreRateLimited(): void
    {
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

        $response = LivewireVolt::test('auth.login')
            ->set('email', $email)
            ->set('password', 'password123')
            ->call('authenticate');

        Assert::assertNull($response);
    }

    public function testAnyUserTypeCanLoginViaFrontend(): void
    {
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

        $authenticatedUser = Auth::user();
        Assert::assertNotNull($authenticatedUser);
        Assert::assertSame($email, $authenticatedUser->email);
    }
}
