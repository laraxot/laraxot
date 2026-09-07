---
title: "PSR-4 nei test Notify"
type: concept
tags: [psr-4, composer, tests, fixtures]
created: 2026-07-16
updated: 2026-07-16
qmd: "notify psr-4 test doubles phpstan probes composer autoload"
issues:
<<<<<<< HEAD
<<<<<<< HEAD
  - "https://github.com/laraxot/base_techplanner_fila5/issues/38"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
=======
  - ""
discussions:
  - ""
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
  - "https://github.com/laraxot/base_techplanner_fila5/issues/38"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
>>>>>>> a988596b (first)
related:
  - "../../../../Xot/docs/wiki/concepts/psr4-one-class-one-file.md"
---

# PSR-4 nei test Notify

Ogni test double vive nel file omonimo sotto `tests/`; non mantenere anche un aggregatore `*TestDoubles.php`. Le probe PHPStan non appartengono ad `app/Phpstan`: testare i trait con fixture reali sotto `tests/` evita classi di produzione create solo per l'analizzatore.

Il controllo conclusivo è `composer dump-autoload -o`, seguito da PHPStan e dal test Pest owner.
