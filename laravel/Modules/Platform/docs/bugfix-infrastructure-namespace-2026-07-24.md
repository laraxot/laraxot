---
title: "Bugfix — namespace Infrastructure\\Models inesistente in PlatformServiceProvider"
type: concept
tags: [platform, bugfix, namespace, reshape]
created: 2026-07-24
updated: 2026-07-24
qmd: "Platform PlatformServiceProvider Infrastructure Models namespace bugfix"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "../../../../docs/gestionale-commesse-comparison/reshape-completion-report.md"
---

# Bugfix — namespace obsoleto in PlatformServiceProvider

`app/Providers/PlatformServiceProvider.php` importava `Modules\{Billing,Customer,Intervention,Quotation,WorkOrder}\Infrastructure\Models\*`. Questo namespace è stato eliminato quando quei moduli sono stati reshapati alla struttura canonica Laraxot (`app/Models`, non più `Infrastructure/Models`). Il commento nel file era ormai falso ("non ancora reshape-ati in questa wave").

**Effetto se non corretto:** autoload PHP fallisce non appena `Platform` viene abilitato in `modules_statuses.json` (oggi `false`, quindi il bug era silente).

**Fix:** import aggiornati a `Modules\{Nome}\Models\*`, commento obsoleto rimosso. Verificato con grep ricorsivo sull'intero repo che non esistono altri riferimenti residui a `Infrastructure\`/`Application\`/`Domain\` di moduli già reshapati.

**Lezione:** quando un modulo A dipende (anche solo per un Observer/binding) da modelli di un modulo B, e B viene reshapato, i riferimenti in A vanno aggiornati nella stessa sessione — altrimenti il bug resta silente finché A non viene abilitato/testato.
