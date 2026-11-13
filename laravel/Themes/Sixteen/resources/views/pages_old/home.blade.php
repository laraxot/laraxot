<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

/**
 * /{locale}/home → home SSoT Folio (`index.blade.php` su /{locale}).
 * Niente markup duplicato, niente HomeController, niente $loginUrl.
 */
name('home.page');

render(static fn (): RedirectResponse => redirect()->to(
    LaravelLocalization::localizeURL('/'),
));
