---
title: "Moduli Laraxot — config/config.php"
type: concept
tags: [theme, module, config, cross-reference]
updated: 2026-07-27
related:
  - ../../Modules/Xot/docs/wiki/concepts/module-config-php-religion.md
  - ./module-structure.md
---

# config/config.php (moduli)

I **moduli** in `laravel/Modules/<Modulo>/` devono avere `config/config.php` con `name`, `icon`, `navigation`.

I **temi** usano `theme.json` per metadati nwidart; non duplicare panel config nel tema — leggere dal modulo owner o da config tema root se applicabile.

Quando un agente lavora su FO/panel: verificare sempre che il modulo referenziato abbia `config/config.php` valido **e** `app/Filament/Pages/Dashboard.php` ([module-dashboard-page-religion.md](./module-dashboard-page-religion.md)).

Audit: `bash bashscripts/tools/audit-module-config-php.sh` · `bash bashscripts/tools/audit-module-dashboard-page.sh`

**Icona modulo**: mai `heroicon-o-square-3-stack-3d` (placeholder generico) — o un
Heroicon esistente e pertinente al dominio (verificare che il file `o-{nome}.svg` esista
davvero in `vendor/blade-ui-kit/blade-heroicons`), o un SVG custom in
`resources/svg/{file}.svg` auto-registrato da `XotBaseServiceProvider` e richiamabile come
`{modulo_lower}-{file}` (`Str::lower()`, non `Str::snake()` — es. `PublicProcurement` →
`publicprocurement-icon`, tutto attaccato). Dettagli e criterio di scelta:
[module-config-php-religion § Scelta dell'icona](../../Modules/Xot/docs/wiki/concepts/module-config-php-religion.md#scelta-dellicona-due-percorsi-validi-verificati-nel-sorgente).

Canon completo: [module-config-php-religion](../../Modules/Xot/docs/wiki/concepts/module-config-php-religion.md)
