# PHPStan Level 10 - Consolidated Roadmap per Moduli Minori

## 📊 Stato Generale

**Data Analisi**: Gennaio 2025  
**PHPStan Level**: 10  
**Totale Errori**: 424 errori in 67 file (moduli minori)  
**Comando**: `./vendor/bin/phpstan analyse Modules --level=10`

## 🎯 Moduli Inclusi

Questa roadmap consolidata copre i moduli con meno errori:

1. **UI**: 88 errori in 24 file
2. **Job**: 81 errori in 20 file
3. **Media**: 76 errori in 16 file
4. **TechPlanner**: 56 errori in 7 file
5. **Lang**: 30 errori in 12 file
6. **Tenant**: 8 errori in 8 file
7. **Gdpr**: 3 errori in 2 file
8. **Cms**: 1 errore in 1 file

## 📈 Distribuzione Errori per Modulo

### UI Module (88 errori)
- **argument.type**: 40 errori (45.5%)
- **return.type**: 22 errori (25.0%)
- **varTag.unresolvableType**: 13 errori (14.8%)
- **Altri**: 13 errori (14.7%)

**Top file**: `LocationSelector.php` (12 errori)

### Job Module (81 errori)
- **argument.type**: 26 errori (32.1%)
- **method.nonObject**: 16 errori (19.8%)
- **method.notFound**: 13 errori (16.0%)
- **Altri**: 26 errori (32.1%)

**Top file**: `JobStatsOverview.php` e `Task.php` (11 errori ciascuno)

### Media Module (76 errori)
- **argument.type**: 26 errori (34.2%)
- **method.nonObject**: 15 errori (19.7%)
- **return.type**: 9 errori (11.8%)
- **Altri**: 26 errori (34.3%)

**Top file**: `S3Test.php` (18 errori)

### TechPlanner Module (56 errori)
- **method.nonObject**: 16 errori (28.6%)
- **nullCoalesce.variable**: 9 errori (16.1%)
- **property.nonObject**: 8 errori (14.3%)
- **Altri**: 23 errori (41.0%)

**Top file**: `Client.php` (17 errori)

### Lang Module (30 errori)
- **argument.type**: 6 errori (20.0%)
- **return.type**: 5 errori (16.7%)
- **method.nonObject**: 4 errori (13.3%)
- **Altri**: 15 errori (50.0%)

**Top file**: `TranslationData.php` (5 errori)

### Tenant Module (8 errori)
- **method.notFound**: 7 errori (87.5%)
- **class.notFound**: 1 errore (12.5%)

**Top file**: `Tenant.php` (1 errore)

### Gdpr Module (3 errori)
- **return.type**: 2 errori (66.7%)
- **argument.type**: 1 errore (33.3%)

**Top file**: `HasGdpr.php` (2 errori)

### Cms Module (1 errore)
- **staticMethod.notFound**: 1 errore (100%)

**Top file**: `CmsMassSeeder.php` (1 errore)

## 🎯 Pattern Comuni Identificati

### Pattern 1: Problemi con Tipi degli Argomenti (UI, Job, Media)

**Soluzione**:
- Usare `SafeStringCastAction` per le traduzioni
- Aggiungere type casting esplicito
- Verificare null safety

### Pattern 2: Chiamate a Metodi su Mixed (Job, Media, TechPlanner)

**Soluzione**:
- Aggiungere type hints espliciti
- Usare `@var` annotations
- Implementare type casting appropriato

### Pattern 3: Problemi con Tipi di Ritorno (UI, Media, Lang, Gdpr)

**Soluzione**:
- Aggiungere return type hints espliciti
- Usare type casting per i risultati
- Verificare che i metodi restituiscano sempre il tipo atteso

## 🗺️ Roadmap di Risoluzione Consolidata

### Fase 1: Fix Moduli con Più Errori (Priorità Alta)

**Obiettivo**: Risolvere errori nei moduli UI, Job, Media.

**Task**:
1. **UI Module** (88 errori)
   - Fix tipi degli argomenti (40 errori)
   - Fix return types (22 errori)
   - Fix varTag (13 errori)
2. **Job Module** (81 errori)
   - Fix tipi degli argomenti (26 errori)
   - Fix metodi su mixed (16 errori)
   - Fix metodi non trovati (13 errori)
3. **Media Module** (76 errori)
   - Fix tipi degli argomenti (26 errori)
   - Fix metodi su mixed (15 errori)
   - Fix return types (9 errori)

**Tempo stimato**: 8-12 ore

### Fase 2: Fix Moduli Medi (Priorità Media)

**Obiettivo**: Risolvere errori nei moduli TechPlanner e Lang.

**Task**:
1. **TechPlanner Module** (56 errori)
   - Fix metodi su mixed (16 errori)
   - Fix nullCoalesce (9 errori)
   - Fix proprietà su mixed (8 errori)
2. **Lang Module** (30 errori)
   - Fix tipi degli argomenti (6 errori)
   - Fix return types (5 errori)
   - Fix metodi su mixed (4 errori)

**Tempo stimato**: 4-6 ore

### Fase 3: Fix Moduli Minori (Priorità Bassa)

**Obiettivo**: Risolvere errori nei moduli rimanenti.

**Task**:
1. **Tenant Module** (8 errori)
   - Fix metodi non trovati (7 errori)
   - Fix classe non trovata (1 errore)
2. **Gdpr Module** (3 errori)
   - Fix return types (2 errori)
   - Fix tipo argomento (1 errore)
3. **Cms Module** (1 errore)
   - Fix metodo statico non trovato (1 errore)

**Tempo stimato**: 2-3 ore

### Fase 4: Verifica Finale e Testing

**Obiettivo**: Verificare che tutti gli errori siano risolti.

**Task**:
1. Eseguire PHPStan completo su tutti i moduli
2. Verificare che non ci siano regressioni
3. Eseguire test funzionali
4. Aggiornare documentazione

**Tempo stimato**: 2-3 ore

## 📝 Best Practices da Applicare

1. **Sempre usare type hints espliciti** per parametri e return types
2. **Usare `@var` annotations** per variabili di tipo mixed
3. **Verificare null safety** prima di chiamare metodi su oggetti
4. **Usare `SafeStringCastAction`** per le traduzioni
5. **Testare dopo ogni fix** per evitare regressioni

## 🔗 Collegamenti Correlati

- [Employee PHPStan Roadmap](../Modules/Employee/docs/phpstan-errors-resolution-roadmap.md)
- [User PHPStan Roadmap](../Modules/User/docs/phpstan-errors-resolution-roadmap.md)
- [Xot PHPStan Roadmap](../Modules/Xot/docs/phpstan-errors-resolution-roadmap.md)
- [Geo PHPStan Roadmap](../Modules/Geo/docs/phpstan-errors-resolution-roadmap.md)
- [Notify PHPStan Roadmap](../Modules/Notify/docs/phpstan-errors-resolution-roadmap.md)

## ✅ Checklist di Verifica

Prima di considerare completata la risoluzione:

- [ ] Tutti i moduli elencati sono stati corretti
- [ ] PHPStan Level 10 passa senza errori su tutti i moduli
- [ ] Test funzionali passano
- [ ] Documentazione aggiornata
- [ ] Code review completata

---

*Roadmap creata il: Gennaio 2025*  
*Ultimo aggiornamento: Gennaio 2025*