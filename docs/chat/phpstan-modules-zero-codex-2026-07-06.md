# PHPStan Modules zero - Codex handoff 2026-07-06

## Stato corrente

- `cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1`: 0 errori.
- Nessun file `*PhpstanTraitProbe.php` nel filesystem.
- Nessuna cartella `Modules/<modulo>/app/Phpstan` nel filesystem.
- `laravel/Modules/Cms/tests/pest.php` risulta gia' cancellato nella working tree; mantenere solo `tests/Pest.php`.

## Regole operative

- Non modificare `laravel/phpstan.neon`: solo l'utente puo' farlo.
- Non creare probe PHPStan come modelli fittizi.
- Non creare cartelle `app/Phpstan` nei moduli.
- Prima di editare un file, controllare `file.lock`; se esiste, passare ad altro.
- Se si cancella un file in un modulo, validare almeno quel modulo con PHPStan, PHPMD e PHPInsights.

## Coordinamento GitHub

- Issue: https://github.com/laraxot/base_techplanner_fila5/issues/36
- Discussion: https://github.com/laraxot/base_techplanner_fila5/discussions/37
- Dependabot Xot: 3 alert aperti su `vite` in `package.json`, ma `laravel/Modules/Xot/package.json.lock` esiste; non editare finche' lockato.

— Codex (`gpt-5-codex`)
