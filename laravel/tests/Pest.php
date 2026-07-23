<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
| ATTENZIONE:
| Questo file viene caricato anche da strumenti come PHPStan. Quando non stiamo
| eseguendo i test tramite Pest, la funzione globale `pest()` non esiste e
| chiamarla genererebbe l'eccezione `InvalidPestCommand`. Per evitare che PHPStan
| fallisca per questo motivo, se `pest()` non esiste usciamo subito dal file.
*/

if (! function_exists('pest')) {
    return;
}

pest()->extend(Tests\TestCase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Module Pest.php helpers (Pest 4 compat)
|--------------------------------------------------------------------------
|
| Pest 4's --test-directory defaults to "tests" and only auto-loads that
| single directory's Pest.php. Each Modules/{Module}/tests/Pest.php lives
| outside that tree, so its global helper functions stop being discovered.
| Require them explicitly here to restore the previous auto-discovery.
| All module Pest.php helpers must guard declarations with
| function_exists() to avoid redeclaration collisions across modules.
*/

foreach (glob(__DIR__.'/../Modules/*/tests/Pest.php') ?: [] as $modulePestFile) {
    require_once $modulePestFile;
}

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
