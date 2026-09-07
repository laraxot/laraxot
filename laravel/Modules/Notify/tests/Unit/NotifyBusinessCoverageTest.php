<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit;

use Mockery;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\ModuleBusinessCoverage;

=======
use Modules\Notify\Tests\TestCase;
use Modules\Xot\Tests\ModuleBusinessCoverage;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\ModuleBusinessCoverage;

>>>>>>> a988596b (first)
afterEach(function (): void {
    Mockery::close();
});

/**
 * @return array{string, string} radice `app/` del modulo e namespace corrispondente
 */
/** @return list{string, string} */
function notifyBusinessContext(): array
{
    return [dirname(__DIR__, 2).'/app', 'Modules\\Notify\\'];
}

describe('Notify business coverage', function (): void {
    test('all policies execute authorization methods', function (): void {
        [$appRoot, $ns] = notifyBusinessContext();
        ModuleBusinessCoverage::testAllPolicies($appRoot, $ns);
    });

    test('all models expose table and fillable', function (): void {
        [$appRoot, $ns] = notifyBusinessContext();
        ModuleBusinessCoverage::testAllModels($appRoot, $ns);
    });

    test('all actions are resolvable', function (): void {
        [$appRoot, $ns] = notifyBusinessContext();
        ModuleBusinessCoverage::testAllActions($appRoot, $ns);
    });

    test('all datas are loadable', function (): void {
        [$appRoot, $ns] = notifyBusinessContext();
        ModuleBusinessCoverage::testAllDatas($appRoot, $ns);
    });
});
