<?php

declare(strict_types=1);

namespace Modules\Tenant\Tests\Unit\Actions;

use Modules\Tenant\Actions\GetTenantNameAction;
use Modules\Tenant\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\mkdir;
use function Safe\rmdir;

uses(\Modules\Tenant\Tests\TestCase::class);

/** @var list<string> $createdConfigPaths */
$createdConfigPaths = [];

beforeEach(function (): void {
    config(['app.url' => 'http://localhost']);
});

afterEach(function () use (&$createdConfigPaths): void {
    TestCase::setServerNameForTenantTest(null);

    foreach ($createdConfigPaths as $path) {
        if (is_dir($path)) {
            rmdir($path);
        }
    }
    $createdConfigPaths = [];
});

function ensureTenantConfigDir(string $relativePath): string
{
    /** @var list<string> $createdConfigPaths */
    global $createdConfigPaths;

    $path = config_path(str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
    if (! is_dir($path)) {
        mkdir($path, 0755, true);
        $createdConfigPaths[] = $path;
    }

    return $path;
}

test('get tenant name action returns correct tenant name from server name', function (): void {
    ensureTenantConfigDir('com/example/myapp');
    TestCase::setServerNameForTenantTest('myapp.example.com');

    $result = app(GetTenantNameAction::class)->execute();

    Assert::assertSame('com/example/myapp', $result);
});

test('get tenant name action handles www prefix correctly', function (): void {
    ensureTenantConfigDir('com/example/myapp');
    TestCase::setServerNameForTenantTest('www.myapp.example.com');

    $result = app(GetTenantNameAction::class)->execute();

    Assert::assertSame('com/example/myapp', $result);
});

test('get tenant name action falls back to default when server name is localhost', function (): void {
    TestCase::setServerNameForTenantTest('127.0.0.1');

    $result = app(GetTenantNameAction::class)->execute();

    Assert::assertSame('localhost', $result);
});

test('get tenant name action uses app url config when server name not set', function (): void {
    ensureTenantConfigDir('test/myapp');
    TestCase::setServerNameForTenantTest(null);
    config(['app.url' => 'https://myapp.test']);

    $result = app(GetTenantNameAction::class)->execute();

    Assert::assertSame('test/myapp', $result);
});

test('get tenant name action handles empty app url config', function (): void {
    TestCase::setServerNameForTenantTest(null);
    config(['app.url' => '']);

    $result = app(GetTenantNameAction::class)->execute();

    Assert::assertContains($result, ['', 'localhost']);
});
