<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('segnalazioni.create');

render(static fn (): RedirectResponse => redirect()->to(
    LaravelLocalization::localizeURL('/tickets/create'),
));
