<?php

declare(strict_types=1);

<<<<<<< HEAD
use Modules\Xot\Filament\Resources\XotBaseResource;
=======
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\glob;
use function Safe\preg_match;

uses(TestCase::class);

/*
 * Da quando `XotBaseListRecords` non usa piu' `HasXotTable`, la tabella di una list page
 * la costruisce `XotBaseResource::table()` attraverso `getTableClass()`, che alza
<<<<<<< HEAD
 * `LogicException` se la classe non esiste. Le Resource che dichiarano ancora un
 * proprio `table()` sono nel percorso di migrazione legacy e non consumano questo
 * resolver.
=======
 * `LogicException` se la classe non esiste: una Resource senza `*Table` non degrada a
 * tabella vuota, va in errore a runtime.
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
 *
 * Questo test tiene il contratto: nessuna list page concreta puo' restare scoperta.
 */

test('ogni list page concreta risolve la sua Table class', function (): void {
    $senzaTable = [];

    /** @var list<string> $files */
    $files = glob(base_path('Modules/*/app/Filament/Resources/*/Pages/*.php')) ?: [];

    foreach ($files as $file) {
        $src = file_get_contents($file);

        if (! preg_match('/extends\s+\w*ListRecords/', $src)) {
            continue;
        }
        if (preg_match('/^abstract class/m', $src)) {
            continue;
        }
        if (! preg_match('/namespace\s+([^;]+);/', $src, $ns)) {
            continue;
        }
        if (! preg_match('/^(?:final\s+)?class\s+(\w+)/m', $src, $className)) {
            continue;
        }

        $page = trim($ns[1]).'\\'.$className[1];
        if (! class_exists($page)) {
            continue;
        }

        try {
<<<<<<< HEAD
            /** @var class-string<XotBaseResource> $resourceClass */
            $resourceClass = $page::getResource();

            if (! is_subclass_of($resourceClass, XotBaseResource::class)) {
                continue;
            }

            $tableMethod = new ReflectionMethod($resourceClass, 'table');
            if ($tableMethod->getDeclaringClass()->getName() !== XotBaseResource::class) {
                continue;
            }

            $resourceClass::getTableClass();
        } catch (Throwable $e) {
=======
            /** @var class-string<\Modules\Xot\Filament\Resources\XotBaseResource> $resourceClass */
            $resourceClass = $page::getResource();
            $resourceClass::getTableClass();
        } catch (\Throwable $e) {
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
            $senzaTable[] = $page.' — '.$e->getMessage();
        }
    }

    Assert::assertSame([], $senzaTable, "List page senza Table class:\n".implode("\n", $senzaTable));
});
