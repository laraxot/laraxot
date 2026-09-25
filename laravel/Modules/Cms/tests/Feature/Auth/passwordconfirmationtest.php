<?php

declare(strict_types=1);

namespace Modules\Cms\Tests\Feature\Auth;

use Livewire\Volt\Volt as LivewireVolt;
use Illuminate\Support\Facades\Hash;
use Modules\Cms\Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class);

test('confirm password screen can be rendered', function () {
    $user = TestCase::pestCreateTestUser(['password' => Hash::make('password')]);

    actingAs($user);
    $lang = app()->getLocale();
    $response = $this->get('/'.$lang.'/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = TestCase::pestCreateTestUser(['password' => Hash::make('password')]);

    actingAs($user);

    $response = LivewireVolt::test('auth.confirm-password')->set('password', 'password')->call('confirmPassword');

    $response->assertHasNoErrors();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('password is not confirmed with invalid password', function () {
    $user = TestCase::pestCreateTestUser(['password' => Hash::make('password')]);

    actingAs($user);

    $response = LivewireVolt::test('auth.confirm-password')->set('password', 'wrong-password')->call('confirmPassword');

    $response->assertHasErrors(['password']);
});
