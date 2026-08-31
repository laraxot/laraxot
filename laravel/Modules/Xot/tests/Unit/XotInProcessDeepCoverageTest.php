<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Modules\Xot\Actions\File\FileAction;
use Modules\Xot\Tests\TestCase;

use function Safe\realpath;

uses(\Modules\Xot\Tests\TestCase::class)->group('no-xot-db');

test('view namespaces resolve an existing public asset', function (): void {
    $directory = sys_get_temp_dir().'/xot-view-contract-'.uniqid('', true);
    File::ensureDirectoryExists($directory.'/css');
    File::put($directory.'/css/app.css', 'body { color: black; }');
    View::addNamespace('xot-contract', $directory);

    $resolved = FileAction::viewNamespaceToAsset('xot-contract::css/app.css');

    expect($resolved)->toContain('css/app.css');
    File::deleteDirectory($directory);
});

test('module path resolution returns the real Xot directory', function (): void {
    $path = FileAction::getModulePath('Xot');

    expect($path)->toBeDirectory()
        ->and(realpath($path))->toBe(realpath(base_path('Modules/Xot')));
});
