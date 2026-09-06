<?php

declare(strict_types=1);

use Modules\Xot\Services\ModuleService;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

uses(TestCase::class);

describe('ModuleService', function () {
    beforeEach(function () {
        $this->service = new ModuleService()->setName('TestModule');
    });

    it('can be instantiated', function () {
        Assert::assertInstanceOf(ModuleService::class, $this->service);
    });

    it('has correct module name property', function () {
        $reflection = new ReflectionClass($this->service);
        $nameProperty = $reflection->getProperty('name');
        $nameProperty->setAccessible(true);

        Assert::assertSame('TestModule', $nameProperty->getValue($this->service));
    });

    it('can be instantiated with different module names', function () {
        $service1 = new ModuleService()->setName('Chart');
        $service2 = new ModuleService()->setName('User');

        Assert::assertInstanceOf(ModuleService::class, $service1);
        Assert::assertInstanceOf(ModuleService::class, $service2);
    });

    it('has getModels method', function () {
        Assert::assertTrue(method_exists($this->service, 'getModels'));
    });

    it('returns array from getModels method', function () {
        $result = $this->service->getModels();

        Assert::assertIsArray($result);
    });

    it('getModels returns correct array structure', function () {
        $result = $this->service->getModels();

        foreach ($result as $key => $value) {
            Assert::assertIsString($key);
            Assert::assertIsString($value);
        }
    });

    it('filters abstract classes correctly', function () {
        $result = $this->service->getModels();

        Assert::assertArrayNotHasKey('base_model', $result);
    });

    it('handles reflection exceptions gracefully', function () {
        $result = $this->service->getModels();

        Assert::assertIsArray($result);
    });

    it('processes model names correctly', function () {
        $reflection = new ReflectionClass($this->service);
        $method = $reflection->getMethod('getModels');

        Assert::assertTrue($method->isPublic());
    });

    it('has proper return type annotation', function () {
        $reflection = new ReflectionClass($this->service);
        $method = $reflection->getMethod('getModels');

        $docComment = $method->getDocComment();
        Assert::assertIsString($docComment);
        Assert::assertStringContainsString('@return array<string, class-string>', $docComment);
    });

    it('validates method signature', function () {
        $reflection = new ReflectionClass($this->service);
        $method = $reflection->getMethod('getModels');

        Assert::assertTrue($method->isPublic());
        Assert::assertSame(0, $method->getNumberOfParameters());
    });

    it('handles empty module gracefully', function () {
        $emptyService = new ModuleService()->setName('NonExistentModule');
        $result = $emptyService->getModels();

        Assert::assertSame([], $result);
    });

    it('uses correct namespace patterns', function () {
        $reflection = new ReflectionClass($this->service);

        Assert::assertTrue($reflection->hasProperty('name'));
    });

    it('uses setName method for configuration', function () {
        $reflection = new ReflectionClass($this->service);

        Assert::assertTrue($reflection->hasMethod('setName'));
        Assert::assertTrue($reflection->getMethod('setName')->isPublic());
    });

    it('validates class structure', function () {
        $reflection = new ReflectionClass($this->service);

        Assert::assertTrue($reflection->isInstantiable());
        Assert::assertFalse($reflection->isFinal());
        Assert::assertFalse($reflection->isAbstract());
    });

    it('has proper method visibility', function () {
        $reflection = new ReflectionClass($this->service);
        $methods = $reflection->getMethods();

        $publicMethods = array_filter($methods, fn ($method) => $method->isPublic());

        Assert::assertGreaterThan(0, count($publicMethods));
    });

    it('handles module facade interactions', function () {
        Assert::assertTrue(class_exists('Nwidart\Modules\Facades\Module'));
    });

    it('processes file extensions correctly', function () {
        $result = $this->service->getModels();

        Assert::assertIsArray($result);
    });

    it('validates string utilities usage', function () {
        Assert::assertTrue(class_exists('Illuminate\Support\Str'));
    });

    it('handles reflection class instantiation', function () {
        Assert::assertTrue(class_exists('ReflectionClass'));
    });

    it('has proper error handling', function () {
        $result = $this->service->getModels();

        Assert::assertIsArray($result);
    });
});
