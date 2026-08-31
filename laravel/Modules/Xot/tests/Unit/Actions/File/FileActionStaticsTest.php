<?php

declare(strict_types=1);

use Modules\Xot\Actions\File\FileAction;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\json_decode;
use function Safe\mkdir;
use function Safe\rmdir;
use function Safe\scandir;
use function Safe\unlink;

uses(TestCase::class);

// $this dentro le closure Pest e' tipizzato da Pest come TestCall, non come
// Modules\Xot\Tests\TestCase: il custom rrmdir() non e' risolvibile via $this,
// quindi si usa una closure locale auto-referenziale.
$rrmdir = function (string $dir) use (&$rrmdir): void {
    if (! is_dir($dir)) {
        return;
    }

    /** @var array<int, string> $files */
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $path = $dir.'/'.$file;
        if (is_dir($path) && ! is_link($path)) {
            $rrmdir($path);

            continue;
        }

        unlink($path);
    }

    rmdir($dir);
};

test('fixPath normalizza entrambi i separatori sul separatore di sistema', function (): void {
    $expected = implode(DIRECTORY_SEPARATOR, ['a', 'b', 'c', 'd']);

    Assert::assertSame($expected, FileAction::fixPath('a/b\\c/d'));
    Assert::assertSame('', FileAction::fixPath(''));
});

test('getNiceFileSize usa i prefissi binari per default', function (): void {
    Assert::assertSame('0 B', FileAction::getNiceFileSize(0));
    Assert::assertSame('1023 B', FileAction::getNiceFileSize(1023));
    Assert::assertSame('1 KiB', FileAction::getNiceFileSize(1024));
    Assert::assertSame('1.5 KiB', FileAction::getNiceFileSize(1536));
    Assert::assertSame('1 MiB', FileAction::getNiceFileSize(1024 ** 2));
});

test('getNiceFileSize usa i prefissi decimali quando binaryPrefix e falso', function (): void {
    Assert::assertSame('0 B', FileAction::getNiceFileSize(0, false));
    Assert::assertSame('1 KB', FileAction::getNiceFileSize(1000, false));
    Assert::assertSame('1.5 MB', FileAction::getNiceFileSize(1_500_000, false));
});

test('getFileNameByClassName risolve il file della classe e null se non esiste', function (): void {
    $file = FileAction::getFileNameByClassName(FileAction::class);

    Assert::assertIsString($file);
    Assert::assertStringEndsWith('Actions'.DIRECTORY_SEPARATOR.'File'.DIRECTORY_SEPARATOR.'FileAction.php', $file);
});

test('url2Path riporta un asset pubblico sul filesystem', function (): void {
    Assert::assertSame(public_path('css/app.css'), FileAction::url2Path(asset('css/app.css')));
});

test('getConfigKey isola la chiave dopo namespace e gruppo', function (): void {
    Assert::assertSame('key.sub', FileAction::getConfigKey('xot::config.key.sub'));
    Assert::assertSame('single', FileAction::getConfigKey('xot::config.single'));
});

test('getFileUrl toglie un solo slash iniziale e preserva quello di protocollo', function (): void {
    Assert::assertSame('foo/bar.css', FileAction::getFileUrl('/foo/bar.css'));
    Assert::assertSame('//cdn.example.com/x.css', FileAction::getFileUrl('//cdn.example.com/x.css'));
    Assert::assertSame('foo/bar.css', FileAction::getFileUrl('foo/bar.css'));
});

test('getModulePath restituisce il percorso reale del modulo con slash finale', function (): void {
    $path = FileAction::getModulePath('Xot');

    Assert::assertStringEndsWith('/', $path);
    Assert::assertDirectoryExists($path);
    Assert::assertFileExists($path.'module.json');
});

test('getModulePath compone un percorso studly per un modulo inesistente', function (): void {
    $path = FileAction::getModulePath('modulo_inesistente');

    Assert::assertStringEndsWith('/ModuloInesistente/', $path);
    Assert::assertDirectoryDoesNotExist($path);
});

test('assetPath compone il percorso della risorsa dentro il modulo', function (): void {
    $expected = FileAction::getModulePath('Xot').'Resources/css/style.css';

    Assert::assertSame($expected, FileAction::assetPath('Xot::css/style.css'));
});

test('getViewNameSpacePath risolve i namespace di tema e null per gli sconosciuti', function (): void {
    $xot = XotData::make();
    Assert::assertSame(base_path('Themes/'.$xot->pub_theme), FileAction::getViewNameSpacePath('pub_theme'));
    Assert::assertSame(base_path('Themes/'.$xot->adm_theme), FileAction::getViewNameSpacePath('adm_theme'));
    Assert::assertNull(FileAction::getViewNameSpacePath('namespace_che_non_esiste'));
});

test('viewNamespaceToDir traduce i punti in sottocartelle', function (): void {
    $expected = FileAction::fixPath(base_path('Themes/'.XotData::make()->pub_theme).'/errors/500');

    Assert::assertSame($expected, FileAction::viewNamespaceToDir('pub_theme::errors.500'));
});

test('viewPath e configPath compongono i percorsi di vista e configurazione', function (): void {
    Assert::assertSame(
        FileAction::fixPath(base_path('Themes/'.XotData::make()->pub_theme).'/errors/500.blade.php'),
        FileAction::viewPath('pub_theme::errors.500'),
    );

    Assert::assertSame(
        FileAction::fixPath(base_path('Themes/'.XotData::make()->pub_theme).'/../../../Config/xra.php'),
        FileAction::configPath('pub_theme::xra.key'),
    );
});

test('viewNamespaceToAsset segnala il namespace non risolvibile invece di lanciare', function (): void {
    $res = FileAction::viewNamespaceToAsset('namespace_che_non_esiste::css/style.css');

    Assert::assertStringStartsWith('#[namespace_che_non_esiste::css/style.css]', $res);
    Assert::assertStringEndsWith('[FileAction]', $res);
});

test('path2Url trasforma un percorso pubblico nel suo asset', function (): void {
    Assert::assertSame(asset('img/logo.png'), FileAction::path2Url(public_path('/img/logo.png'), 'pub_theme'));
});

test('viewNamespaceToUrl lascia intatti i percorsi senza namespace', function (): void {
    $files = ['css/app.css', 'js/app.js'];

    Assert::assertSame($files, FileAction::viewNamespaceToUrl($files));
});

test('viewNamespaceToUrl risolve il namespace del tema pubblico', function (): void {
    $res = FileAction::viewNamespaceToUrl(['pub_theme::css/style.css']);

    Assert::assertSame([base_path('Themes/'.XotData::make()->pub_theme).'/css/style.css'], $res);
});

test('viewNamespaceToUrl richiede una cartella nel percorso del tema', function (): void {
    try {
        FileAction::viewNamespaceToUrl(['pub_theme::style.css']);
        Assert::fail('Expected exception was not thrown.');
    } catch (Throwable $e) {
        Assert::assertStringContainsString('not found / on filename', $e->getMessage());
    }
});

test('createDirectoryForFilename crea la cartella mancante del file', function () use (&$rrmdir): void {
    $dir = sys_get_temp_dir().'/xot-fileaction-'.uniqid('', true);
    $filename = $dir.'/nested/deep/file.txt';

    FileAction::createDirectoryForFilename($filename);
    Assert::assertDirectoryExists($dir.'/nested/deep');

    // Seconda invocazione: la cartella esiste gia', nessun errore.
    FileAction::createDirectoryForFilename($filename);
    Assert::assertDirectoryExists($dir.'/nested/deep');

    $rrmdir($dir);
});

test('allDirectories elenca ricorsivamente saltando le cartelle escluse', function () use (&$rrmdir): void {
    $dir = sys_get_temp_dir().'/xot-fileaction-'.uniqid('', true);
    mkdir($dir.'/alpha/inner', 0755, true);
    mkdir($dir.'/beta', 0755, true);
    mkdir($dir.'/vendor/skipped', 0755, true);

    $all = FileAction::allDirectories($dir);
    sort($all);

    Assert::assertSame([
        'alpha',
        'alpha'.DIRECTORY_SEPARATOR.'inner',
        'beta',
        'vendor',
        'vendor'.DIRECTORY_SEPARATOR.'skipped',
    ], $all);

    $filtered = FileAction::allDirectories($dir, ['vendor']);
    sort($filtered);

    Assert::assertSame(['alpha', 'alpha'.DIRECTORY_SEPARATOR.'inner', 'beta'], $filtered);

    $rrmdir($dir);
});

test('getComponents indicizza i componenti e ne memorizza la cache su json', function () use (&$rrmdir): void {
    $dir = sys_get_temp_dir().'/xot-fileaction-'.uniqid('', true);
    mkdir($dir.'/Sub', 0755, true);
    file_put_contents($dir.'/Foo.php', '<?php');
    file_put_contents($dir.'/Sub/Bar.php', '<?php');
    file_put_contents($dir.'/readme.md', 'ignorato');

    $components = FileAction::getComponents($dir, 'Modules/Xot/View/Components', 'x-');

    $byClass = [];
    foreach ($components as $component) {
        Assert::assertIsObject($component);
        $data = get_object_vars($component);
        $className = $data['class_name'] ?? null;
        Assert::assertIsString($className);
        Assert::assertNotEmpty($className);
        $byClass[$className] = $data;
    }

    $keys = array_keys($byClass);
    sort($keys);
    Assert::assertSame(['Foo', 'Sub\\Bar'], $keys);
    Assert::assertSame('x-foo', $byClass['Foo']['comp_name']);
    Assert::assertSame('Modules\\Xot\\View\\Components\\Foo', $byClass['Foo']['comp_ns']);
    Assert::assertSame('x-sub.bar', $byClass['Sub\\Bar']['comp_name']);
    Assert::assertSame('Modules\\Xot\\View\\Components\\Sub\\Bar', $byClass['Sub\\Bar']['comp_ns']);

    Assert::assertFileExists($dir.'/_components.json');

    // Seconda invocazione: legge la cache json e restituisce array associativi.
    $cached = FileAction::getComponents($dir, 'Modules/Xot/View/Components', 'x-');
    Assert::assertCount(2, $cached);
    Assert::assertNotEmpty($cached[0]);

    // force_recreate riporta gli oggetti.
    $rebuilt = FileAction::getComponents($dir, 'Modules/Xot/View/Components', 'x-', true);
    Assert::assertCount(2, $rebuilt);
    Assert::assertIsObject($rebuilt[0]);

    $decoded = json_decode(file_get_contents($dir.'/_components.json'), true);
    Assert::assertIsArray($decoded);
    Assert::assertNotEmpty($decoded);
    Assert::assertCount(2, $decoded);

    $rrmdir($dir);
});

test('copy prepara la cartella di destinazione e non sovrascrive in console', function () use (&$rrmdir): void {
    $dir = sys_get_temp_dir().'/xot-fileaction-'.uniqid('', true);
    mkdir($dir, 0755, true);
    $from = $dir.'/from.txt';
    file_put_contents($from, 'contenuto');
    $to = $dir.'/nested/to.txt';

    FileAction::copy($from, $to);

    Assert::assertDirectoryExists($dir.'/nested');
    // In console la copia effettiva viene saltata: il file non viene creato.
    Assert::assertFileDoesNotExist($to);

    $rrmdir($dir);
});

test('copy esce subito quando la destinazione esiste gia', function () use (&$rrmdir): void {
    $dir = sys_get_temp_dir().'/xot-fileaction-'.uniqid('', true);
    mkdir($dir, 0755, true);
    $from = $dir.'/from.txt';
    $to = $dir.'/to.txt';
    file_put_contents($from, 'nuovo');
    file_put_contents($to, 'vecchio');

    FileAction::copy($from, $to);

    Assert::assertSame('vecchio', file_get_contents($to));

    $rrmdir($dir);
});

test('execute non ha effetti collaterali', function (): void {
    $action = app(FileAction::class);
    $action->execute();

    Assert::assertInstanceOf(FileAction::class, $action);
});
