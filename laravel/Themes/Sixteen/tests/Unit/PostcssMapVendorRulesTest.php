<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Symfony\Component\Process\Process;

/*
 * DCP-146: `postcss/isolate-legacy-important.cjs` limita le regole legacy fuori dall'ambito Design Comuni (`body:has(.page-content[data-dc])`).
 * Applicava il guard anche a leaflet.css e MarkerCluster: dentro l'ambito `.leaflet-pane` e i tile restavano `position: static`, i tile si impilavano
 * in colonna fuori dal riquadro e la mappa del wizard sembrava rotta. Il plugin viene eseguito davvero (node + postcss), senza database.
 */
function isolatePluginOutput(string $css): string
{
    $themeRoot = dirname(__DIR__, 2);
    $script = 'const postcss=require("postcss");const plugin=require("./postcss/isolate-legacy-important.cjs");'
        .'postcss([plugin()]).process(process.argv[1],{from:undefined}).then(r=>process.stdout.write(r.css));';
    $process = new Process(['node', '-e', $script, $css], $themeRoot);
    $process->run();
    Assert::assertTrue($process->isSuccessful(), 'Il plugin PostCSS non si esegue: '.$process->getErrorOutput());

    return $process->getOutput();
}

const DC_SCOPE_GUARD = ':not(:where(body:has(.page-content[data-dc])';

it('leaves the Leaflet and MarkerCluster vendor rules active inside the Design Comuni scope', function (string $rule): void {
    Assert::assertSame($rule, isolatePluginOutput($rule));
})->with([
    'pannello' => ['.leaflet-pane{position:absolute;z-index:400}'],
    'tile con !important' => ['.leaflet-container .leaflet-tile{max-width:none!important}'],
    'cluster' => ['.marker-cluster div{width:30px}'],
]);

it('still isolates the legacy rules and the look-alike names that are not the map plugin', function (string $selector): void {
    $output = isolatePluginOutput($selector.'{color:red}');

    Assert::assertStringContainsString(DC_SCOPE_GUARD, $output);
})->with([
    'legacy' => ['.legacy a'],
    'nome simile ma nostro' => ['.map-picker-leaflet-pane'],
]);

it('guards only the non vendor selectors of a mixed selector list', function (): void {
    $output = isolatePluginOutput('.leaflet-popup a, .x a{color:blue}');

    Assert::assertStringStartsWith('.leaflet-popup a, .x a'.DC_SCOPE_GUARD, $output);
});

/*
 * CMT-022: l'editor Markdown di Filament (EasyMDE + CodeMirror 5) nei commenti. Con il guard acceso dentro l'ambito Design Comuni i pulsanti
 * della barra erano larghi 0 px e `.CodeMirror-measure` restava visibile: stessa causa della mappa, stessa esenzione per i nomi del vendor.
 */
it('leaves the Markdown editor vendor rules active inside the Design Comuni scope', function (string $rule): void {
    Assert::assertSame($rule, isolatePluginOutput($rule));
})->with([
    'misura' => ['.CodeMirror-measure{visibility:hidden;width:100%;height:0;position:absolute;overflow:hidden}'],
    'contenitore' => ['.EasyMDEContainer .CodeMirror{border:1px solid #ced4da}'],
    'barra' => ['.editor-toolbar button{width:30px;height:30px}'],
    'barra con !important' => ['.editor-toolbar{border-top:1px solid #ced4da!important}'],
    'tema cm' => ['.cm-s-easymde .cm-header{font-weight:700}'],
    'riquadro Filament' => ['.fi-fo-markdown-editor{border-radius:.5rem}'],
]);

it('still isolates the other Filament classes and the comment widget look-alikes', function (string $selector): void {
    $output = isolatePluginOutput($selector.'{color:red}');

    Assert::assertStringContainsString(DC_SCOPE_GUARD, $output);
})->with([
    'pulsante Filament' => ['.fi-btn'],
    'campo Filament' => ['.fi-fo-field'],
    'nostro cmt' => ['.cmt-section .fi-btn'],
    'nome simile' => ['.my-cm-toolbar'],
]);
