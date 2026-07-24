# Bom — Stato Factory / Seeder / Migration

Obiettivo: ogni modello concreto ha migration + factory + seeder.

## Copertura modelli

| Modello | Tabella | Migration | Factory | Seeder |
|---|---|---|---|---|
| `Bom` | `boms` | OK | `BomFactory` (esistente) | `BomSeeder` (nuovo) |
| `BomItem` | `bom_items` | OK | `BomItemFactory` (esistente) | `BomItemSeeder` (nuovo) |

Orchestratore: `BomDatabaseSeeder` chiama `BomSeeder` poi `BomItemSeeder`.

## Skip motivati

Nessuno skip. Entrambi i modelli sono concreti; nessun pivot/STI/tabella condivisa.

## Note

- I seeder sono idempotenti (early-return se la tabella ha già righe).
- `BomFactory`/`BomItemFactory` creano i `Product` collegati (modulo `Catalog`) al volo.
- `XotBaseModel` è la base astratta (modulo `Xot`), esclusa dal conteggio.
