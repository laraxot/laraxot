# 🚨 REGOLA ASSOLUTA: Solo Nomi in Inglese nel Codice 🚨

## Filosofia Fondamentale

### Perché SOLO Inglese nel Codice
- **Standard Internazionale**: L'inglese è la lingua universale dello sviluppo software
- **Manutenibilità**: Codice comprensibile a sviluppatori di tutto il mondo
- **Consistenza**: Uniformità in tutto il codebase
- **Collaborazione**: Facilità di collaborazione con team internazionali
- **Documentazione**: Allineamento con documentazione e commenti

## Regole di Denominazione

### ✅ CORRETTO (Inglese)
```php
// Classi
class EmployeeTimeTrackerWidget
class WorkHourManagementService
class AttendanceReportGenerator

// Metodi
public function calculateOvertimeHours()
public function processPayroll()
public function generateMonthlyReport()

// Variabili
$employeeShiftSchedule
$weeklyAttendanceRecords
$payrollProcessingStatus
```

### ❌ ERRATO (Italiano o misto)
```php
// Classi
class TimbratureWidget // ❌ VIETATO
class GestioneOreLavoro // ❌ VIETATO  
class GeneratoreReportPresenze // ❌ VIETATO

// Metodi
public function calcolaOreStraordinario() // ❌ VIETATO
public function elaboraBustePaga() // ❌ VIETATO
public function generaReportMensile() // ❌ VIETATO

// Variabili
$orarioTurnoDipendente // ❌ VIETATO
$registriPresenzeSettimanali // ❌ VIETATO
$statoElaborazionePaghe // ❌ VIETATO
```

## Eccezioni Consentite

### 1. Dominio Specifico (Solo se necessario)
```php
// Termini di dominio specifico che non hanno traduzione diretta
$partitaIva // ✅ Permesso (termine fiscale specifico)
$codiceFiscale // ✅ Permesso (termine amministrativo specifico)
```

### 2. Configurazione e Dati
```php
// File di configurazione e dati possono usare contesto locale
'comune' => 'Roma', // ✅ Permesso (dato geografico)
'provincia' => 'RM', // ✅ Permesso (dato amministrativo)
```

### 3. Interfaccia Utente
```php
// Le traduzioni per l'UI sono separate e gestite via lang files
__('employee::messages.welcome_message') // ✅ Permesso (traduzione UI)
@lang('auth.login.title') // ✅ Permesso (traduzione UI)
```

## Enforcement Automatico

### Pre-commit Hook
```bash
#!/bin/bash
# Verifica assenza di termini italiani nel codice

ITALIAN_KEYWORDS=(
    "timbrature" "orelavoro" "bustepaga" "presenze"
    "dipendente" "turno" "straordinario" "ferie"
    "permesso" "malattia" "contratto" "assunzione"
    "dimissioni" "trasferta" "missione" "rendiconto"
)

for keyword in "${ITALIAN_KEYWORDS[@]}"; do
    if grep -r -i "$keyword" app/ --include="*.php" | grep -v "__(" | grep -v "@lang"; then
        echo "❌ ERRORE: Trovato termine italiano '$keyword' nel codice"
        echo "Usare sempre nomi in inglese per classi, metodi e variabili"
        exit 1
    fi
done
```

### CI/CD Check
```yaml
# .github/workflows/code-quality.yml
jobs:
  check-english-naming:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Check for Italian terms in code
        run: |
          if grep -r -i "timbrature\|orelavoro\|bustepaga" app/ --include="*.php" | grep -v "__(" | grep -v "@lang"; then
            echo "::error::Italian terms found in code - use English only"
            exit 1
          fi
```

## Best Practices

### 1. Nomi Descritttivi e Chiari
```php
// ✅ Buoni esempi
class EmployeeShiftScheduler
class PayrollCalculator
class AttendanceTrackingService
class OvertimeAuthorizationHandler

// ❌ Cattivi esempi  
class GestoreOre // Troppo generico
class CalcolatorePaghe // Italiano
class Service1 // Non descrittivo
```

### 2. Consistent Naming Patterns
```php
// Pattern: [Entity][Action][Type]
EmployeeTimeTrackerWidget // Entity: Employee, Action: TimeTrack, Type: Widget
WorkHourReportGenerator  // Entity: WorkHour, Action: Report, Type: Generator
AttendanceRecordExporter // Entity: Attendance, Action: Record, Type: Exporter
```

### 3. Avoid Abbreviations
```php
// ✅ Chiaro e descrittivo
EmployeeAttendanceManagementSystem

// ❌ Troppo abbreviato
EmpAttMgrSys
```

## Risoluzione Problemi Comuni

### Problema: "Non conosco la traduzione esatta"
**Soluzione**: Usare dizionario tecnico o chiedere aiuto, MAI usare italiano

### Problema: "Il termine italiano è più preciso"  
**Soluzione**: Trovare equivalente inglese o usare descrizione più lunga

### Problema: "Legacy code con nomi italiani"
**Soluzione**: Refactoring graduale con priorità alle nuove feature

## Strumenti di Supporto

### Dizionario Consigliato
```
Italiano            -> Inglese
----------------------------
timbratura          -> timeEntry, clockEvent
ore lavoro          -> workHours, workingHours  
busta paga          -> paycheck, payrollSlip
presenza            -> attendance, presence
dipendente          -> employee
turno               -> shift
straordinario       -> overtime
ferie               -> vacation, timeOff
permesso            -> leave, permission
malattia            -> sickLeave
contratto           -> contract
assunzione          -> hiring, recruitment
dimissioni          -> resignation
trasferta           -> businessTrip
missione            -> assignment, mission
rendiconto         -> report, accounting
```

### Extension VSCode
- **Code Spell Checker**: Evita errori di ortografia
- **English Dictionary**: Correzione automatica
- **SonarLint**: Analisi qualità codice

## Checklist Pre-Commit

- [ ] **Nessun** termine italiano in classi, metodi, variabili
- [ ] **Nessun** commento in italiano (tranne casi specifici documentati)
- [ ] **Nomi** descrittivi e in inglese
- [ ] **Pattern** di naming consistenti
- [ ] **Documentazione** allineata con nomi inglesi

---
**Priorità**: 🔥 MASSIMA
**Enforcement**: ✅ AUTOMATICO  
**Eccezioni**: 🟡 LIMITATE (solo dominio specifico)
**Data**: Settembre 2025
**Stato**: ✅ IMPLEMENTATO