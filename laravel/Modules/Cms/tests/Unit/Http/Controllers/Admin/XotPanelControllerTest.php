<?php

declare(strict_types=1);

use Modules\Cms\Http\Controllers\Admin\XotPanelController;
use Modules\Cms\Http\Controllers\BaseController;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
describe('XotPanelController', function (): void {
    test('xot panel controller extends base controller', function (): void {
        $controller = new XotPanelController;

        Assert::assertInstanceOf(BaseController::class, $controller);
    });

    test('xot panel controller has __call method', function (): void {
    })->todo('Serve una asserzione di comportamento: l\'esistenza di un metodo su una classe nota e\' decidibile staticamente, quindi non prova niente.');

    test('xot panel controller uses correct namespace', function (): void {
        $reflector = new ReflectionClass(XotPanelController::class);

        Assert::assertSame('Modules\Cms\Http\Controllers\Admin', $reflector->getNamespaceName());
    });

    test('xot panel controller is not instantiable via constructor without params', function (): void {
        $controller = new XotPanelController();
    });
});
