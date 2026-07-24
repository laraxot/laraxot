---
title: "Debito D7 — soft-deps export DomPDF / PhpWord"
type: how-to
tags: [technical-debt, dompdf, phpword, export, workorder, billing]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt soft deps dompdf phpword document export bridge workorder"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-billing-pages-xotbase.md"
  - "../Modules/WorkOrder/docs/import-status.md"
---

# Debito D7 — DomPDF / PhpWord (export)

## Perché (business)

Commesse e fatture devono spesso produrre PDF (e a volte DOCX). Nel porting le librerie **non** sono hard-deps root: il codice usa un **bridge** (es. `Modules/WorkOrder/app/Support/DocumentExportBridge.php`) che:

- se la classe vendor esiste → genera il documento;
- altrimenti → `RuntimeException` controllata o messaggio UI.

Questo evita di forzare vendor pesanti su ogni ambiente; crea debito funzionale finché UAT non richiede export.

## Dove vive il debito

| Area | Cosa cercare |
|---|---|
| WorkOrder | Bridge export, Action `Generate*Report*`, RelationManager export |
| Billing | Facade `Barryvdh\DomPDF\Facade\Pdf` / riferimenti PhpWord |
| composer | `barryvdh/laravel-dompdf`, `phpoffice/phpword` in root `laravel/composer.json` e `composer.lock` |

```bash
cd laravel
rg -n "DocumentExportBridge|laravel-dompdf|PhpWord|DomPDF" Modules/WorkOrder Modules/Billing composer.json composer.lock | head -40
composer show barryvdh/laravel-dompdf 2>/dev/null || echo "dompdf: NOT INSTALLED"
composer show phpoffice/phpword 2>/dev/null || echo "phpword: NOT INSTALLED"
```

## Decisioni prodotto (obbligatorie prima del require)

| Scelta | Quando | Effetto |
|---|---|---|
| **A. Install PDF+DOCX** | UAT/prod richiedono entrambi | `composer require` entrambi |
| **B. Solo PDF** | DOCX non in scope | DomPDF only; DOCX resta throw/UI off |
| **C. Nessun export** | MVP senza stampa | UI action hidden; bridge resta soft |

Scrivere la scelta su Issue #14 (una riga basta).

## Come risolvere — Opzione A/B (install)

### Passi

1. OK utente (licenza, peso image Docker, tempo CI).
2. Da `laravel/` (merge-plugin: deps preferibilmente **root**, non solo modulo):
   ```bash
   composer require barryvdh/laravel-dompdf
   # se scelta A:
   composer require phpoffice/phpword
   ```
3. Se il package pubblica config:
   ```bash
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```
4. Verificare che il bridge risolva le classi:
   ```bash
   php -r 'require "vendor/autoload.php"; echo class_exists("Barryvdh\\DomPDF\\Facade\\Pdf") ? "pdf-ok\n" : "pdf-missing\n";'
   ```
5. Pest smoke (niente binari in git):
   - Action con view Blade minimale → assert string PDF non vuota **oppure** assert file temporaneo size > 0 poi unlink.
6. Aggiornare `Modules/WorkOrder/docs/import-status.md` e inventario D7.

### Rollback

`composer remove …` + ripristinare UI hidden — forward-only sul codice bridge (non lasciare fatal).

## Come risolvere — Opzione C (non installare)

1. Filament: `->visible(false)` / `->disabled()` sulle Action export (o feature flag AccountFeatures).
2. Messaggio user-facing chiaro se qualcuno chiama API: “Export non configurato”.
3. Test: assert che l’action non sia registrata **oppure** che lanci RuntimeException con messaggio stabile.
4. Documentare in readiness come gap accettato.

## DoD

- [ ] Scelta A/B/C scritta su GitHub
- [ ] O pacchetti in `composer.lock` + smoke, **oppure** UI/API esplicitamente disabilitate
- [ ] Nessun fatal non gestito in prod se manca vendor
- [ ] Inventario [gestionale-technical-debt.md](./gestionale-technical-debt.md) aggiornato

## Non fare

- Copiare `vendor/` a mano.
- Mettere require solo in `Modules/WorkOrder/composer.json` sperando nel merge senza verificare lock root.
- Richiedere pacchetti “di nascosto” in un task docs.
- Usare `@phpstan-ignore` al posto del soft-bridge.
