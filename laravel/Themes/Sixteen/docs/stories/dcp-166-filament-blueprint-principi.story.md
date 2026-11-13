---
type: story
tags: [filament, blueprint, planning, security-audit, laravel-boost]
created: 2026-10-14
updated: 2026-10-14
qmd: "Filament Blueprint planning skill security audit review conformità"
issues: []
discussions: []
id: "2026-10-14-dcp-166"
epic: 166
title: "Filament Blueprint — principi di planning e security audit applicati"
slug: filament-blueprint-principi
status: ready-for-dev
scope: repo
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/66
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/67
  issue_status: "aperta"
related:
  - ../docs/stories/dcp-165-gdpr-docs-indice-canonico.story.md
  - ../../Xot/docs/stories/phpstan-cleanup-xot-app.story.md
---

# 2026-10-14 DCP-166 Filament Blueprint — principi di planning e security audit applicati

## Fonte

`https://filamentphp.com/plugins/filament-blueprint` (premium, $49, Filament v5).

## Principi applicabili

1. **Planning-first**: un blueprint (Markdown) specifica prima di codificare: modelli, risorse, form, tabelle, autorizzazione, test.
2. **Review di conformità**: dopo l'implementazione, verificare che il codice rispetti il blueprint (Conforms / Deviates / Blocked / Not verified).
3. **Security audit skill**: scansione contro misconfigurazioni note (autorizzazione, upload, XSS, query scoping, CVE).

## Applicazione al progetto

- Il progetto usa Filament (XotBaseResource) e non ha blueprint per i moduli mancanti.
- Creare un `blueprints/` directory con un blueprint per ogni modulo da completare (Gdpr, Fixcity, Cms).
- Eseguire una security audit sui panel Filament esistenti.

## Accettazione

1. `blueprints/` directory con almeno un blueprint per il modulo Gdpr.
2. Security audit report con findings e remediation.
3. Nessun file in `app/Http/Controllers`.
4. PHPStan zero errors.