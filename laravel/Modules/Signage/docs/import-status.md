# Import status — Signage

Modulo **nuovo** della seconda tranche (sorgente `gestionale_commesse/Modules/Signage`).

| Sorgente (DDD-lite) | Destinazione (canonica Laraxot) |
|---|---|
| `Application/Actions` | `app/Actions` |
| `Application/DTOs` | `app/Datas` |
| `Application/Support` | `app/Support` |
| `Infrastructure/Models` | `app/Models` (estendono `XotBaseModel`) |
| `Database/Migrations` (4 file, 9 tabelle) | 8 migrazioni `XotBaseMigration`, una per tabella |
| `Filament/*` | `app/Filament/*` (resource su `GatedXotBaseResource`) |
| `routes/web.php` (rotta anteprima disegno) | `routes/web.php` del modulo |

I centri di lavoro **non** sono nel modulo: stanno nella tabella condivisa di
`Costing`, filtrati per contesto. La migrazione `sign_work_centers` non è stata
portata; `work_center_code` su `sign_price_list_items`, che il sorgente aggiungeva
con una `Schema::table` successiva, è entrata direttamente nel `create`.

## Adattamenti

- Le 5 resource avevano `form(Schema)`: convertite in `getFormSchema(): array`.
- `config/signage.php` senza `env()`; il provider registra l'alias `signage.*`.
- Le resource sono state aggiunte anche al discovery del pannello `admin`, perché
  è lì che i test (e il sorgente) costruiscono gli URL.

## Correzioni fatte strada facendo

- `ArtworkTextLayout::fromDataTm()` dava per buona la forma di quello che restituisce
  pdfparser. Ora verifica ogni voce: un .ai malformato produce meno testo, non
  un'eccezione a metà estrazione.
- Il reshape aveva sostituito `use Illuminate\Database\Eloquent\Model` con
  `XotBaseModel` anche nei RelationManager, dove `Model` serve come type hint del
  metodo padre. Ripristinato dove non è un modello.
- PHPDoc `@property` generati per tutti i modelli dalle migrazioni.

Test: **52 verdi**. PHPStan: **zero errori sul codice di produzione** (erano 129).
