# English Naming Standards - Employee Module

## ⚠️ REGOLA CRITICA ASSOLUTA ⚠️

**MAI usare parole italiane nei nomi di classi, file, metodi, variabili o qualsiasi identificatore del codice.**

## Errore Critico Identificato

### ❌ Errore Commesso
```php
\Modules\Employee\Filament\Widgets\TimbratureWidget::class  // ❌ GRAVISSIMO
```

### ✅ Correzione Implementata  
```php
\Modules\Employee\Filament\Widgets\TimeClockWidget::class  // ✅ CORRETTO
```

## Motivazioni della Regola

### 1. **Standard Internazionali**
- Laravel usa inglese per tutto
- Filament usa inglese per tutto
- PSR standards richiedono inglese
- Comunità internazionale

### 2. **Manutenibilità**
- Codice leggibile da sviluppatori globali
- Documentazione ricercabile
- Collaborazione facilitata
- Standard dell'industria

### 3. **Professionalità**
- Qualità enterprise del codice
- Conformità best practices
- Scalabilità internazionale
- Credibilità tecnica

### 4. **Separazione Responsabilità**
- **Codice**: SEMPRE inglese
- **Traduzioni**: Italiano nei file `lang/`
- **Documentazione**: Può essere italiana
- **Commenti**: Preferibilmente inglesi

## Dizionario Traduzioni Obbligatorie

### Modulo Employee - Termini Corretti

#### ✅ Classi e File
```php
// HR/Employee Domain
TimeTrackingWidget    ← Timbrature
EmployeeResource      ← Dipendenti  
AttendancePage        ← Presenze
LeaveRequest          ← Ferie
TimeOffWidget         ← Permessi
OvertimeCalculator    ← Straordinari
ShiftManager          ← Turni
DepartmentResource    ← Dipartimenti

// General Domain
UserController        ← Utenti
RoleResource          ← Ruoli
PermissionPolicy      ← Autorizzazioni
SettingsPage          ← Configurazioni
NotificationService   ← Notifiche
ReportGenerator       ← Rapporti
```

#### ✅ Metodi
```php
// Time Tracking
getTimeEntries()      ← getTimbrate()
clockIn()             ← timbraEntrata()
clockOut()            ← timbraUscita()
calculateHours()      ← calcolaOre()

// Employee Management
getEmployees()        ← getDipendenti()
checkAttendance()     ← verificaPresenza()
approveLeave()        ← approvaFerie()
calculateSalary()     ← calcolaSalario()
```

#### ✅ Variabili
```php
// Collections
$employees            ← $dipendenti
$timeEntries          ← $timbrature
$attendanceRecords    ← $presenze
$leaveRequests        ← $richiesteferie

// Values  
$workedHours          ← $oreLavorate
$overtimeHours        ← $oreStraordinarie
$leaveBalance         ← $saldoFerie
$employeeCount        ← $numeroDipendenti
```

## Implementazione Correzione

### 1. Widget Corretto Creato
- ✅ **TimeClockWidget.php** - Nome inglese corretto
- ✅ **Layout 3 colonne** - Esatto dall'immagine
- ✅ **Componenti Filament** - `x-filament::button` nativi
- ✅ **Logica reale** - Database queries, no mock

### 2. Dashboard Aggiornato
```php
// ✅ CORRETTO
\Modules\Employee\Filament\Widgets\TimeClockWidget::class

// ❌ RIMOSSO
\Modules\Employee\Filament\Widgets\TimbratureWidget::class
```

### 3. Documentazione Aggiornata
- ✅ **english_naming_standards.md** - Questo documento
- ✅ **Regole permanenti** - `.cursor/rules/`
- ✅ **Memoria aggiornata** - Prevenzione futura

## Controlli di Qualità

### Checklist Naming
- [ ] Tutti i nomi di classi in inglese
- [ ] Tutti i metodi in inglese
- [ ] Tutte le variabili in inglese
- [ ] Tutti i file in inglese
- [ ] Nessuna eccezione salvo ultima spiaggia

### Script di Validazione
```bash
# Controlla nomi italiani nel codice
function check_italian_names() {
    echo "Checking for Italian names in code..."
    
    # Classi
    grep -r "class.*[A-Z][a-z]*\(Timbr\|Dipend\|Presenz\|Utent\)" . --include="*.php"
    
    # Metodi  
    grep -r "function.*\(timbr\|dipend\|presenz\|utent\)" . --include="*.php"
    
    # Variabili
    grep -r "\$\(timbr\|dipend\|presenz\|utent\)" . --include="*.php"
    
    echo "Check completed."
}
```

## Prevenzione Futura

### 1. Pre-commit Hook
```bash
#!/bin/bash
# .git/hooks/pre-commit

if grep -r -E "(class|function|\\$).*(Timbr|Dipend|Presenz|Utent)" . --include="*.php"; then
    echo "❌ ERRORE: Nomi italiani trovati nel codice!"
    echo "Correggere prima del commit"
    exit 1
fi
```

### 2. IDE Settings
- **Configurare spell checker** per identificatori inglesi only
- **Template di codice** con nomi inglesi
- **Snippet** con pattern corretti

### 3. Code Review
- **Checklist obbligatoria**: Verifica nomi inglesi
- **Automated checks**: CI/CD pipeline
- **Team guidelines**: Standard condivisi

## Impatto della Correzione

### Prima (Errore)
- ❌ `TimbratureWidget` - Nome italiano
- ❌ Standard compromessi
- ❌ Manutenibilità ridotta
- ❌ Professionalità questionabile

### Dopo (Correzione)
- ✅ `TimeClockWidget` - Nome inglese professionale
- ✅ Standard internazionali rispettati
- ✅ Codice manutenibile e scalabile
- ✅ Qualità enterprise

---

**ERRORE CORRETTO**: TimbratureWidget → TimeClockWidget  
**REGOLE AGGIORNATE**: Prevenzione automatica  
**STANDARD**: Solo inglese per identificatori codice  
**STATUS**: ✅ **COMPLETAMENTE RISOLTO**

Questa regola è ora permanentemente implementata per prevenire errori futuri!

## Collegamenti

- [Laraxot Naming Conventions](../naming-standards.md)
- [English Naming Rule](.cursor/rules/english-naming-critical-rule.mdc)
- [Professional Standards](../README.md#naming-standards)

*Ultimo aggiornamento: Gennaio 2025*