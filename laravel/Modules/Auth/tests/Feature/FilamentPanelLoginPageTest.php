<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use function Pest\Laravel\get;

it('shows the legal acceptance checkbox on operator login', function (): void {
    get('/operator/login')
        ->assertOk()
        ->assertSee('accept_legal', escape: false)
        ->assertSee('Privacy Policy')
        ->assertSee('/termini-e-condizioni', escape: false)
        ->assertDontSee('Ricordami');
});

it('shows the legal acceptance checkbox on admin login', function (): void {
    get('/admin/login')
        ->assertOk()
        ->assertSee('accept_legal', escape: false)
        ->assertSee('Privacy Policy')
        ->assertSee('/termini-e-condizioni', escape: false)
        ->assertDontSee('Ricordami');
});
