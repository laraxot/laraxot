<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Modules\Tenant\Database\Factories\TenantFactory;
use Modules\Tenant\Models\Tenant;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use PHPUnit\Framework\Assert;
use Webmozart\Assert\Assert as WebmozartAssert;

use function Safe\json_decode;

/*
 * Bootstrap Pest — modulo Tenant.
 * Ogni file test dichiara uses(\Modules\Tenant\Tests\TestCase::class).
 * Vietato pest()->extend() e expect()->extend() qui (PHPStan method.internalClass).
 */

/**
 * @param  array<array-key, mixed>  $rows
 * @return array<string, mixed>
 */
function sushiRowById(array $rows, int|string $id): array
{
    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $rowId = $row['id'] ?? null;
        if ($rowId === $id || (is_numeric($rowId) && SafeIntCastAction::cast($rowId) === SafeIntCastAction::cast($id))) {
            /** @var array<string, mixed> $row */
            return $row;
        }
    }

    Assert::fail(sprintf('Row with id [%s] not found.', SafeStringCastAction::cast($id)));
}

/**
 * @return array<string, mixed>
 */
function assertTenantArray(mixed $value): array
{
    Assert::assertIsArray($value);

    /** @var array<string, mixed> $value */
    return $value;
}

/**
 * @param  class-string<Throwable>  $exceptionClass
 */
function assertTenantThrows(callable $callback, string $exceptionClass, ?string $messageContains = null): void
{
    try {
        $callback();
    } catch (Throwable $exception) {
        Assert::assertInstanceOf($exceptionClass, $exception);
        if ($messageContains !== null) {
            Assert::assertStringContainsString($messageContains, $exception->getMessage());
        }

        return;
    }

    Assert::fail(sprintf('Expected exception %s was not thrown.', $exceptionClass));
}

if (! function_exists('assertFreshModel')) {
    /**
     * @template T of Model
     *
     * @param  T  $model
     * @param  class-string<T>  $class
     * @return T
     */
    function assertFreshModel(Model $model, string $class): Model
    {
        $fresh = $model->fresh();
        Assert::assertInstanceOf($class, $fresh);

        return $fresh;
    }
}

/** @param array<string, mixed> $attributes */
function createTenant(array $attributes = []): Tenant
{
    /** @var TenantFactory $factory */
    $factory = Tenant::factory();
    $tenant = $factory->create($attributes);
    WebmozartAssert::isInstanceOf($tenant, Tenant::class);

    return $tenant;
}

/** @param array<string, mixed> $attributes */
function makeTenant(array $attributes = []): Tenant
{
    /** @var TenantFactory $factory */
    $factory = Tenant::factory();
    $tenant = $factory->make($attributes);
    WebmozartAssert::isInstanceOf($tenant, Tenant::class);

    return $tenant;
}

/**
 * @return array<int, array<string, mixed>>
 */
function decodeTenantJsonFile(string $path): array
{
    $content = File::get($path);
    $decoded = json_decode($content, true);
    Assert::assertIsArray($decoded);

    /** @var array<int, array<string, mixed>> $decoded */
    return $decoded;
}
