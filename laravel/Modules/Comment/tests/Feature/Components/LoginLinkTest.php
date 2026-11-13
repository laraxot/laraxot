<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

uses(\Modules\Comment\Tests\TestCase::class);

/*
 * CMT-022: «Accedi per commentare» deve riportare l'ospite sulla pagina che stava leggendo. Con il link nudo al login, dopo
 * l'accesso si finiva sulla dashboard: l'ospite perdeva il punto in cui voleva scrivere.
 */

beforeEach(function (): void {
    if (! Route::has('login')) {
        Route::get('/it/auth/login', static fn (): string => '')->name('login');
    }
});

it('carries the page being read, query string included, into the login link', function (): void {
    app()->instance('request', Request::create('/it/blog/una-guida?page=2'));

    $html = Blade::render('<x-comment::login-link class="underline" />');

    expect($html)
        ->toContain('redirect=%2Fit%2Fblog%2Funa-guida%3Fpage%3D2')
        ->toContain('class="underline"')
        ->toContain(__('comment::txt.log-in-for-comment'));
});

it('lets the caller choose the page to come back to and the text of the link', function (): void {
    $html = Blade::render('<x-comment::login-link redirect="/it/tickets/35">Entra</x-comment::login-link>');

    expect($html)
        ->toContain('redirect=%2Fit%2Ftickets%2F35')
        ->toContain('>Entra</a>');
});
