---
title: "Moduli Laraxot — app/Providers/Filament/AdminPanelProvider.php"
type: concept
tags: [theme, module, filament, panel, provider, cross-reference]
updated: 2026-07-27
related:
  - ../../../Modules/Xot/docs/wiki/concepts/module-admin-panel-provider-mandatory.md
  - ./module-dashboard-page-religion.md
  - ./module-config-php-religion.md
---

# app/Providers/Filament/AdminPanelProvider.php (moduli)

Ogni **modulo** che espone UI Filament deve avere `AdminPanelProvider extends
Modules\Xot\Providers\Filament\XotBasePanelProvider`, registrato nell'array `providers` di
`module.json` — è questa classe che crea il panel `{modulo}/admin` e vi collega
Resources/Pages/Widgets/Clusters del modulo via `discover*()`. Senza registrazione in
`module.json` il file non ha alcun effetto, anche se sintatticamente corretto.

I **temi** non hanno un proprio panel Filament: consumano/estendono l'esperienza
front-office, mai il backoffice di un modulo. Un tema non deve mai duplicare o wrappare
un `AdminPanelProvider`.

Checklist agente su un modulo con Filament: `config/config.php`
([module-config-php-religion.md](./module-config-php-religion.md)) +
`AdminPanelProvider.php` registrato +
`Dashboard.php`
([module-dashboard-page-religion.md](./module-dashboard-page-religion.md)) — i tre vanno
sempre verificati insieme.
