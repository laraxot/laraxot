# 🕐 Sistema Timbrature Presenze

## Panoramica
Questa cartella contiene l'implementazione completa del sistema di timbrature e presenze, inclusi modelli, migrazioni, risorse Filament, componenti Livewire e documentazione.

## Struttura della Cartella

```
timbrature/
├── README.md                    # Questo file
├── models/                      # Modelli Eloquent
│   ├── Attendance.php          # Modello principale presenze
│   ├── TimeEntry.php           # Modello singola timbratura
│   └── WorkSchedule.php        # Modello orari di lavoro
├── migrations/                  # Migrazioni database
│   ├── create_attendances_table.php
│   ├── create_time_entries_table.php
│   └── create_work_schedules_table.php
├── resources/                   # Risorse Filament
│   ├── AttendanceResource.php
│   ├── TimeEntryResource.php
│   └── WorkScheduleResource.php
├── livewire/                    # Componenti Livewire
│   ├── ClockInOut.php          # Componente timbratura
│   ├── AttendanceCalendar.php   # Calendario presenze
│   └── TimeTrackingWidget.php  # Widget tracking
├── widgets/                     # Widget Filament
│   ├── ClockWidget.php         # Widget timbratura
│   ├── AttendanceStatsWidget.php # Statistiche presenze
│   └── TimeTrackingWidget.php  # Widget tracking tempo
├── seeders/                     # Seeder per dati di test
│   └── AttendanceSeeder.php
├── services/                    # Servizi business logic
│   ├── AttendanceService.php    # Servizio presenze
│   ├── TimeCalculationService.php # Calcoli ore
│   └── GeolocationService.php  # Servizio geolocalizzazione
└── documentation/               # Documentazione specifica
    ├── implementation.md        # Guida implementazione
    ├── api-reference.md        # Riferimento API
    └── geolocation-setup.md    # Setup geolocalizzazione
```

## Regole di Implementazione

### Modelli
- **Sempre estendere XotBaseModel**: `use Modules\Xot\Models\XotBaseModel;`
- **Usare trait Updater**: Per tracking modifiche
- **Implementare relazioni**: BelongsTo Employee, etc.
- **Validazioni**: Usare rules e custom validation
- **Casts**: Per dati JSON e datetime

### Migrazioni
- **Sempre estendere XotBaseMigration**: `use Modules\Xot\Database\Migrations\XotBaseMigration;`
- **Usare metodi helper**: `tableCreate()`, `tableUpdate()`, `hasColumn()`
- **Mai usare Schema:: direttamente**
- **Controllo esistenza**: Prima di aggiungere colonne
- **Indici**: Per performance su query frequenti

### Risorse Filament
- **Sempre estendere XotBaseResource**: `use Modules\Xot\Filament\Resources\XotBaseResource;`
- **Form strutturati**: Sezioni logiche
- **Validazioni**: Client e server side
- **Relazioni**: Gestire correttamente
- **Azioni**: Approvazione, rifiuto, modifica

### Componenti Livewire
- **Namespace corretto**: `Modules\Employee\Livewire`
- **Validazioni**: Real-time validation
- **Eventi**: Gestire eventi browser
- **Geolocalizzazione**: Integrazione posizione

## Funzionalità Principali

### 1. Timbratura Virtuale
- ✅ Login/logout con timestamp
- ✅ Geolocalizzazione automatica
- ✅ Validazione posizione
- ✅ Dispositivo tracking

### 2. Calcolo Ore
- ✅ Ore totali lavorate
- ✅ Straordinari automatici
- ✅ Pause e break
- ✅ Ore notturne

### 3. Approvazione Presenze
- ✅ Workflow approvazione
- ✅ Notifiche manager
- ✅ Storico modifiche
- ✅ Motivi rifiuto

### 4. Geolocalizzazione
- ✅ Tracking posizione
- ✅ Validazione sede
- ✅ Storico posizioni
- ✅ Privacy compliance

## Implementazione

### 1. Modelli
Vedi cartella `models/` per i modelli completi.

### 2. Migrazioni
Vedi cartella `migrations/` per le migrazioni database.

### 3. Risorse Filament
Vedi cartella `resources/` per le interfacce amministrative.

### 4. Componenti Livewire
Vedi cartella `livewire/` per i componenti interattivi.

### 5. Widget
Vedi cartella `widgets/` per i widget dashboard.

### 6. Servizi
Vedi cartella `services/` per la logica business.

## Testing

```bash
# Eseguire migrazioni
php artisan migrate --path=Modules/Employee/database/migrations

# Eseguire seeder
php artisan db:seed --class=Modules\\Employee\\database\\seeders\\AttendanceSeeder

# Testare modelli
php artisan test --filter=AttendanceTest

# Testare componenti Livewire
php artisan test --filter=ClockInOutTest
```

## Configurazione

### Variabili Ambiente
```env
# Geolocalizzazione
GOOGLE_MAPS_API_KEY=your_api_key
LOCATION_VALIDATION_RADIUS=1000

# Timbrature
WORK_HOURS_STANDARD=8
OVERTIME_THRESHOLD=8
BREAK_TIME_STANDARD=60
```

### Permessi File
```bash
# Permessi storage
chmod -R 755 storage/app/public/attendance
chown -R www-data:www-data storage/app/public/attendance
```

## Documentazione Correlata

- [Pattern di Estensione Filament](../../../docs/patterns/filament-extension.md)
- [Best Practices Migrazioni](../../../docs/patterns/xotbasemigration_best_practices.md)
- [Modelli Base](../../../docs/patterns/baseuser.md)
- [Geolocalizzazione Setup](./documentation/geolocation-setup.md) 