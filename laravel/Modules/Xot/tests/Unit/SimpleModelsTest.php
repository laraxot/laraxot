<?php

declare(strict_types=1);
<<<<<<< HEAD
use Illuminate\Support\Facades\Artisan;
use Modules\Xot\Database\Factories\ModuleFactory;
=======
>>>>>>> laraxot/dev
use Modules\Xot\Models\Module;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

<<<<<<< HEAD
it('can create a test module', function () {
    $module = ModuleFactory::new()->createOne([
        'name' => 'TestModule',
        'enabled' => true,
    ]);

    Assert::assertInstanceOf(Module::class, $module);
    Assert::assertSame('TestModule', $module->name);
    Assert::assertTrue((bool) $module->enabled);
});

it('can run migrations', function () {
    Artisan::call('migrate', ['--env' => 'testing', '--force' => true]);
=======
// Module e' un registry Sushi di sola lettura: le righe vengono da nwidart (getRows()),
// non si creano con una factory. Lo schema Sushi non ha le colonne della ModuleFactory.
it('legge i moduli nwidart dal registry Sushi', function () {
    $module = Module::query()->where('name', 'Xot')->first();

    Assert::assertInstanceOf(Module::class, $module);
    Assert::assertSame('Xot', $module->name);
>>>>>>> laraxot/dev
});
