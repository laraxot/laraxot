---
title: "Moduli Laraxot — provider in composer.json E module.json (doppia registrazione)"
type: concept
tags: [theme, module, filament, provider, composer, module-json, cross-reference]
updated: 2026-07-27
related:
  - ../../Modules/Xot/docs/wiki/concepts/module-providers-dual-registration-mandatory.md
  - ./module-admin-panel-provider-religion.md
  - ./module-dashboard-page-religion.md
  - ./module-config-php-religion.md
---

# Doppia registrazione provider (moduli)

Ogni **modulo** deve elencare gli **stessi** almeno-2-provider
(`{Nome}ServiceProvider` + `Providers\Filament\AdminPanelProvider`) sia in
`composer.json` (`extra.laravel.providers`, per il caricamento via Composer
path-repository tra moduli) sia in `module.json` (`providers`, per il boot interno
di nwidart/laravel-modules). Sono due meccanismi di discovery indipendenti che
convivono in questo monorepo — un provider registrato in uno solo dei due file
boota in un contesto ma non nell'altro, senza errori espliciti.

I **temi** non hanno un proprio `composer.json`/`module.json` con provider
Filament da registrare in questo senso (i temi si registrano diversamente, via
`theme.json`); questa nota serve solo da promemoria per un agente che, lavorando
su un tema, deve toccare o verificare un modulo consumato dal tema.

Checklist completa modulo con Filament: `config/config.php` +
`AdminPanelProvider.php` registrato **in entrambi** i file +
`Dashboard.php` — vedi i tre documenti correlati sopra, sempre verificati insieme.

Canon + script di audit: [Xot — module-providers-dual-registration-mandatory](../../Modules/Xot/docs/wiki/concepts/module-providers-dual-registration-mandatory.md)
