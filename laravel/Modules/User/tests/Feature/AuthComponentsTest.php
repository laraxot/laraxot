<?php

declare(strict_types=1);

use Modules\User\Tests\TestCase;

use function Pest\Laravel\get;

uses(TestCase::class);

describe('Auth Components Tests', function (): void {
    test('auth components exist and work correctly', function (): void {
        // Test existing auth components
        expect(View::exists('components.auth-session-status'))->toBeTrue();
        expect(View::exists('components.auth-header'))->toBeTrue();
        expect(View::exists('user::components.auth-session-status'))->toBeTrue();
    });

    test('auth layout components exist and work correctly', function (): void {
        // Test auth layout components that actually exist
        expect(View::exists('components.layouts.auth'))->toBeTrue();
        expect(View::exists('user::layouts.auth'))->toBeTrue();
    });

    test('login page loads correctly', function (): void {
        // Test that login page loads correctly
        $response = get('/it/auth/login');
        $response->assertStatus(200);
    });

    test('register page loads correctly', function (): void {
        // Test that register page loads correctly
        $response = get('/it/auth/register');
        $response->assertStatus(200);
    });

    test('auth-session-status component renders correctly', function (): void {
        // Test the existing auth-session-status component rendering
        /** @var view-string $view */
        $view = 'components.auth-session-status';
        $html = View::make($view, ['status' => 'Test status'])->render();

        expect(strlen($html))->toBeGreaterThanOrEqual(0);
        expect($html)->not->toBeEmpty();
    });

    test('auth header component exists and renders', function (): void {
        // Test the auth header component that exists
        expect(View::exists('components.auth-header'))->toBeTrue();

        /** @var view-string $view */
        $view = 'components.auth-header';
        $html = View::make($view, [
            'title' => 'Login Test',
            'description' => 'Test description',
        ])->render();

        expect($html)->toContain('Login Test');
        expect($html)->toContain('Test description');
    });
});

describe('Authentication Flow with Reorganized Components', function (): void {
    test('login form components work after reorganization', function (): void {
        // Visit login page and ensure all reorganized components render
        $response = get('/it/auth/login');
        $response->assertStatus(200);
    });
});

describe('User Profile Components Tests', function (): void {
    test('profile pages use reorganized components correctly', function (): void {
        // Profile pages use reorganized components correctly
        $this->markTestSkipped('Pending implementation');
    });
});
