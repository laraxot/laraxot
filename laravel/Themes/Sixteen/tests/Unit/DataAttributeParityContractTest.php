<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_match_all;

/*
 * docs/data-attribute-rule.md: il riferimento Design Comuni usa 12 nomi `data-*` e nessuno porta il nome di un tema o di un
 * progetto. Un `data-sixteen-*` e' HTML che il riferimento non ha (drift dalla regola 013). Test sul SORGENTE (Blade, JS, CSS):
 * dice che il nome non e' scritto, non che il menu si apra; la prova e' il browser dopo `npm run build`.
 */
function dataAttributeParitySources(): array
{
    $root = dirname(__DIR__, 2).'/resources';
    $endings = ['views' => ['.blade.php'], 'js' => ['.js', '.mjs'], 'css' => ['.css']];
    $sources = ['views' => [], 'js' => [], 'css' => []];

    foreach ($endings as $kind => $suffixes) {
        if (! is_dir($root.'/'.$kind)) {
            continue;
        }
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/'.$kind, FilesystemIterator::SKIP_DOTS)) as $file) {
            // I `.lock` di coordinamento tra agenti finiscono in `.lock`, non in un suffisso cercato: restano fuori da soli.
            if (! $file instanceof SplFileInfo || ! array_filter($suffixes, static fn (string $suffix): bool => str_ends_with($file->getFilename(), $suffix))) {
                continue;
            }
            $sources[$kind][substr($file->getPathname(), strlen($root) + 1)] = file_get_contents($file->getPathname());
        }
    }

    return $sources;
}

test('nessun sorgente del tema usa un data-* col nome del tema, di un tema fratello o del progetto', function (): void {
    $scanned = 0;
    $offenders = [];

    foreach (dataAttributeParitySources() as $files) {
        foreach ($files as $path => $content) {
            $scanned++;
            // `two` solo come `data-two`: `dataset.twoFactor` e simili sono nomi legittimi.
            if (preg_match('/\bdata-(?:sixteen|two|fixcity|techplanner)\b|dataset(?:\.|\[\s*[\'"])(?:sixteen|fixcity|techplanner)/i', $content, $match) === 1) {
                $offenders[] = $path.' -> '.$match[0];
            }
        }
    }

    Assert::assertGreaterThan(0, $scanned, 'Nessun sorgente letto: la guardia passerebbe a vuoto.');
    Assert::assertSame([], $offenders, 'Il riferimento Design Comuni non ha attributi col nome del tema: usare l\'aggancio del riferimento, `data-element` o un `data-<comportamento>` generico (docs/data-attribute-rule.md). Un commento che cita il nome vecchio fa scattare lo stesso la guardia: riformularlo.');
});

test('ogni data-mobile-nav* emesso da una Blade ha un consumatore in JS o CSS, altrimenti e un nome inventato', function (): void {
    $sources = dataAttributeParitySources();
    $emitted = [];

    foreach ($sources['views'] as $path => $content) {
        preg_match_all('/(?<![\w-])(data-mobile-nav(?:-[a-z]+)*)(?![\w-])/', $content, $matches);
        foreach ($matches[1] as $name) {
            $emitted[$name][] = $path;
        }
    }

    // ponytail: basta che il nome compaia in JS/CSS, anche in un commento; se serve di piu', cercare solo dentro `[..]`, `getAttribute` e `closest`.
    $consumers = implode("\n", array_merge($sources['js'], $sources['css']));
    $orphans = [];
    foreach ($emitted as $name => $paths) {
        if (preg_match('/(?<![\w-])'.preg_quote($name, '/').'(?![\w-])/', $consumers) !== 1) {
            $orphans[] = $name.' ('.implode(', ', array_unique($paths)).')';
        }
    }

    Assert::assertGreaterThan(0, count($sources['views']), 'Nessuna Blade letta: la guardia passerebbe a vuoto.');
    Assert::assertSame([], $orphans, 'Attributi emessi che nessun JS o CSS legge: toglierli dalla Blade o aggiungere il consumatore.');
});
