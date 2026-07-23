<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/'.app()->getLocale());
})->name('home');

// Frontend e pagine auth: gestiti da Volt + Folio + Laraxot. Non aggiungere rotte né controller in web.php.
