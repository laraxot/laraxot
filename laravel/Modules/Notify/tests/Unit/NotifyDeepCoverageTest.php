<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit;

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\ModuleDeepCoverage;

=======
use Modules\Notify\Tests\TestCase;
use Modules\Xot\Tests\ModuleDeepCoverage;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\ModuleDeepCoverage;

>>>>>>> a988596b (first)
/**
 * @return array{string, string} radice `app/` del modulo e namespace corrispondente
 */
/** @return list{string, string} */
function notifyDeepContext(): array
{
    return [dirname(__DIR__, 2).'/app', 'Modules\\Notify\\'];
}

describe('Notify deep coverage', function (): void {
    test('all actions execute method is invoked', function (): void {
        [$appRoot, $ns] = notifyDeepContext();
        ModuleDeepCoverage::testExecuteAllActions($appRoot, $ns);
    });

    test('all events are instantiable', function (): void {
        [$appRoot, $ns] = notifyDeepContext();
        ModuleDeepCoverage::testInstantiateAllEvents($appRoot, $ns);
    });

    test('all datas from or construct', function (): void {
        [$appRoot, $ns] = notifyDeepContext();
        ModuleDeepCoverage::testFromAllDatas($appRoot, $ns);
    });

    test('providers register without fatal', function (): void {
        [$appRoot, $ns] = notifyDeepContext();
        ModuleDeepCoverage::testRegisterAllProviders($appRoot, $ns);
    });

    test('filament columns and widgets instantiate', function (): void {
        [$appRoot, $ns] = notifyDeepContext();
        ModuleDeepCoverage::testInstantiateFilamentColumns($appRoot, $ns);
    });
});
