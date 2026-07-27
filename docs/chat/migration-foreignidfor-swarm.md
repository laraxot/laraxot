# Swarm: migrazioni → `foreignIdFor()`

> Handoff multi-agente. Regola: ogni FK in una `XotBaseMigration` deve usare
> `$table->foreignIdFor(Model::class, $column)` invece di `foreignId()`/`foreignUuid()`/
> `unsignedBigInteger()` manuale. Riferimenti a `users` sempre via
> `XotData::make()->getUserClass()`, mai hardcodati.
>
> SSoT: `laravel/Modules/Xot/docs/wiki/concepts/migration-foreign-id-for.md`

## Perché

`users.id` è UUID string(36), non bigint. `foreignId('user_id')->constrained('users')`
produce mismatch di tipo colonna e falliva già in produzione (vedi
`Modules/Inventory/docs/bugfix-migration-users-uuid-fk.md`,
`Modules/Production/docs/bugfix-migration-users-uuid-fk.md`). `foreignIdFor()` legge
`$model->getKeyType()` e sceglie da solo bigint vs uuid — elimina la classe di bug.

## Regole operative per ogni agente

1. **Lock prima di editare**: `bashscripts/lock/check.sh <file>` → se libero,
   `bashscripts/lock/lock.sh <file>`, edita, `bashscripts/lock/unlock.sh <file>`.
   Se locked: skip, segna qui sotto, passa al file successivo.
2. **Non cambiare nomi colonna esistenti** — passare il nome originale come secondo
   argomento a `foreignIdFor()`.
3. **Non aggiungere `->constrained()` se non c'era già** — se il modello target è su
   una connection diversa (cross-modulo), `constrained()` fa fallire la migration
   (errore MySQL 1824). Mantenere il comportamento originale (nullable/index senza
   constraint) a meno che target e migrante condividano connection.
4. **Model class esplicita per nomi irregolari** (brand-casing tipo WhatsApp, plurali
   irregolari tipo `media`) — non affidarsi al guesser di `XotBaseMigration`.
5. **Closure non-static** se chiamano `$this->updateTimestamps()` dentro `tableCreate()`.
6. Dopo le modifiche nel proprio scope: `vendor/bin/pint --dirty`,
   `./vendor/bin/phpstan analyse Modules/<Modulo> --memory-limit=-1` da `laravel/`,
   e i test Pest del modulo se esistono.

## Moduli in lavorazione (batch)

| Batch | Moduli | File coinvolti (~) | Stato |
|---|---|---|---|
| 1 | User | 23 | in corso |
| 2 | PublicProcurement, Compliance, Bom, Cms | 25 | in corso |
| 3 | TimberBilling, Intervention | 20 | in corso |
| 4 | Billing, Inventory, HR, EnergyBroker | 21 | in corso |
| 5 | WorkOrder, WhatsApp, Quotation, Job, Gdpr, Employee | 24 | in corso |
| 6 | Comment, AiAssistant, AI, Email, Customer, UI, Signature | 22 | in corso |
| 7 | Production, Notify, Lang, Geo, Catalog, Blog, Xot, Vehicle, Media, Document, Activity | 17 | in corso |

## File toccati fuori dai moduli (wiki, no lock necessario — root docs)

- `bashscripts/ai/wiki/rules/foreign-key-constraints.md` — corretto (era contraddittorio)
- `bashscripts/ai/wiki/rules/migration-rules.md` — da correggere
- `bashscripts/ai/wiki/rules/migrations.md` — da correggere
- `bashscripts/ai/wiki/rules/migration-complete-rules.md` — da correggere
- `bashscripts/ai/wiki/rules/00-TRIGGER_MAP.md` — entry da estendere

## Note

Se un agente trova un file lockato da un altro processo: non aspettare, non forzare.
Segnare qui sotto quali file sono rimasti skippati per lock, per un pass successivo.

### File skippati per lock (da riprocessare)

_nessuno finora_

### File skippati per lock (aggiornamento live)

- `laravel/Modules/PublicProcurement/database/migrations/2026_07_24_000107_create_public_procurement_document_requirements_table.php`
  — lockato da altro agente. Problema noto: FK verso `compliance_documents` (Compliance module,
  migration `2026_07_24_000303_create_compliance_documents_table.php`) creata DOPO nell'ordine
  timestamp → errore MySQL 1824 "Failed to open the referenced table" per ordering, non per
  cross-connection (entrambe le tabelle sono su connection `xot`). Fix: bump timestamp di questo
  file a dopo 000303, o sposta compliance_documents prima. Da riprocessare se ancora irrisolto.
