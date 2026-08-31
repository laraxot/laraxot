---
title: "Nessun marker di conflitto Git"
type: rule
module: Xot
tags: [git, conflict-markers, quality-gate]
updated: 2026-08-25
related:
  - ./INDEX.md
  - ../git/risoluzione-conflitti.md
  - ../../risoluzione-conflitti.md
  - ../../stories/5.37.zero-conflict-markers-repo-wide.story.md
  - ../../../../../docs/rules/no-conflict-markers-anywhere.md
---

# Nessun marker di conflitto Git

Regola canonica di progetto: [docs/rules/no-conflict-markers-anywhere.md](../../../../../docs/rules/no-conflict-markers-anywhere.md).

## Perché in Xot

Xot è il cuore del monorepo: marker residui in docs/wiki o in config condivise propagano conoscenza falsa a tutti i moduli e azzerano i gate statici.

## Cosa fare

1. Censire con `/bin/grep` (pattern story XOT-5.37).
2. Risolvere a mano (no rewrite di massa sulle docs).
3. Verificare con `bash bashscripts/quality-gates/verify-no-conflict-markers.sh`.

## Allowlist nota

- `bashscripts/cacert.pem` — underline `=======` del certificato `Juur-SK` (falso positivo).
