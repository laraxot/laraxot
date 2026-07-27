<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Actions\File;

use Illuminate\Support\Facades\File;
use Modules\Xot\Actions\File\AssetAction;
use Modules\Xot\Actions\File\AssetPathAction;
use Modules\Xot\Actions\File\FixPathAction;
use Modules\Xot\Actions\File\GetModulePathAction;
use Modules\Xot\Tests\TestCase;
use Nwidart\Modules\Facades\Module;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('handles absolute urls in AssetAction', function (): void {
    $action = app(AssetAction::class);
    $url = 'https://example.com/asset.js';
    Assert::assertSame($url, $action->execute($url));
});

it('returns path if asset already exists in public folder', function (): void {
    $path = 'css/app.css';

    // Spy on File facade to simulate existing file
    File::partialMock()->allows([
        'exists' => true,
    ]);

    $action = app(AssetAction::class);
    Assert::assertSame($path, $action->execute($path));
});

it('resolves module assets correctly in AssetAction', function (): void {
    $path = 'Xot::css/style.css';
    $modulePath = '/var/www/Modules/Xot';
    $from = $modulePath.'/resources/css/style.css';
    $to = public_path('assets/Xot/css/style.css');

    // Replace GetModulePathAction with a spy
    $getModulePathAction = new class($modulePath) extends GetModulePathAction {
        public function __construct(private string $modulePath)
        {
        }

        public function execute(string $module): string
        {
            return $this->modulePath;
        }
    };

    app()->instance(GetModulePathAction::class, $getModulePathAction);

    // Replace FixPathAction with a spy (identity function)
    $fixPathAction = new class extends FixPathAction {
        public function execute(string $path): string
        {
            return $path;
        }
    };

    app()->instance(FixPathAction::class, $fixPathAction);

    // Spy on File facade for all file operations
    File::partialMock()->allows([
        'exists' => function (string $checkPath) use ($path, $from, $to): bool {
            return in_array($checkPath, [
                public_path($path),
                $from,
                $to,
                dirname($to),
            ], true) && $checkPath !== public_path($path);
        },
        'copy' => true,
    ]);

    $action = app(AssetAction::class);
    $result = $action->execute($path);

    Assert::assertStringContainsString('assets/Xot/css/style.css', $result);
});

it('calculates asset path correctly in AssetPathAction', function (): void {
    // Spy on Module facade
    Module::partialMock()->allows([
        'getModulePath' => function (string $module): string {
            return 'User' === $module ? '/path/to/User/' : '';
        },
    ]);

    $action = app(AssetPathAction::class);
    $result = $action->execute('User::js/app.js');

    Assert::assertSame('/path/to/User/resources/js/app.js', $result);
});
