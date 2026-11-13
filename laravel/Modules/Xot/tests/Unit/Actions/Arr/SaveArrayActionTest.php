<?php

declare(strict_types=1);
use Illuminate\Support\Facades\File;
use Modules\Xot\Actions\Arr\SaveArrayAction;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\json_decode;
<<<<<<< HEAD

uses(TestCase::class);

// $this dentro le closure Pest non e' Modules\Xot\Tests\TestCase: la cartella temporanea
// vive in una variabile locale condivisa per riferimento (stessa scelta di SavePhpArrayActionTest).
$tempDir = '';

beforeEach(function () use (&$tempDir): void {
    $tempDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'save_array_action_'.uniqid('', true);
    File::ensureDirectoryExists($tempDir);
});

afterEach(function () use (&$tempDir): void {
    File::deleteDirectory($tempDir);
});

test('save array action saves as php by default', function () use (&$tempDir): void {
    $data = ['foo' => 'bar'];
    $filename = $tempDir.'/data.php';

    $result = app(SaveArrayAction::class)->execute($data, $filename);

    Assert::assertTrue($result);
    Assert::assertStringStartsWith('<?php', File::get($filename));
    Assert::assertSame($data, include $filename);
});

test('save array action saves as json', function () use (&$tempDir): void {
    $data = ['foo' => 'bar'];
    $filename = $tempDir.'/data.json';

    $result = app(SaveArrayAction::class)->execute($data, $filename, 'json');

    Assert::assertTrue($result);
    Assert::assertSame($data, json_decode(File::get($filename), true));
});

test('save array action throws exception for unsupported format', function () use (&$tempDir): void {
    $filename = $tempDir.'/data.xml';
    $action = app(SaveArrayAction::class);

    expect(fn (): bool => $action->execute(['foo' => 'bar'], $filename, 'xml'))
        ->toThrow(InvalidArgumentException::class, 'Formato non supportato: xml');
=======
use function Safe\tempnam;

uses(TestCase::class);

test('save array action saves as php by default', function () {
    $data = ['foo' => 'bar'];
    $filename = tempnam(sys_get_temp_dir(), 'test_save_array_php').'.php';

    $action = app(SaveArrayAction::class);
    $result = $action->execute($data, $filename);

    Assert::assertTrue($result);
    $savedData = include $filename;
    Assert::assertSame($data, $savedData);
    File::delete($filename);
});

test('save array action saves as json', function () {
    $data = ['foo' => 'bar'];
    $filename = tempnam(sys_get_temp_dir(), 'test_save_array_json').'.json';

    $action = app(SaveArrayAction::class);
    $result = $action->execute($data, $filename, 'json');

    Assert::assertTrue($result);
    $savedData = json_decode(File::get($filename), true);
    Assert::assertSame($data, $savedData);
    File::delete($filename);
});

test('save array action throws exception for unsupported format', function () {
    $action = app(SaveArrayAction::class);
>>>>>>> 77b1e2748 (first)
});
