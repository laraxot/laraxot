---
title: "Debito D13 — registrare test moduli in phpunit.xml / CI"
type: how-to
tags: [technical-debt, pest, phpunit, ci, modules]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt phpunit xml modules testsuites pest ci registration"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-pest-field-service-coverage.md"
  - "./pest_testing_setup.md"
---

# Debito D13 — i test dei moduli non girano in CI se non registrati

## Perché

Pest/PHPUnit root (`laravel/phpunit.xml` o equivalente) può **non** includere `Modules/*/tests`.  
Risultato: si scrivono Feature utili, `vendor/bin/pest Modules/X/tests` passa in locale, ma `composer test` / CI **non** li esegue mai.

## Come verificare

```bash
cd laravel
# cosa include la suite default?
rg -n "Modules/|testsuite|tests/" phpunit.xml phpunit.xml.dist 2>/dev/null
composer test -- --list-tests 2>/dev/null | rg 'Modules\\\\(Customer|WorkOrder|Intervention)' | head
```

Se i test GC non compaiono nella lista default → D13 aperto.

## Come risolvere

### Opzione A — Testsuite per modulo (esplicita)

In `phpunit.xml`:

```xml
<testsuite name="Customer">
  <directory suffix="Test.php">Modules/Customer/tests</directory>
</testsuite>
<!-- ripetere per WorkOrder, Intervention, … -->
```

Pro: controllo fine. Contro: file XML lungo; aggiornare a ogni nuovo modulo.

### Opzione B — Directory glob (KISS se supportato)

```xml
<testsuite name="Modules">
  <directory suffix="Test.php">Modules/*/tests</directory>
</testsuite>
```

Verificare che PHPUnit della versione in uso espanda il glob (altrimenti script generate).

### Opzione C — Script CI che invoca pest path-scoped

```bash
for m in Customer WorkOrder Intervention Quotation; do
  vendor/bin/pest "Modules/$m/tests" || exit 1
done
```

Pro: non tocca phpunit.xml. Contro: due entrypoint (dev vs CI).

### Consigliato per questo repo

1. Story BMAD su #14: “CI esegue Pest GC cuore”.
2. Partire da **Opzione C** per Customer+WorkOrder+Fiscal (già verdi).
3. Estendere a Intervention quando D6 Strato 2 esiste.
4. Poi consolidare in phpunit.xml (A o B) quando la lista è stabile.
5. Documentare comando canon in `pest_testing_setup.md` (stesso cartella).

## DoD

- [ ] Almeno un job CI/local `composer test` (o script documentato) esegue Pest di ≥2 moduli GC
- [ ] Fallimento di un test modulo fa fallire la pipeline
- [ ] Nessun `RefreshDatabase` introdotto “per far passare CI”

## Non fare

- Dichiarare “abbiamo i test” se girano solo a mano con path.
- Aggiungere TechPlanner/path fantasma che produce `Test directory not found` e confonde gli agenti.
