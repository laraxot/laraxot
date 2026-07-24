<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Filament\Auth\Http\Responses\Contracts\LoginResponse as FilamentLoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('shows the legacy login page for guests', function (): void {
    get('/login')
        ->assertOk()
        ->assertSee('min-h-screen bg-slate-50 text-slate-950 antialiased', escape: false)
        ->assertSee('border border-slate-200 bg-white', escape: false)
        ->assertSee('name="email"', escape: false)
        ->assertSee('name="accept_legal"', escape: false)
        ->assertDontSee('name="remember"', escape: false);
});

it('redirects admin users to the admin panel after login', function (): void {
    // Arrange
    $adminRole = Role::findOrCreate('admin');

    $user = User::factory()->create([
        'email' => 'admin@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole]);

    // Act + Assert
    post('/login', [
        'email' => $user->email,
        'password' => 'secret123',
        'accept_legal' => '1',
    ])->assertRedirect('/admin');
});

it('redirects administrator users to the admin panel after login', function (): void {
    $adminRole = Role::findOrCreate('administrator');

    $user = User::factory()->create([
        'email' => 'administrator@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole]);

    post('/login', [
        'email' => $user->email,
        'password' => 'secret123',
        'accept_legal' => '1',
    ])->assertRedirect('/admin');
});

it('redirects operator users to the operator panel after login', function (): void {
    // Arrange
    $operatorRole = Role::findOrCreate('operator');

    $user = User::factory()->create([
        'email' => 'operator@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$operatorRole]);

    // Act + Assert
    post('/login', [
        'email' => $user->email,
        'password' => 'secret123',
        'accept_legal' => '1',
    ])->assertRedirect('/operator');
});

it('redirects wts users to the wts panel after wts login', function (): void {
    $wtsRole = Role::findOrCreate('wts');

    $user = User::factory()->create([
        'email' => 'wts@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$wtsRole]);

    actingAs($user);

    session()->forget('url.intended');

    $response = app(FilamentLoginResponseContract::class)->toResponse(
        Request::create('/wts/login', 'GET'),
    );

    expect($response)->toBeInstanceOf(RedirectResponse::class);

    if (! $response instanceof RedirectResponse) {
        return;
    }

    expect($response->getTargetUrl())->toEndWith('/wts');
});

it('redirects timber users to the timber panel after timber login', function (): void {
    $timberRole = Role::findOrCreate('timber_admin');

    $user = User::factory()->create([
        'email' => 'timber@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$timberRole]);

    actingAs($user);

    session()->forget('url.intended');

    $response = app(FilamentLoginResponseContract::class)->toResponse(
        Request::create('/timber/login', 'GET'),
    );

    expect($response)->toBeInstanceOf(RedirectResponse::class);

    if (! $response instanceof RedirectResponse) {
        return;
    }

    expect($response->getTargetUrl())->toEndWith('/timber');
});

it('redirects dds users to the dds panel after dds login', function (): void {
    $ddsRole = Role::findOrCreate('dds_admin');

    $user = User::factory()->create([
        'email' => 'dds@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$ddsRole]);

    actingAs($user);

    session()->forget('url.intended');

    $response = app(FilamentLoginResponseContract::class)->toResponse(
        Request::create('/dds/login', 'GET'),
    );

    expect($response)->toBeInstanceOf(RedirectResponse::class);

    if (! $response instanceof RedirectResponse) {
        return;
    }

    expect($response->getTargetUrl())->toEndWith('/dds');
});

it('redirects the gerardo account to the admin dashboard after legacy login', function (): void {
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');
    $user = User::factory()->create([
        'email' => 'info@gsbuildingservice.it',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole, $operatorRole]);

    post('/login', [
        'email' => $user->email,
        'password' => 'secret123',
        'accept_legal' => '1',
    ])->assertRedirect('/admin');
});

it('rejects invalid credentials', function (): void {
    post('/login', [
        'email' => 'nope@test.local',
        'password' => 'wrong',
        'accept_legal' => '1',
    ])->assertSessionHasErrors('email');
});

it('requires accepting privacy and terms before login', function (): void {
    post('/login', [
        'email' => 'nope@test.local',
        'password' => 'wrong',
    ])->assertSessionHasErrors('accept_legal');
});
