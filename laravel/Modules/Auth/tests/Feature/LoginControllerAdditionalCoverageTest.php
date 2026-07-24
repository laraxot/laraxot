<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('redirects authenticated users away from /login', function (): void {
    $user = User::factory()->create();

    actingAs($user)->get('/login')->assertRedirect('/operator');
});

it('logs out users and redirects to /login', function (): void {
    $user = User::factory()->create();

    actingAs($user)->post('/logout')->assertRedirect('/login');
});

it('rejects login for users without roles (cannot access any panel)', function (): void {
    $user = User::factory()->create([
        'email' => 'norole@example.test',
        'password' => Hash::make('secret123'),
    ]);

    post('/login', [
        'email' => $user->email,
        'password' => 'secret123',
        'accept_legal' => '1',
    ])->assertRedirect('/')
        ->assertSessionHasErrors(['email']);
});
