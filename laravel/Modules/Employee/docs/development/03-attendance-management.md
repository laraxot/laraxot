# 03 - Gestione Presenze (Attendance Management)

## 🎯 Obiettivo
Creare un sistema completo per gestire le presenze dei dipendenti, replicando e migliorando le funzionalità di dipendentincloud.it.

## 📋 Cosa Dobbiamo Fare

### 1. **Modello Attendance (Presenza)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Attendance.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per una presenza
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- employee_id (dipendente)
- data (data della presenza)
- tipo_presenza (normale, straordinario, permesso, malattia)

// Orari
- ora_entrata (ora di entrata)
- ora_uscita (ora di uscita)
- ora_pausa_inizio (inizio pausa pranzo)
- ora_pausa_fine (fine pausa pranzo)

// Calcoli
- ore_lavorate (ore totali lavorate)
- ore_straordinario (ore di straordinario)
- ore_permesso (ore di permesso)

// Geolocalizzazione
- lat_entrata (latitudine entrata)
- lng_entrata (longitudine entrata)
- lat_uscita (latitudine uscita)
- lng_uscita (longitudine uscita)

// Stato
- approvato (se approvato dal manager)
- approvato_da (chi ha approvato)
- approvato_il (quando è stato approvato)
- note (note aggiuntive)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000005_create_attendances_table.php`

**Cosa fare:**
- Creare la migrazione per la tabella attendances
- Definire tutti i campi con i tipi corretti
- Aggiungere indici per performance
- Aggiungere foreign keys per relazioni

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/AttendanceResource.php`

**Cosa fare:**
- Creare il resource che estende XotBaseResource
- Definire il form per inserimento/modifica
- Definire la tabella per visualizzazione
- Aggiungere filtri e azioni

### 2. **Timbratura Virtuale**

#### Passo 1: Creare Componente Livewire
**Dove:** `laravel/Modules/Employee/app/Livewire/TimeClock.php`

**Cosa fare:**
- Creare componente per timbratura virtuale
- Gestire entrata/usciuta con geolocalizzazione
- Validare orari di lavoro
- Mostrare stato attuale dipendente

#### Passo 2: Creare Vista Timbratura
**Dove:** `laravel/Modules/Employee/resources/views/livewire/time-clock.blade.php`

**Cosa fare:**
- Creare interfaccia per timbratura
- Mostrare stato attuale dipendente
- Pulsanti per entrata/usciuta
- Mappa con posizione

### 3. **Calendario Presenze**

#### Passo 1: Creare Pagina Calendario
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/AttendanceCalendar.php`

**Cosa fare:**
- Creare pagina con calendario interattivo
- Mostrare presenze per mese
- Permettere modifica diretta
- Filtri per dipendente/dipartimento

#### Passo 2: Creare Vista Calendario
**Dove:** `laravel/Modules/Employee/resources/views/filament/pages/attendance-calendar.blade.php`

**Cosa fare:**
- Integrare FullCalendar.js
- Mostrare presenze come eventi
- Permettere drag&drop per modifiche
- Tooltip con dettagli presenze

### 4. **Miglioramenti Rispetto a dipendentincloud.it**

#### Funzionalità Avanzate da Aggiungere:

**A. Geolocalizzazione Avanzata**
- Validazione posizione con Google Maps
- Controllo che il dipendente sia in sede
- Storico movimenti durante la giornata
- Mappa con percorso giornaliero

**B. Riconoscimento Biometrico**
- Integrazione con dispositivi biometrici
- Validazione impronte digitali
- Controllo volto per timbratura
- Sicurezza avanzata

**C. IoT Integration**
- Integrazione con dispositivi IoT
- Timbratura automatica con badge
- Controllo accessi automatico
- Monitoraggio presenza real-time

**D. Analytics Presenze**
- Predizione assenze
- Analisi pattern di lavoro
- Ottimizzazione turni
- Report produttività

### 5. **Workflow Approvazioni**

#### Passo 1: Creare Sistema Approvazioni
**Dove:** `laravel/Modules/Employee/app/Models/AttendanceApproval.php`

**Cosa fare:**
- Creare modello per approvazioni
- Gestire workflow multi-livello
- Notifiche automatiche
- Storico approvazioni

### 6. **Report e Analytics**

#### Passo 1: Creare Widget Analytics
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/AttendanceAnalyticsWidget.php`

**Cosa fare:**
- Grafici presenze per periodo
- Confronto con periodi precedenti
- Analisi straordinari
- Trend assenze

#### Passo 2: Creare Report Personalizzati
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/AttendanceReports.php`

**Cosa fare:**
- Report per dipendente
- Report per dipartimento
- Report per sede
- Export Excel/PDF

### 7. **Validazioni e Regole**

#### Validazioni da Implementare:
```php
// Nel modello Attendance
protected static function boot()
{
    parent::boot();
    
    static::saving(function ($attendance) {
        // Validazione orari
        if ($attendance->ora_entrata && $attendance->ora_uscita) {
            if ($attendance->ora_entrata >= $attendance->ora_uscita) {
                throw new \Exception('L\'ora di entrata deve essere precedente all\'uscita');
            }
        }
        
        // Calcolo automatico ore
        if ($attendance->ora_entrata && $attendance->ora_uscita) {
            $attendance->ore_lavorate = $this->calculateWorkHours($attendance);
        }
    });
}
```

### 8. **Traduzioni da Aggiungere**

#### File di Traduzione:
**Dove:** `laravel/Modules/Employee/lang/it/attendance.php`

```php
return [
    'title' => 'Presenze',
    'fields' => [
        'employee_id' => 'Dipendente',
        'data' => 'Data',
        'tipo_presenza' => 'Tipo Presenza',
        'ora_entrata' => 'Ora Entrata',
        'ora_uscita' => 'Ora Uscita',
        'ora_pausa_inizio' => 'Inizio Pausa',
        'ora_pausa_fine' => 'Fine Pausa',
        'ore_lavorate' => 'Ore Lavorate',
        'ore_straordinario' => 'Ore Straordinario',
        'ore_permesso' => 'Ore Permesso',
        'approvato' => 'Approvato',
        'approvato_da' => 'Approvato da',
        'note' => 'Note',
    ],
    'types' => [
        'normale' => 'Lavoro normale',
        'straordinario' => 'Straordinario',
        'permesso' => 'Permesso',
        'malattia' => 'Malattia',
    ],
    'messages' => [
        'clock_in_success' => 'Entrata registrata con successo',
        'clock_out_success' => 'Uscita registrata con successo',
        'already_clocked_in' => 'Sei già entrato oggi',
        'not_clocked_in' => 'Non hai ancora timbrato l\'entrata',
    ],
];
```

### 9. **Test da Implementare**

#### Test Unitari:
```php
// Test timbratura entrata
public function test_can_clock_in()
{
    $employee = Employee::factory()->create();
    
    $this->actingAs($employee->user);
    
    $response = $this->post('/employee/clock-in', [
        'employee_id' => $employee->id,
    ]);
    
    $response->assertSuccessful();
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employee->id,
        'data' => now()->toDateString(),
        'ora_entrata' => now()->format('H:i:s'),
    ]);
}
```

## ✅ Checklist Completamento

- [ ] Modello Attendance creato con tutti i campi
- [ ] Migrazione database creata e testata
- [ ] Resource Filament implementato
- [ ] Componente timbratura virtuale creato
- [ ] Calendario presenze implementato
- [ ] Workflow approvazioni creato
- [ ] Widget analytics implementati
- [ ] Report personalizzati creati
- [ ] Validazioni avanzate implementate
- [ ] Traduzioni aggiunte
- [ ] Test funzionali completati

## 🎯 Risultato Finale

Alla fine di questo sviluppo avrai:
1. **Sistema completo di gestione presenze** che replica dipendentincloud.it
2. **Timbratura virtuale** con geolocalizzazione
3. **Calendario interattivo** per gestione presenze
4. **Workflow approvazioni** automatizzato
5. **Analytics presenze** con grafici e statistiche
6. **Integrazione IoT** per dispositivi esterni
7. **Report personalizzati** per ogni esigenza
8. **Test coverage** completo

Il sistema sarà pronto per gestire presenze di centinaia di dipendenti con controllo avanzato e analytics predittive.

---

*File creato il: 2025-07-30*
*Modulo: Employee*
*Funzionalità: Gestione Presenze*
*Priorità: ALTA* 