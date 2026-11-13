<?php

declare(strict_types=1);

/*
 * Le icone di header, footer, ricerca e menu utente sono `<use href=".../sprites.svg#nome">`. Senza lo sprite la richiesta risponde 404
 * (sia sul percorso diretto `/themes/Sixteen/...` sia sulla rotta `/{lingua}/assets/...`, che serve dallo stesso `public/`) e le icone
 * spariscono senza alcun errore visibile.
 *
 * `deploy.sh` non esegue il build del tema: serve `Themes/Sixteen/public` cosi' com'e' nel repository. Quindi lo sprite deve stare
 * nell'output versionato (non in `.gitignore`) e il deploy deve rifiutare un tema che non lo ha, come gia' fa con `manifest.json`.
 * Test di soli file: non avvia l'app e non tocca il database.
 */
const SIXTEEN_ICON_SPRITE = 'design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';

it('ships the icon sprite in the versioned theme public output', function (): void {
    $sprite = dirname(__DIR__, 2).'/public/'.SIXTEEN_ICON_SPRITE;

    expect($sprite)->toBeFile();
    expect((string) file_get_contents($sprite))->toContain('<svg');
    expect((int) filesize($sprite))->toBeGreaterThan(1000);
});

it('does not git-ignore the sprite that production serves', function (): void {
    $theme = dirname(__DIR__, 2);

    if (! file_exists($theme.'/.git')) {
        $this->markTestSkipped('Il tema non e un checkout git: nessuna regola di ignore da interrogare.');
    }

    // Si chiede a git, non si legge una riga del .gitignore: `dist/` esclude la cartella e basta una sola regola dimenticata
    // (o una negazione mancante) perche' il file non finisca nel repository. Uscita 1 = non ignorato; 0 = ignorato; 128 = errore.
    exec('git -C '.escapeshellarg($theme).' check-ignore -q -- '.escapeshellarg('public/'.SIXTEEN_ICON_SPRITE).' 2>&1', $output, $exitCode);

    expect($exitCode)->toBe(1);
});

it('makes deploy.sh refuse a theme without the icon sprite', function (): void {
    $commands = array_filter(
        array_map(trim(...), file(dirname(__DIR__, 5).'/deploy.sh') ?: []),
        static fn (string $line): bool => $line !== '' && ! str_starts_with($line, '#'),
    );
    $guards = array_filter($commands, static fn (string $line): bool => str_starts_with($line, 'test -f') && str_contains($line, 'themes/Sixteen/'.SIXTEEN_ICON_SPRITE));

    expect($guards)->not->toBeEmpty();
});
