---
title: "Gitmodules multi-repo sync coordination"
type: coordination
tags: [git, submodules, sync, multi-agent, quality]
created: 2026-07-16
updated: 2026-07-16
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/42"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/43"
---

# Gitmodules multi-repo sync coordination

## Protocollo

- Ordine estratto casualmente da `gitmodules.ini`.
- Un solo owner per path; nessuna modifica a file già sporchi senza attribuzione.
- Sync forward-only; collisioni studiate tramite history e consumer prima della patch.
- Prima di lasciare un path: gate disponibili PHPStan, PHPMD, PHPInsights, Pest e Puppeteer.
- Report degli agenti in append sotto, con remote, branch, esito sync e gate.

## Assegnazioni

- Agent A: User, Gdpr, TechPlanner, Lang, Job, Notify.
- Agent B: Activity, Employee, UI, Two, Geo, Cms.
- Agent C: Xot, Tenant, Sixteen, Zero, Media.
- Root: bashscripts e verifica aggregata `laravel/Modules`.

## Report

