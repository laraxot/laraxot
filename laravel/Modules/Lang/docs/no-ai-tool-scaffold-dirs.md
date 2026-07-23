---
title: No AI/tool scaffold directories in module tree
---

# Perché queste cartelle non devono esistere qui

Regola canonica: [module-theme-root-cleanup.md — Rule 5](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

Rimosse in questo modulo: `_docs/`, `scripts/`, `bashscripts/`, `docs/archive|archived|legacy|workbench/`, `.circleci/`, `.claude-audit/`, `tests/.claude-audit/`, `_bmad-output/`, `test-results/`, `.devcontainer/`, `.kilocode/`, `.kiro/`, `.ralph/` (dove presenti) e aggiunte al `.gitignore` di questo modulo.

**Perché**: questo modulo vive anche come repo Git indipendente (multi-repo); ogni agente/tool AI o pipeline CI che gira in quella root scrive lì la propria cache/scaffold locale (skill `.kiro/`, output `_bmad-output/`, stato `.ralph/`, audit `.claude-audit/`, log `test-results/`), ignorando che quella root è in realtà un sotto-albero del monorepo con le proprie convenzioni: `docs/` unica per la conoscenza riusabile, `bashscripts/` unica alla root del monorepo, `build/` unico per gli artefatti generati. Un secondo posto per la stessa categoria di contenuto è entropia, non struttura — se il tool lo rigenera, il `.gitignore` aggiornato lo tiene fuori dal tracking invece di doverlo ripulire ogni sessione.

## `tests/AuditCoverage` e il tranello del rename in `.bak` (2026-07-16)

Lo script `bashscripts/tools/claude-audit-module-static-boost.sh` generava classi finte
(`final class AuditBridgeTestNN { public function test_bridge(): void { self::assertTrue(true); } }`)
sotto `tests/AuditCoverage/` per gonfiare artificialmente il ratio di copertura. Sono scaffold
fasulli: non estendono nessun TestCase, quindi `self::assertTrue()` è un metodo statico
inesistente → 24 errori PHPStan `staticMethod.notFound`.

Lo script è stato corretto per scrivere in `build/audit-coverage/${MODULE}/` (fuori dal modulo,
namespace `Build\AuditCoverage\...`). **Ma archiviare la cartella rinominandola in
`tests/AuditCoverage.bak/` NON basta**: PHPStan analizza qualunque `*.php` sotto i path passati,
`.bak` incluso — gli errori restano. Anche `.gitignore` non aiuta: PHPStan non guarda lo stato Git.
La soluzione corretta è **eliminare** la cartella (era untracked, nessun valore), non rinominarla.
Regola: gli scaffold finti di boost-copertura non devono mai vivere nell'albero del modulo, in
nessuna forma (`AuditCoverage/`, `AuditCoverage.bak/`, ecc.).
