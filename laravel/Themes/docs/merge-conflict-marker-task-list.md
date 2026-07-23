# Merge conflict marker task list (themes)

Regola operativa multi-agent: ogni file con marker `<<<<<<<` va risolto manualmente; una volta chiuso il conflitto va spuntato.

## Wiki e docs tema

- [ ] `laravel/Themes/docs/docs/wiki/index.md`
- [ ] `laravel/Themes/docs/docs/wiki/log.md`
- [ ] `laravel/Themes/docs/docs/wiki/README.md`
- [ ] `laravel/Themes/TwentyOne/docs/wiki/README.md`
- [ ] `laravel/Themes/Sixteen/docs/wiki/index.md`
- [ ] `laravel/Themes/Sixteen/docs/wiki/log.md`

## Tema Sixteen - codice/assets

- [x] `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`
- [ ] `laravel/Themes/Sixteen/public/manifest.json`
- [ ] `laravel/Themes/Sixteen/composer.lock`

## Note

- File risolto in questa sessione: `header/v1.blade.php`.
- Criterio di merge applicato: mantenere comportamento contestuale utile (`is-segnalazione-crea`) ed eliminare inline style hardcoded, lasciando i token tema come source of truth.
- Refresh scan 2026-04-21: i path sopra restano i conflitti noti lato tema; per nuovi file usare `rg '^<<<<<<< ' .` dalla root del repo.