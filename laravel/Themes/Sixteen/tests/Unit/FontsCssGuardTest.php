<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\filesize;
use function Safe\glob;
use function Safe\preg_match_all;

/*
 * DCP-183: i font del tema sono asset di Vite. Se un `url()` di `fonts.css` e' assoluto (`/themes/Sixteen/...`) Vite non lo elabora:
 * niente hash nel nome, niente cache lunga, niente voce nel manifest per le preload. Se il file e' mancante o a 0 byte (DCP-08)
 * il `@font-face` cade in silenzio sul carattere di ripiego. Solo filesystem, nessun database.
 */
$themeRoot = dirname(__DIR__, 2);
$fontsCss = $themeRoot.'/resources/css/design-comuni/fonts.css';

/**
 * @return list<string> gli `url()` dei file woff2 dichiarati, come scritti nel CSS
 */
$declaredUrls = static function () use ($fontsCss): array {
    preg_match_all('/url\(\s*["\']?([^"\')]+\.woff2)["\']?\s*\)/', file_get_contents($fontsCss), $matches);

    return $matches[1];
};

it('declares font files as relative urls that vite can process', function () use ($declaredUrls): void {
    $urls = $declaredUrls();

    Assert::assertNotSame([], $urls, 'Nessun woff2 dichiarato: la guardia passerebbe a vuoto.');
    foreach ($urls as $url) {
        Assert::assertStringStartsWith('../../fonts/', $url, "{$url}: usare un percorso relativo a resources/fonts, non un URL assoluto servito da public/");
    }
});

it('points every declared font at an existing non-empty file', function () use ($declaredUrls, $themeRoot): void {
    foreach ($declaredUrls() as $url) {
        $file = $themeRoot.'/resources/css/design-comuni/'.$url;

        Assert::assertFileExists($file, "{$url} non esiste");
        Assert::assertGreaterThan(0, filesize($file), "{$url} e' vuoto (0 byte)");
    }
});

it('has no font file in resources/fonts that css does not declare', function () use ($declaredUrls, $themeRoot): void {
    $declared = array_map(static fn (string $url): string => basename($url), $declaredUrls());
    $onDisk = array_map(static fn (string $file): string => basename($file), array_filter(glob($themeRoot.'/resources/fonts/*/*.woff2'), is_string(...)));

    Assert::assertNotSame([], $onDisk, 'resources/fonts e\' vuota o manca.');
    Assert::assertSame([], array_values(array_diff($onDisk, $declared)), 'woff2 orfani in resources/fonts (non dichiarati in fonts.css)');
});

it('uses Lora Regular for the homepage civic headline (DCP-221 / D18)', function () use ($themeRoot): void {
    $homepageCss = file_get_contents($themeRoot.'/resources/css/app/16-homepage-civic-reframe.css');

    Assert::assertStringContainsString('.fc-home-hero h1', $homepageCss);
    // !important obbligatorio: 08-layout forza Titillium su body * (shell Filament FO).
    // Slogan lungo: Lora 400 (serif display) batte Titillium Bold — evita fallback Black 900.
    Assert::assertMatchesRegularExpression(
        '/\.fc-home-hero h1[^{]*\{[^}]*font-family:\s*"Lora"/s',
        $homepageCss,
        'Hero h1 deve usare Lora (DCP-221), non Titillium Bold.'
    );
    Assert::assertStringContainsString('font-size: clamp(2.5rem, 4vw, 3rem)', $homepageCss);
    Assert::assertStringContainsString('font-weight: 400 !important', $homepageCss);
    Assert::assertStringContainsString('line-height: 1.2', $homepageCss);
    Assert::assertDoesNotMatchRegularExpression(
        '/\.fc-home-hero h1[^{]*\{[^}]*font-weight:\s*700/s',
        $homepageCss,
        'Hero h1 non deve tornare a weight 700 (regressione ciccione).'
    );
});
