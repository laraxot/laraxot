<?php

declare(strict_types=1);

use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\glob;

it('keeps infolist schemas out of direct and indirect Xot view pages', function (): void {
    $parser = (new ParserFactory())->createForNewestSupportedVersion();
    $finder = new NodeFinder();
    /** @var array<string, Class_> $classes */
    $classes = [];

    // Parse source without autoloading pages: invalid #[Override] must be reportable.
    foreach (glob(dirname(__DIR__, 4).'/*/app/Filament') as $directory) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));

        foreach ($files as $file) {
            Assert::assertInstanceOf(SplFileInfo::class, $file);
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $source = file_get_contents($file->getPathname());
            if (! str_contains($source, 'extends')) {
                continue;
            }

            $nodes = $parser->parse($source);
            Assert::assertNotNull($nodes);
            $nodes = (new NodeTraverser(new NameResolver()))->traverse($nodes);

            foreach ($finder->findInstanceOf($nodes, Class_::class) as $class) {
                if (isset($class->namespacedName)) {
                    $classes[$class->namespacedName->toString()] = $class;
                }
            }
        }
    }

    Assert::assertArrayHasKey(XotBaseViewRecord::class, $classes);
    Assert::assertNull($classes[XotBaseViewRecord::class]->getMethod('getInfolistSchema'));
    $pages = 0;
    $violations = [];

    foreach ($classes as $name => $class) {
        $parent = $class->extends?->toString();
        $visited = [];

        while ($parent !== null && $parent !== XotBaseViewRecord::class && isset($classes[$parent]) && ! isset($visited[$parent])) {
            $visited[$parent] = true;
            $parent = $classes[$parent]->extends?->toString();
        }

        if ($parent !== XotBaseViewRecord::class) {
            continue;
        }

        $pages++;
        if ($class->getMethod('getInfolistSchema') !== null) {
            $violations[] = $name;
        }
    }

    Assert::assertGreaterThan(0, $pages);
    Assert::assertSame([], $violations, 'Move getInfolistSchema() to the Resource Schemas/*Infolist class: '.implode(', ', $violations));
})->group('no-db');
