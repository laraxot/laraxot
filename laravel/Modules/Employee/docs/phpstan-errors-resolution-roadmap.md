# Employee Module - PHPStan Level 10 Errors Resolution Roadmap

## 📊 Stato Attuale

**Data Analisi**: Gennaio 2025  
**PHPStan Level**: 10  
**Totale Errori**: 184 errori in 26 file  
**Comando**: `./vendor/bin/phpstan analyse Modules/Employee --level=10`

## 🎯 Obiettivo

Ridurre gli errori PHPStan a **0** mantenendo la funzionalità esistente e rispettando i principi DRY + KISS.

## 📈 Distribuzione Errori per Tipo

1. **method.nonObject**: 46 errori (25.0%) - Chiamate a metodi su mixed
2. **argument.type**: 37 errori (20.1%) - Problemi con tipi degli argomenti
3. **staticMethod.notFound**: 34 errori (18.5%) - Metodi statici non trovati
4. **return.type**: 20 errori (10.9%) - Problemi con tipi di ritorno
5. **method.notFound**: 18 errori (9.8%) - Metodi non trovati
6. **Altri**: 29 errori (15.8%)

## 🔍 Top 10 File con Più Errori

1. `EmployeeOverviewWidget.php` - 28 errori
2. `AttendanceOverviewWidget.php` - 24 errori
3. `TimeClockPage.php` - 18 errori
4. `WorkHourStatsWidget.php` - 17 errori
5. `TeamPresenceWidget.php` - 11 errori
6. `TodayPresenceWidget.php` - 9 errori
7. `EmployeeController.php` - 9 errori
8. `ClockOutAction.php` - 8 errori
9. `WorkHourDashboard.php` - 8 errori
10. `WorkHour.php` - 8 errori

## 🎯 Pattern di Errori Identificati

### Pattern 1: Chiamate a Metodi su Mixed (46 errori - 25.0%)

**Problema**: Metodi chiamati su variabili di tipo `mixed`.

**Causa**: 
- Query builder che restituiscono `mixed` invece di tipi specifici
- Variabili senza type hints espliciti

**Soluzione**:
- Aggiungere type hints espliciti ai risultati delle query
- Usare `@var` annotations per specificare i tipi
- Implementare type casting appropriato
- Verificare null safety prima di chiamare metodi

**Esempio**:
```php
// ❌ PRIMA
$workHour = WorkHour::where('id', $id)->first();
$workHour->calculateTotalHours(); // method.nonObject

// ✅ DOPO
/** @var WorkHour|null $workHour */
$workHour = WorkHour::where('id', $id)->first();
if ($workHour !== null) {
    $workHour->calculateTotalHours();
}
```

### Pattern 2: Problemi con Tipi degli Argomenti (37 errori - 20.1%)

**Problema**: Argomenti di tipo `array|string|null` passati dove è richiesto `string`.

**Causa**: Traduzioni che possono restituire array o string.

**Soluzione**:
- Usare `SafeStringCastAction` per le traduzioni
- Aggiungere type casting esplicito
- Verificare che le traduzioni restituiscano sempre string

**File interessati**:
- `AttendanceOverviewWidget.php` (linee 46, 49, 61, 72, 83, 97)
- `EmployeeOverviewWidget.php` (linee 57, 58, 62, 63, 67)

### Pattern 3: Metodi Statici Non Riconosciuti (34 errori - 18.5%)

**Problema**: PHPStan non riconosce metodi statici come `WorkHour::where()`, `Employee::count()`, ecc.

**Causa**: 
- Modelli che non estendono correttamente `Illuminate\Database\Eloquent\Model`
- Mancanza di `@mixin \Eloquent` nei PHPDoc
- Configurazione Larastan non corretta

**Soluzione**:
- Verificare che i modelli estendano `Model` o classi base appropriate
- Aggiungere `@mixin \Eloquent` nei PHPDoc dei modelli
- Verificare configurazione Larastan in `phpstan.neon`
- Aggiungere type hints espliciti per i risultati delle query

**File interessati**:
- `WorkHourStatsWidget.php` (linee 23, 24, 27, 32, 35)
- `EmployeeOverviewWidget.php` (linee 44, 48, 54)
- `ClockInAction.php` (linea 25)
- `ClockOutAction.php` (linea 25)

### Pattern 4: Problemi con Tipi di Ritorno (20 errori - 10.9%)

**Problema**: Funzioni che dovrebbero restituire un tipo specifico ma restituiscono `mixed` o tipi più ampi.

**Causa**: Closure anonime senza type hints o query builder che restituiscono tipi generici.

**Soluzione**:
- Aggiungere return type hints espliciti
- Usare type casting per i risultati delle query
- Verificare che i metodi restituiscano sempre il tipo atteso

**Esempio**:
```php
// ❌ PRIMA
->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No') // return.type

// ✅ DOPO
->formatStateUsing(fn ($state): string => $state ? 'Yes' : 'No')
```

## 🗺️ Roadmap di Risoluzione

### Fase 1: Fix Modelli Base (Priorità Alta)

**Obiettivo**: Risolvere i problemi con i metodi statici non riconosciuti.

**Task**:
1. Verificare che `WorkHour`, `Employee`, `TimeEntry` estendano correttamente `Model`
2. Aggiungere `@mixin \Eloquent` nei PHPDoc
3. Verificare configurazione Larastan
4. Test: Eseguire PHPStan su un singolo modello

**File da modificare**:
- `app/Models/WorkHour.php`
- `app/Models/Employee.php`
- `app/Models/TimeEntry.php`

**Tempo stimato**: 2-3 ore

### Fase 2: Fix Widgets Critici (Priorità Alta)

**Obiettivo**: Risolvere errori nei widget più utilizzati.

**Task**:
1. `EmployeeOverviewWidget.php` (28 errori)
   - Fix metodi statici non riconosciuti
   - Fix tipi degli argomenti per traduzioni
   - Fix tipi di ritorno
2. `AttendanceOverviewWidget.php` (24 errori)
   - Fix tipi degli argomenti
   - Fix metodi su mixed
   - Fix return types
3. `TimeClockPage.php` (18 errori)
   - Fix return types nelle closure
   - Fix metodi su mixed
   - Fix proprietà non trovate

**Tempo stimato**: 4-6 ore

### Fase 3: Fix Widgets Minori (Priorità Media)

**Obiettivo**: Risolvere errori nei widget rimanenti.

**Task**:
1. `WorkHourStatsWidget.php` (17 errori)
2. `TeamPresenceWidget.php` (11 errori)
3. `TodayPresenceWidget.php` (9 errori)
4. `WorkHourDashboard.php` (8 errori)

**Tempo stimato**: 3-4 ore

### Fase 4: Fix Actions (Priorità Media)

**Obiettivo**: Risolvere errori nelle Actions.

**Task**:
1. `ClockInAction.php` (6 errori)
2. `ClockOutAction.php` (8 errori)
3. `BuildTimelineVisualizationAction.php` (2 errori)
4. `ExportTimeDataAction.php` (2 errori)

**Tempo stimato**: 2-3 ore

### Fase 5: Fix Controller e Altri File (Priorità Bassa)

**Obiettivo**: Risolvere errori rimanenti.

**Task**:
1. `EmployeeController.php` (9 errori)
2. `WorkHour.php` (8 errori)
3. Altri file con errori minori

**Tempo stimato**: 2-3 ore

### Fase 6: Verifica Finale e Testing

**Obiettivo**: Verificare che tutti gli errori siano risolti.

**Task**:
1. Eseguire PHPStan completo sul modulo
2. Verificare che non ci siano regressioni
3. Eseguire test funzionali
4. Aggiornare documentazione

**Tempo stimato**: 1-2 ore

## 📝 Best Practices da Applicare

1. **Sempre usare type hints espliciti** per parametri e return types
2. **Usare `@var` annotations** per variabili di tipo mixed
3. **Verificare null safety** prima di chiamare metodi su oggetti
4. **Usare `SafeStringCastAction`** per le traduzioni
5. **Aggiungere `@mixin \Eloquent`** nei modelli
6. **Testare dopo ogni fix** per evitare regressioni

## 🔗 Collegamenti Correlati

- [PHPStan Compliance Status](./phpstan-compliance-status.md)
- [Maintenance PHPStan Fixes](./maintenance/phpstan-fixes.md)
- [Xot PHPStan Patterns](../../Xot/docs/phpstan-patterns-dec-2025.md)

## ✅ Checklist di Verifica

Prima di considerare completata la risoluzione:

- [ ] Tutti i file elencati sono stati corretti
- [ ] PHPStan Level 10 passa senza errori
- [ ] Test funzionali passano
- [ ] Documentazione aggiornata
- [ ] Code review completata

---

*Roadmap creata il: Gennaio 2025*  
*Ultimo aggiornamento: Gennaio 2025*  
*Nota: Questa roadmap aggiorna quella precedente che mostrava 110 errori*