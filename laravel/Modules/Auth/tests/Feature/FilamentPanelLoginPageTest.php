<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);


it('shows the legal acceptance checkbox on operator login', function (): void {
    $this->get('/operator/login')
        ->assertOk()
        ->assertSee('accept_legal', escape: false)
        ->assertSee('Privacy Policy')
        ->assertSee('/termini-e-condizioni', escape: false)
        ->assertDontSee('Ricordami');
});

it('shows the legal acceptance checkbox on admin login', function (): void {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('accept_legal', escape: false)
        ->assertSee('Privacy Policy')
        ->assertSee('/termini-e-condizioni', escape: false)
        ->assertDontSee('Ricordami');
});
