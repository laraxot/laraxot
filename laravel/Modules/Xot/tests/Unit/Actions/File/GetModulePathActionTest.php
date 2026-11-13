<?php

declare(strict_types=1);
use Illuminate\Support\Facades\File;
use Modules\Xot\Actions\File\GetModulePathAction;
use Modules\Xot\Tests\TestCase;
use Nwidart\Modules\Facades\Module;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('gets module path from facade correctly', function (): void {
    // Spy on Module facade
<<<<<<< HEAD
    // allows(['metodo' => closure]) restituisce la closure, non il suo risultato: serve andReturnUsing().
    Module::partialMock()->shouldReceive('getModulePath')->andReturnUsing(
        fn (string $module): string => $module === 'Xot' ? '/path/to/Xot/' : '',
    );
=======
    Module::partialMock()->allows([
        'getModulePath' => function (string $module): string {
            return $module === 'Xot' ? '/path/to/Xot/' : '';
        },
    ]);
>>>>>>> 77b1e2748 (first)

    $action = app(GetModulePathAction::class);
    $result = $action->execute('Xot');

    Assert::assertSame('/path/to/Xot/', $result);
});

it('gets module path from fallback correctly', function (): void {
    // We assume Modules directory exists in base_path
    $modulesPath = base_path('Modules');
    if (! File::exists($modulesPath)) {
        File::makeDirectory($modulesPath);
    }

    // Create a dummy module dir
    $dummyModule = $modulesPath.'/TestModule';
    if (! File::exists($dummyModule)) {
        File::makeDirectory($dummyModule);
    }

    // Spy on Module facade to throw exception, forcing fallback
<<<<<<< HEAD
    Module::partialMock()->shouldReceive('getModulePath')->andReturnUsing(
        static function (string $module): string {
            throw new Exception('Module not found: '.$module);
        },
    );

    $action = app(GetModulePathAction::class);
    try {
        // Case-insensitive search
        $result = $action->execute('testmodule');
    } finally {
        // Sempre, anche se il test fallisce: una Modules/TestModule rimasta e' una cartella spuria per tutto il repo.
        File::deleteDirectory($dummyModule);
    }

    Assert::assertSame($dummyModule, $result);
=======
    Module::partialMock()->allows([
        'getModulePath' => function (string $module): string {
            throw new Exception('Module not found');
        },
    ]);

    $action = app(GetModulePathAction::class);
    // Case-insensitive search
    $result = $action->execute('testmodule');

    Assert::assertSame($dummyModule, $result);
    File::deleteDirectory($dummyModule);
>>>>>>> 77b1e2748 (first)
});
