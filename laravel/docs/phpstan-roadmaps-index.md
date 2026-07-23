# PHPStan Level 10 - Roadmaps Index

## 📊 Stato Generale

**Data Analisi**: Gennaio 2025  
**PHPStan Level**: 10  
**Totale Errori**: 1438 errori in 330 file  
**Comando**: `./vendor/bin/phpstan analyse Modules --level=10`

## 🗺️ Roadmap Create

### Moduli Principali (con roadmap dedicate)

1. **User Module** - 339 errori in 97 file
   - Roadmap: [Modules/User/docs/phpstan-errors-resolution-roadmap.md](../Modules/User/docs/phpstan-errors-resolution-roadmap.md)
   - Pattern principali: argument.type (135), staticMethod.notFound (50), method.nonObject (43)

2. **Xot Module** - 242 errori in 63 file ⚠️ **CRITICO**
   - Roadmap: [Modules/Xot/docs/phpstan-errors-resolution-roadmap.md](../Modules/Xot/docs/phpstan-errors-resolution-roadmap.md)
   - Pattern principali: argument.type (127), method.nonObject (25), return.type (21)
   - **IMPORTANTE**: Fornisce classi base per tutti gli altri moduli

3. **Geo Module** - 189 errori in 20 file
   - Roadmap: [Modules/Geo/docs/phpstan-errors-resolution-roadmap.md](../Modules/Geo/docs/phpstan-errors-resolution-roadmap.md)
   - Pattern principali: method.nonObject (51), argument.type (29), property.nonObject (26)
   - **File critico**: `AddressItemEnum.php` (67 errori)

4. **Employee Module** - 184 errori in 26 file
   - Roadmap: [Modules/Employee/docs/phpstan-errors-resolution-roadmap.md](../Modules/Employee/docs/phpstan-errors-resolution-roadmap.md)
   - Pattern principali: method.nonObject (46), argument.type (37), staticMethod.notFound (34)

5. **Notify Module** - 141 errori in 34 file
   - Roadmap: [Modules/Notify/docs/phpstan-errors-resolution-roadmap.md](../Modules/Notify/docs/phpstan-errors-resolution-roadmap.md)
   - Pattern principali: argument.type (66), property.nonObject (19), method.nonObject (15)

### Moduli Minori (roadmap consolidata)

6. **UI Module** - 88 errori in 24 file
7. **Job Module** - 81 errori in 20 file
8. **Media Module** - 76 errori in 16 file
9. **TechPlanner Module** - 56 errori in 7 file
10. **Lang Module** - 30 errori in 12 file
11. **Tenant Module** - 8 errori in 8 file
12. **Gdpr Module** - 3 errori in 2 file
13. **Cms Module** - 1 errore in 1 file

- Roadmap consolidata: [phpstan-modules-consolidated-roadmap.md](./phpstan-modules-consolidated-roadmap.md)

## 📈 Distribuzione Errori per Tipo (Totale)

1. **argument.type**: ~500 errori (35%) - Problemi con tipi degli argomenti
2. **method.nonObject**: ~200 errori (14%) - Chiamate a metodi su mixed
3. **staticMethod.notFound**: ~150 errori (10%) - Metodi statici non riconosciuti
4. **return.type**: ~120 errori (8%) - Problemi con tipi di ritorno
5. **method.notFound**: ~100 errori (7%) - Metodi non trovati
6. **property.nonObject**: ~80 errori (6%) - Accesso a proprietà su mixed
7. **Altri**: ~288 errori (20%)

## 🎯 Pattern Comuni Identificati

### Pattern 1: Problemi con Tipi degli Argomenti (35% degli errori)

**Causa**: Traduzioni che possono restituire `array|string|null` invece di `string`.

**Soluzione**:
- Usare `SafeStringCastAction` per le traduzioni
- Aggiungere type casting esplicito con `(string)` o `strval()`
- Verificare null safety prima di passare argomenti

### Pattern 2: Chiamate a Metodi su Mixed (14% degli errori)

**Causa**: Query builder che restituiscono `mixed` invece di tipi specifici.

**Soluzione**:
- Aggiungere type hints espliciti ai risultati delle query
- Usare `@var` annotations per specificare i tipi
- Implementare type casting appropriato
- Verificare null safety prima di chiamare metodi

### Pattern 3: Metodi Statici Non Riconosciuti (10% degli errori)

**Causa**: Modelli che non hanno `@mixin \Eloquent` o configurazione Larastan non corretta.

**Soluzione**:
- Verificare che tutti i modelli estendano `Model` o classi base appropriate
- Aggiungere `@mixin \Eloquent` nei PHPDoc dei modelli
- Verificare configurazione Larastan in `phpstan.neon`

## 🗺️ Strategia di Risoluzione Consigliata

### Fase 1: Fix Moduli Base (Priorità Critica)

1. **Xot Module** - Deve essere risolto per primo perché fornisce le classi base
2. **User Module** - Modulo critico per l'autenticazione

### Fase 2: Fix Moduli Business (Priorità Alta)

3. **Employee Module** - Modulo business critico
4. **Geo Module** - Modulo con file critico (`AddressItemEnum.php`)
5. **Notify Module** - Modulo di comunicazione

### Fase 3: Fix Moduli Minori (Priorità Media)

6. UI, Job, Media, TechPlanner, Lang, Tenant, Gdpr, Cms

## 📝 Best Practices Applicabili a Tutti i Moduli

1. **Sempre usare type hints espliciti** per parametri e return types
2. **Usare `@var` annotations** per variabili di tipo mixed
3. **Verificare null safety** prima di chiamare metodi su oggetti
4. **Usare `SafeStringCastAction`** per le traduzioni
5. **Aggiungere `@mixin \Eloquent`** nei modelli
6. **Testare dopo ogni fix** per evitare regressioni
7. **Documentare i cambiamenti** nelle classi base

## 🔗 Collegamenti alle Roadmap

### Roadmap Moduli Principali

- [Employee Module](../Modules/Employee/docs/phpstan-errors-resolution-roadmap.md)
- [User Module](../Modules/User/docs/phpstan-errors-resolution-roadmap.md)
- [Xot Module](../Modules/Xot/docs/phpstan-errors-resolution-roadmap.md) ⚠️ **CRITICO**
- [Geo Module](../Modules/Geo/docs/phpstan-errors-resolution-roadmap.md)
- [Notify Module](../Modules/Notify/docs/phpstan-errors-resolution-roadmap.md)

### Roadmap Moduli Minori

- [Consolidated Roadmap](./phpstan-modules-consolidated-roadmap.md)

## ✅ Checklist Generale

Prima di considerare completata la risoluzione:

- [ ] Tutti i moduli hanno roadmap create
- [ ] PHPStan Level 10 passa senza errori su tutti i moduli
- [ ] Test funzionali passano
- [ ] Documentazione aggiornata
- [ ] Code review completata
- [ ] Verificato che non ci siano regressioni

## 📊 Progress Tracking

Per tracciare il progresso, aggiornare questo documento con:

- Data di inizio risoluzione per ogni modulo
- Numero di errori risolti
- Numero di errori rimanenti
- Data di completamento

---

*Documento creato il: Gennaio 2025*  
*Ultimo aggiornamento: Gennaio 2025*