---
title: "Code quality — tema Zero"
type: report
tags: [theme, zero, code, quality, report]
created: 2026-07-24
updated: 2026-07-24
qmd: "zero theme code quality   tema zero"
id: zero-docs-bmad-code-quality-report
slug: code-quality-report
description: "Report locale (2026-07-17). Metodo: phpstan analyse (sweep repo-wide, incluso nei Themes), phpmd (codesize+unusedcode), grep mirati (TODO/FIXME, dd()/dump() nei .blade.php,..."
document_type: analysis
category: static-analysis
status: active
issues: []
discussions: []
---
# Code quality — tema Zero

Report locale (2026-07-17). Metodo: `phpstan analyse` (sweep repo-wide, incluso nei Themes), `phpmd` (codesize+unusedcode), grep mirati (TODO/FIXME, dd()/dump() nei .blade.php, facade dirette in app/Actions).

## Numeri

- File PHP applicativi (`app/`): 0
- File Blade: 23
- File con TODO/FIXME/@deprecated: 0
- `dd()`/`dump()`/`var_dump()` residui in Blade: 0
- Violazioni PHPMD (codesize+unusedcode): 15
- Facade Laravel dirette in `app/Actions/` (violazione pattern QueueableAction): 0
- PHPStan: incluso nello sweep repo-wide, 0 errori residui noti

## Azioni consigliate

- La qualità delle view Blade/Volt (duplicazione, componenti riusabili) non è stata misurata quantitativamente in questo giro — possibile follow-up con un audit dedicato ai componenti.


## Come migliorare — modifiche effettive da fare

