# TimeEntry Model Refactoring Plan

## Analisi Iniziale

Il modello `TimeEntry.php` presenta numerosi metodi ridondanti che violano il principio DRY. Sono stati identificati 40+ metodi statici (righe 107-310) che sono semplici wrapper dei metodi Eloquent di base senza aggiungere valore.

## Problemi Identificati

1. **Violazione DRY**: Metodi statici che solo chiamano `parent::method()`
2. **Code Bloat**: Il file contiene 218 righe invece delle ~60 necessarie
3. **Mantenimento**: Codice ridondante difficile da mantenere
4. **Performance**: Overhead non necessario per ogni chiamata

## Metodi da Rimuovere

Tutti i metodi statici seguenti verranno rimossi perché sono wrapper inutili:

- `create()`
- `find()`
- `query()`
- `on()`
- `where()`
- `whereDate()`
- `whereNotNull()`
- `orderBy()`
- `latest()`
- `first()`
- `firstOrFail()`
- `all()`
- `get()`
- `count()`
- `limit()`
- `join()`
- `leftJoin()`
- `rightJoin()`
- `groupBy()`
- `having()`
- `distinct()`
- `whereRaw()`
- `orWhereRaw()`
- `whereIn()`
- `whereNotIn()`
- `whereBetween()`
- `orWhereBetween()`
- `whereNotBetween()`
- `orWhereNotBetween()`
- `whereNull()`
- `orWhereNull()`
- `whereTime()`
- `orWhereTime()`
- `whereDay()`
- `whereMonth()`
- `whereYear()`
- `exists()`
- `doesntExist()`

## Metodi da Mantenere

I seguenti metodi specifici del modello verranno mantenuti:

1. **Relazioni**:
   - `employee(): BelongsTo`
   - `approvedBy(): BelongsTo`

2. **Scope**:
   - `scopePending(Builder $query): Builder`
   - `scopeForEmployee(Builder $query, int $employeeId): Builder`
   - `scopeWithAnomalies(Builder $query): Builder`

3. **Business Logic**:
   - `calculateTotalHours(): float`
   - `hasAnomalies(): bool`
   - `isApproved(): bool`
   - `isPending(): bool`
   - `isRejected(): bool`

## Piano di Azione

1. **Backup**: Creare il file `.lock` come da procedura
2. **Refactoring**: Rimuovere tutti i metodi ridondanti
3. **Testing**: Verificare con PHPStan, PHPMD e PHPInsights
4. **Documentazione**: Aggiornare la documentazione del modello

## Risultato Atteso

- File ridotto da 218 a ~60 righe
- Codice più pulito e manutenibile
- Compliance PHPStan Level 10 mantenuta
- Nessuna perdita di funzionalità

## Note Tecniche

- I metodi Eloquent rimangono disponibili tramite ereditarietà
- Non sarà necessario modificare il codice che utilizza TimeEntry
- Le query continueranno a funzionare normalmente

## Implementazione

Verrà eseguito il refactoring seguendo la metodologia "Super Mucca":
1. Analisi approfondita completata ✓
2. Documentazione del piano ✓
3. Implementazione della correzione
4. Verifica con strumenti di qualità
5. Aggiornamento documentazione finale