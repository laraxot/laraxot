<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Symfony\Component\Process\Process;

use function Safe\file_get_contents;

/*
 * DCP-195: il reset button/submit trasparente deve restare in @layer base nel CSS compilato,
 * altrimenti le utility Tailwind sui pulsanti FO tornano invisibili.
 */
$themeRoot = dirname(__DIR__, 2);
$manifestPath = $themeRoot.'/public/manifest.json';

it('wraps unlayered button submit transparent reset in @layer base in compiled app css', function () use ($themeRoot, $manifestPath): void {
    Assert::assertFileExists($manifestPath, 'manifest.json mancante: eseguire npm run build nel tema Sixteen.');
    /** @var array<string, array{file?: string, css?: list<string>}> $manifest */
    $manifest = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
    $entry = $manifest['resources/css/app.css']['file'] ?? null;
    Assert::assertIsString($entry, 'Voce resources/css/app.css assente nel manifest Vite.');

    $cssPath = $themeRoot.'/public/'.$entry;
    Assert::assertFileExists($cssPath, 'CSS del manifest non trovato: '.$cssPath);

    $script = <<<'JS'
import fs from 'node:fs';
import postcss from 'postcss';
const unsafe = [];
postcss.parse(fs.readFileSync(process.argv[1], 'utf8')).walkRules(rule => {
    const positiveSelector = rule.selector.replace(/:not\(\[type=[^\]]+\]\)/g, '');
    if (!positiveSelector.includes('[type=submit]') || !positiveSelector.includes('[type=reset]')) return;
    if (!rule.nodes.some(node => node.prop === 'background-color' && ['#0000', '#00000000', 'transparent'].includes(node.value))) return;
    let parent = rule.parent;
    while (parent && !(parent.type === 'atrule' && parent.name === 'layer')) parent = parent.parent;
    if (!parent) unsafe.push(rule.selector);
});
console.log(JSON.stringify(unsafe));
JS;
    $process = new Process(['node', '--input-type=module', '-e', $script, $cssPath], $themeRoot);
    $process->mustRun();
    Assert::assertSame([], json_decode($process->getOutput(), true, 512, JSON_THROW_ON_ERROR), 'Unlayered transparent button reset in '.$entry);
});
