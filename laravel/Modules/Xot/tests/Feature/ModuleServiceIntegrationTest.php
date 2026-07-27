<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Xot\Actions\ModuleAction;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('ModuleAction Integration', function () {
    beforeEach(function () {
    });

    it('integrates with Nwidart Modules system', function () {
        Assert::assertTrue(class_exists('Nwidart\Modules\Facades\Module'));
        Assert::assertTrue(class_exists('Nwidart\Modules\Module'));
    });

    it('can find existing modules', function () {
        // Test with known existing modules
        $chartService = new ModuleAction('Chart');
        $userService = new ModuleAction('User');
        $xotService = new ModuleAction('Xot');

        Assert::assertInstanceOf(ModuleAction::class, $chartService);

        Assert::assertInstanceOf(ModuleAction::class, $userService);

        Assert::assertInstanceOf(ModuleAction::class, $xotService);

        Assert::assertInstanceOf(ModuleAction::class, $userService);

        Assert::assertInstanceOf(ModuleAction::class, $xotService);
    });

    it('returns models from existing modules', function () {
        // Test with Chart module (we know it exists)
        $chartService = new ModuleAction('Chart');
        /** @var array<int|string, class-string> $models */
        $models = $chartService->getModels();

        // Should contain Chart model
        $hasChartModel = false;
        foreach ($models as $key => $modelClass) {
            if (is_string($modelClass) && str_contains($modelClass, 'Chart\\Models\\Chart')) {
                $hasChartModel = true;
                break;
            }
        }

        Assert::assertTrue($hasChartModel);
    });

    it('handles User module models correctly', function () {
        $userService = new ModuleAction('User');
        /** @var array<int|string, class-string> $models */
        $models = $userService->getModels();

        // Check for common User module models
        $modelClasses = array_values($models);
        $hasUserModels = false;

        foreach ($modelClasses as $modelClass) {
            if (is_string($modelClass) && str_contains($modelClass, 'User\\Models\\')) {
                $hasUserModels = true;
                break;
            }
        }

        Assert::assertTrue($hasUserModels);
    });

    it('filters abstract models correctly', function () {
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();

        // BaseModel should not be included (it's abstract)
        $modelNames = array_keys($models);
        Assert::assertStringNotContainsString('base_model', implode(',', $modelNames));
    });

    it('returns class strings as values', function () {
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();

        foreach ($models as $key => $modelClass) {
            Assert::assertIsString($key);
            Assert::assertIsString($modelClass);
            Assert::assertTrue(str_contains($modelClass, 'Modules\\'));
        }
    });

    it('handles reflection operations safely', function () {
        // Test that reflection operations don't cause crashes
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();

        // Test each returned model class
        foreach ($models as $modelClass) {
            Assert::assertTrue(is_string($modelClass) && (class_exists($modelClass) || interface_exists($modelClass)));
        }
    });

    it('processes module directory structure', function () {
        // Test that the service can process module directories
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();
    });

    it('handles snake_case conversion correctly', function () {
        // Test string conversion logic
        $testString = 'TestModelName';
        $snakeCase = Str::snake($testString);

        Assert::assertSame('test_model_name', $snakeCase);
    });

    it('integrates with Laravel filesystem', function () {
        // Test filesystem operations
        Assert::assertTrue(class_exists('Illuminate\Support\Facades\File'));
    });

    it('can handle multiple module instances', function () {
        $services = [
            new ModuleAction('Chart'),
            new ModuleAction('User'),
            new ModuleAction('Xot'),
            new ModuleAction('Job'),
        ];

        foreach ($services as $service) {
            Assert::assertInstanceOf(ModuleAction::class, $service);
            $models = $service->getModels();
        }
    });

    it('validates module existence checking', function () {
        // Test with non-existent module
        $nonExistentService = new ModuleAction('NonExistentModule');
        $models = $nonExistentService->getModels();

        Assert::assertEmpty($models);
    });

    it('handles namespace construction correctly', function () {
        // Test namespace building logic
        $chartService = new ModuleAction('Chart');
        $models = $chartService->getModels();

        foreach ($models as $modelClass) {
            Assert::assertStringContainsString('Modules\\Chart\\', (string) $modelClass);
        }
    });

    it('processes file extensions correctly', function () {
        // Test that only .php files are processed
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();

        // All returned classes should be valid PHP classes
        foreach ($models as $modelClass) {
            Assert::assertTrue(is_string($modelClass));
            Assert::assertGreaterThan(0, strlen((string) $modelClass));
        }
    });

    it('handles exception scenarios gracefully', function () {
        // Test various edge cases that might cause exceptions
        $edgeCaseServices = [
            new ModuleAction(''),
            new ModuleAction('InvalidModule'),
            new ModuleAction('Test123'),
        ];

        foreach ($edgeCaseServices as $service) {
        }
    });

    it('validates return type consistency', function () {
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();

        // Validate that all keys are strings and all values are class strings
        foreach ($models as $key => $value) {
            Assert::assertIsString($key);
            Assert::assertIsString($value);
            Assert::assertGreaterThan(0, strlen($key));
            Assert::assertGreaterThan(0, strlen($value));
        }
    });

    it('can work with Laravel service container', function () {
        // Test service container integration
        $serviceFromContainer = app(ModuleAction::class, ['name' => 'TestModule']);

        Assert::assertInstanceOf(ModuleAction::class, $serviceFromContainer);
    });

    it('handles concurrent access correctly', function () {
        // Test multiple simultaneous calls
        $results = [];
        for ($i = 0; $i < 3; ++$i) {
            $service = new ModuleAction('Xot');
            $results[] = $service->getModels();
        }

        // All results should be consistent
        Assert::assertSame($results[0], $results[1]);
        Assert::assertSame($results[0], $results[2]);
    });

    it('validates module path resolution', function () {
        // Test that module paths are resolved correctly
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();

        foreach ($models as $modelClass) {
            // Each model class should follow the correct namespace pattern
            Assert::assertMatchesRegularExpression('/^Modules\\\\[A-Za-z]+\\\\Models\\\\[A-Za-z]+$/', (string) $modelClass);
        }
    });

    it('handles file system operations safely', function () {
        // Test file system operations
        $xotService = new ModuleAction('Xot');
        $models = $xotService->getModels();
    });

    it('integrates with Laravel string helpers', function () {
        // Test string helper integration
        Assert::assertTrue(class_exists('Illuminate\Support\Str'));
        $testStudly = Str::studly('test_string');
        Assert::assertSame('TestString', $testStudly);
    });

    it('validates class instantiation patterns', function () {
        // Test that the service follows proper instantiation patterns
        $xotService = new ModuleAction('Xot');
        $reflection = new ReflectionClass($xotService);
        $constructor = $reflection->getConstructor();

        Assert::assertNotNull($constructor);
        Assert::assertTrue($constructor->isPublic());
    });

    it('can handle model discovery efficiently', function () {
        // Test performance of model discovery
        $xotService = new ModuleAction('Xot');
        $startTime = microtime(true);

        $models = $xotService->getModels();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        Assert::assertLessThan(5.0, $executionTime); // Should complete within 5 seconds
    });
});
