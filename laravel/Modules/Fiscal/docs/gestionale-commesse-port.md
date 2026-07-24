---
title: "Porting gestionale_commesse — Fiscal (test migration)"
type: concept
tags: [import, gestionale-commesse, fiscal, tests, pest]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting Fiscal gestionale_commesse test migration pest vat fattura elettronica"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "./README.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
---

# Porting gestionale_commesse — Fiscal (test migration, 2026-07-24)

Sorgente: `/var/www/_bases/gestionale_commesse/tests/Unit/Fiscal/` (4 file, nessun test Feature nel sorgente).

## Test migrati dal sorgente

Tutti e 4 i file sorgente risultavano **già portati e passanti** da una sessione precedente, verificato prima di
aggiungere qualunque cosa (evitare duplicazione, come da istruzioni):

| Sorgente | Destinazione | Esito |
|---|---|---|
| `Unit/Fiscal/CalculateInvoiceTotalsActionTest.php` | `tests/Unit/CalculateInvoiceTotalsActionTest.php` | ✅ pass |
| `Unit/Fiscal/CalculatePaymentPlanActionTest.php` | `tests/Unit/CalculatePaymentPlanActionTest.php` | ✅ pass |
| `Unit/Fiscal/CalculateVatLiquidationActionTest.php` | `tests/Unit/CalculateVatLiquidationActionTest.php` | ✅ pass |
| `Unit/Fiscal/GenerateFatturaElettronicaXmlActionTest.php` | `tests/Unit/GenerateFatturaElettronicaXmlActionTest.php` | ✅ pass |

Nessun nuovo test aggiunto in questa sessione: le 4 Action coperte dal sorgente (calcolo totali fattura, piano di
pagamento, liquidazione IVA, generazione XML FatturaPA) sono tutte Unit puri su `Datas`/`Actions` senza dipendenze da
DB o package esterni (l'XML FatturaPA è generato come stringa, non richiede `barryvdh/laravel-dompdf` né altri
vendor non installati), quindi non soffrono del debito #12 (sqlite `:memory:` non migrato, vedi
`Modules/Billing/docs/gestionale-commesse-port.md`) che blocca invece gran parte dei Feature test di `Billing`.

Nessuna integrazione Billing→Fiscal testata: coerente con il gap documentato (Billing non chiama ancora Fiscal),
non inventata alcuna asserzione di integrazione.

## Comando di verifica

```bash
cd laravel && ./vendor/bin/pest Modules/Fiscal/tests --no-coverage
```

Esito (2026-07-24): 6 pass, 0 skip, 0 fail (14 assertions).
