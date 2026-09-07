# BMAD Story — ViewRecord delega l'infolist alla Resource

## Understand
- Richiesta utente: chi estende `XotBaseViewRecord` non deve dichiarare `getInfolistSchema()`.
- Errore segnalato: `Geo\Filament\Resources\Pages\ViewLocation` ha un `#[\Override]` senza metodo parent.
- `Filament\Resources\Pages\ViewRecord::infolist()` delega già alla Resource; gli schemi risiedono in `Schemas/*Infolist`.
- Working tree già modificato da altri task: preservare ogni modifica estranea; patch solo in avanti, nessun commit.

## Plan
1. Censire i metodi effettivi nelle sottoclassi, inclusi i parent intermedi; validazione indipendente read-only.
2. Rimuovere i metodi obsoleti e gli import divenuti inutilizzati, mantenendo azioni e logica restante.
3. Verificare gli schemi Resource e proteggere il contratto con un test architetturale senza database.
4. Eseguire lint PHP e quality gate mirati; documentare eventuali blocchi preesistenti.

## Implement
- Owner codice e documentazione: Codex principale.
- Reviewer: agente separato, solo lettura, validazione story e rischi della rimozione.

## Verify
- Audit AST su tutte le sottoclassi di XotBaseViewRecord.
- PHP lint, PHPStan, PHPMD, PHPInsights, Pest sullo scope modificato.
- Smoke autoload/bootstrap se disponibile.

## Document
- [Architettura Xot](../wiki/filament-tables-schemas-architecture.md).
- [Regola canonica](../../../../../docs/wiki/filament-canonical-structure.md).
- [Handoff](../../../../../docs/chat/2026-09-07-view-record-resource-infolist-only.md).

## Status
- Branch: dev.
- Moduli: Xot (contratto), Geo (errore segnalato), pagine degli altri moduli che violano il contratto.
- Stato: analisi; validazione indipendente richiesta.
- Retrieval: healthcheck e wrapper QMD documentati assenti; QMD globale non avviabile per ABI Node/better-sqlite3. Fallback rg e lettura diretta wiki.
