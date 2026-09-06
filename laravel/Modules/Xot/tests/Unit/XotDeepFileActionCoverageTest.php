<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Modules\Xot\Actions\File\FileAction;
use Modules\Xot\Tests\TestCase;

uses(TestCase::class)->group('no-xot-db');

test('view assets resolve from a registered namespace', function (): void {
    $directory = sys_get_temp_dir().'/xot-file-view-'.uniqid('', true);
    File::ensureDirectoryExists($directory.'/css');
    File::put($directory.'/css/app.css', 'body{}');
    View::addNamespace('xot-file-contract', $directory);

    $asset = FileAction::viewNamespaceToAsset('xot-file-contract::css/app.css');

    expect($asset)->toContain('css/app.css');
    File::deleteDirectory($directory);
});

test('file URLs remove one application-root slash without damaging protocol URLs', function (): void {
    expect(FileAction::getFileUrl('/plain.css'))->toBe('plain.css')
        ->and(FileAction::getFileUrl('//cdn.example.test/app.css'))->toBe('//cdn.example.test/app.css');
});

test('copy prepares the destination directory in console mode', function (): void {
    $directory = sys_get_temp_dir().'/xot-file-copy-'.uniqid('', true);
    File::ensureDirectoryExists($directory);
    $source = $directory.'/from.css';
    $destination = $directory.'/nested/to.css';
    File::put($source, 'body{}');

    FileAction::copy($source, $destination);

    expect(dirname($destination))->toBeDirectory()
        ->and(File::exists($destination))->toBeFalse();
    File::deleteDirectory($directory);
});
