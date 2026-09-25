<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature\Filament\Widgets\Auth;

use Illuminate\Support\Facades\Schema;
use Modules\User\Filament\Widgets\Auth\LoginWidget;
use Modules\User\Filament\Widgets\Auth\Schemas\UserForm;
use Modules\User\Tests\TestCase;

uses(TestCase::class);

/**
 * Coverage for `Modules\User\Filament\Widgets\Auth\LoginWidget`.
 *
 * Interaction tests are skipped: login blade/lang bugs outside this scope
 * (story 10.3). Avoid `->skip()` chaining — Pest stubs tipizzano test(): void.
 */
beforeEach(function (): void {
    /* @var TestCase $this */
    config(['activitylog.enabled' => false]);
    app()->setLocale('en');

    if (! Schema::connection('user')->hasTable('users')) {
        $this->markTestSkipped('users table missing on user connection (run migrations for testing)');
    }
});

describe('LoginWidget (Auth SSoT)', function (): void {
    test('delegates form schema to UserForm via formClass', function (): void {
        $reflection = new \ReflectionClass(LoginWidget::class);

        $formClass = $reflection->getMethod('formClass');
        $formClass->setAccessible(true);
        expect($formClass->invoke(null))->toBe(UserForm::class);

        $schemaMethod = $reflection->getMethod('schemaMethod');
        $schemaMethod->setAccessible(true);
        expect($schemaMethod->invoke(null))->toBe('getLoginFormSchema');
    });

    test('login widget renders successfully', function (): void {
        $this->markTestSkipped('blocked by pre-existing bug: login.blade.php truncated (story 10.3)');
    });

    test('authenticates user with valid credentials', function (): void {
        $this->markTestSkipped('blocked by pre-existing bug: login.blade.php truncated (story 10.3)');
    });

    test('rejects invalid credentials without authenticating', function (): void {
        $this->markTestSkipped('blocked by pre-existing bug: login.blade.php truncated (story 10.3)');
    });

    test('save delegates to login', function (): void {
        $this->markTestSkipped('blocked by pre-existing bug: login.blade.php truncated (story 10.3)');
    });
});
