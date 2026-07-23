# Doctor States - Business Logic Analysis

## Overview
Analisi della business logic per la gestione degli stati dei dottori nel tema Two del sistema TechPlanner.

## File Analizzato
`Themes/Two/lang/it/doctor_states.php` (e varianti multilingue)

## Business Logic

### Scopo Principale
Il file `doctor_states.php` definisce la configurazione multilingue per tutti gli stati possibili di un dottore nel sistema, includendo metadati visivi e descrittivi per ogni stato.

### Struttura Dati Stati

#### 1. **Schema Base per Stato**
```php
'state_name' => [
    'label' => 'Etichetta Umana',
    'color' => 'filament_color',
    'description' => 'Descrizione dettagliata dello stato',
    'icon' => 'heroicon-icon-name',
    'hex_color' => '#hexvalue',
],
```

**Business Logic:**
- **Label**: Testo mostrato nell'interfaccia utente
- **Color**: Colore semantico Filament (success, warning, danger)
- **Description**: Tooltip e documentazione estesa
- **Icon**: Icona Heroicon per identificazione visiva rapida
- **Hex Color**: Colore esatto per personalizzazioni CSS

#### 2. **Stati del Workflow Dottore**

##### **Stati Base**
```php
'active' => [
    'label' => 'Attivo',
    'color' => 'success',
    'description' => 'Utente attivo e operativo nel sistema',
    'icon' => 'heroicon-o-check-circle',
    'hex_color' => '#10b981',
],
'inactive' => [
    'label' => 'Inattivo',
    'color' => 'danger',
    'description' => 'Utente non attivo, accesso disabilitato',
    'icon' => 'heroicon-o-x-circle',
    'hex_color' => '#ef4444',
],
```

**Business Logic:**
- **Active**: Dottore completamente operativo, può accedere e operare
- **Inactive**: Dottore disattivato, accesso negato

##### **Stati di Moderazione**
```php
'pending' => [
    'label' => 'In attesa',
    'color' => 'warning',
    'description' => 'Registrazione in attesa di approvazione',
    'icon' => 'heroicon-o-clock',
    'hex_color' => '#f59e0b',
],
'rejected' => [
    'label' => 'Rifiutato',
    'color' => 'danger',
    'description' => 'Registrazione rifiutata dal sistema',
    'icon' => 'heroicon-o-x-circle',
    'hex_color' => '#dc2626',
],
```

**Business Logic:**
- **Pending**: Nuovo dottore in attesa di verifica credenziali
- **Rejected**: Dottore non accettato dopo revisione

##### **Stati di Integrazione**
```php
'integration_requested' => [
    'label' => 'Richiesta di integrazione',
    'color' => 'warning',
    'description' => 'Richiesta di integrazione in attesa di elaborazione',
    'icon' => 'heroicon-o-arrow-path',
    'hex_color' => '#d97706',
],
'integration_approved' => [
    'label' => 'Integrazione approvata',
    'color' => 'success',
    'description' => 'Richiesta di integrazione approvata',
    'icon' => 'heroicon-o-check-circle',
    'hex_color' => '#059669',
],
'integration_completed' => [
    'label' => 'Integrazione completata',
    'color' => 'success',
    'description' => 'Processo di integrazione completato con successo',
    'icon' => 'heroicon-o-check-circle',
    'hex_color' => '#10b981',
],
```

**Business Logic:**
- **Integration Requested**: Dottore richiede integrazione con sistemi esterni
- **Integration Approved**: Richiesta approvata, in attesa di implementazione
- **Integration Completed**: Integrazione completata e funzionante

##### **Stati Avanzati**
```php
'suspended' => [
    'label' => 'Sospeso',
    'color' => 'danger',
    'description' => 'Utente temporaneamente sospeso dal sistema',
    'icon' => 'heroicon-o-pause-circle',
    'hex_color' => '#dc2626',
],
```

**Business Logic:**
- **Suspended**: Sospensione temporanea per violazioni o motivi amministrativi

## Conflitti Git Risolti

### Problema Metadati Estesi
```php
// PRIMA (con conflitti):
'active' => [
    'label' => 'Attivo',
    'color' => 'success',
    'description' => 'Utente attivo e operativo nel sistema',
    'icon' => 'heroicon-o-check-circle',
    'hex_color' => '#10b981',
],

// DOPO (risolto):
'active' => [
    'label' => 'Attivo',
    'color' => 'success',
    'description' => 'Utente attivo e operativo nel sistema',
    'icon' => 'heroicon-o-check-circle',
    'hex_color' => '#10b981',
],
```

**Risoluzione:**
- **Metadati Estesi**: Mantenuti description, icon e hex_color
- **Completezza**: Informazioni complete per ogni stato
- **Coerenza**: Schema uniforme applicato a tutti gli stati

## Architettura e Pattern

### Design Patterns
1. **State Pattern**: Definizione esplicita di tutti gli stati possibili
2. **Configuration Pattern**: Dati esterni al codice per flessibilità
3. **I18n Pattern**: Supporto multilingue completo
4. **Metadata Pattern**: Dati ricchi per ogni stato

### Integrazione con Sistema
- **Spatie Model States**: Compatibile con package Spatie
- **Filament Components**: Integrazione nativa con UI Filament
- **Theme System**: Configurazione specifica per tema Two
- **Translation System**: Supporto Laravel Localization

## Flussi di Stato Tipici

### 1. **Registrazione Nuovo Dottore**
```
pending → (approvazione) → active
pending → (rifiuto) → rejected
```

### 2. **Processo di Integrazione**
```
active → integration_requested → integration_approved → integration_completed
active → integration_requested → integration_rejected
```

### 3. **Gestione Disciplinare**
```
active → suspended → (riabilitazione) → active
active → suspended → (escalation) → inactive
```

### 4. **Lifecycle Completo**
```
pending → active → integration_requested → integration_completed → suspended → inactive
```

## Utilizzo nel Codice

### 1. **IconStateColumn Integration**
```php
// In DoctorResource Table
IconStateColumn::make('state')
    ->label('Stato Dottore'),

// Automaticamente utilizzerà:
// - pub_theme::doctor_states.{state}.label per il testo
// - pub_theme::doctor_states.{state}.icon per l'icona
// - pub_theme::doctor_states.{state}.color per il colore
```

### 2. **Model States Configuration**
```php
// In Doctor Model
protected $casts = [
    'state' => DoctorState::class,
];

// In DoctorState
public function color(): string
{
    return __('pub_theme::doctor_states.'.$this->name().'.color');
}

public function icon(): string
{
    return __('pub_theme::doctor_states.'.$this->name().'.icon');
}
```

### 3. **Dashboard Widgets**
```php
// Statistics Widget
$activeCount = Doctor::whereState('active')->count();
$pendingCount = Doctor::whereState('pending')->count();
$suspendedCount = Doctor::whereState('suspended')->count();
```

## Considerazioni Business

### Compliance e Audit
- **Traceability**: Ogni cambio di stato dovrebbe essere tracciato
- **Approval Workflow**: Stati di approvazione per compliance
- **Audit Trail**: Log dettagliato per ogni transizione

### User Experience
- **Visual Consistency**: Colori e icone semanticamente coerenti
- **Clear Communication**: Descrizioni esplicative per ogni stato
- **Internationalization**: Supporto completo multilingue

### Operational Efficiency
- **Bulk Operations**: Possibilità di cambiare stato per gruppi di dottori
- **Automated Transitions**: Regole automatiche per alcuni passaggi
- **Notification System**: Avvisi automatici per cambi di stato critici

## Estensibilità

### Aggiunta Nuovi Stati
```php
'on_vacation' => [
    'label' => 'In Vacanza',
    'color' => 'info',
    'description' => 'Dottore temporaneamente in vacanza',
    'icon' => 'heroicon-o-sun',
    'hex_color' => '#3b82f6',
],
```

### Metadati Personalizzati
```php
'active' => [
    'label' => 'Attivo',
    'color' => 'success',
    'description' => 'Utente attivo e operativo nel sistema',
    'icon' => 'heroicon-o-check-circle',
    'hex_color' => '#10b981',
    'permissions' => ['view_patients', 'create_appointments'],
    'notification_enabled' => true,
    'auto_assign_patients' => true,
],
```

## Conclusioni

La configurazione degli stati dottore fornisce una base solida e flessibile per la gestione del ciclo di vita dei dottori nel sistema, con particolare attenzione all'esperienza utente, compliance e operazioni amministrative.