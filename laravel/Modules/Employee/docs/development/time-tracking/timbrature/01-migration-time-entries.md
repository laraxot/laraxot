# 🗄️ Migration: Time Entries (Timbrature)

## 📋 Obiettivo

Creare la tabella `time_entries` per registrare tutte le timbrature dei dipendenti (entrata, uscita, pause).

## 🏗️ Struttura Migration

### File: `database/migrations/2025_01_01_000001_create_time_entries_table.php`

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Class CreateTimeEntriesTable.
 * 
 * Migration per creare la tabella time_entries che gestisce
 * tutte le timbrature dei dipendenti (entrata, uscita, pause).
 */
return new class extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->string('id', 36)->primary();
                
                // Relazione con dipendente
                $table->string('employee_id', 36);
                $table->foreign('employee_id')
                      ->references('id')
                      ->on('employees')
                      ->onDelete('cascade');
                
                // Tipo di timbratura
                $table->enum('type', [
                    'clock_in',      // Entrata
                    'clock_out',     // Uscita
                    'break_start',   // Inizio pausa
                    'break_end',     // Fine pausa
                    'lunch_start',   // Inizio pranzo
                    'lunch_end'      // Fine pranzo
                ]);
                
                // Data e ora della timbratura
                $table->timestamp('timestamp');
                
                // Posizione GPS
                $table->decimal('location_lat', 10, 8)->nullable();
                $table->decimal('location_lng', 11, 8)->nullable();
                $table->string('location_name')->nullable();
                $table->decimal('location_accuracy', 8, 2)->nullable(); // Precisione GPS in metri
                
                // Informazioni dispositivo
                $table->string('device_type')->nullable(); // mobile, desktop, tablet
                $table->string('device_info')->nullable(); // User agent
                $table->string('ip_address', 45)->nullable();
                
                // Foto timbratura (opzionale)
                $table->string('photo_path')->nullable();
                
                // Note del dipendente
                $table->text('notes')->nullable();
                
                // Stato approvazione
                $table->enum('status', [
                    'pending',    // In attesa
                    'approved',   // Approvata
                    'rejected',   // Rifiutata
                    'auto_approved' // Approvata automaticamente
                ])->default('pending');
                
                // Approvazione
                $table->string('approved_by', 36)->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('approval_notes')->nullable();
                
                // Metadati aggiuntivi
                $table->json('metadata')->nullable(); // Dati extra (temperatura, beacon, etc.)
                
                // Soft deletes
                $table->softDeletes();
                
                // Indici per performance
                $table->index(['employee_id', 'timestamp']);
                $table->index(['employee_id', 'type']);
                $table->index(['timestamp']);
                $table->index(['status']);
            }
        );
        
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                // Aggiungi campi se non esistono
                if (! $this->hasColumn('location_accuracy')) {
                    $table->decimal('location_accuracy', 8, 2)
                          ->nullable()
                          ->after('location_name');
                }
                
                if (! $this->hasColumn('device_type')) {
                    $table->string('device_type')
                          ->nullable()
                          ->after('location_accuracy');
                }
                
                if (! $this->hasColumn('ip_address')) {
                    $table->string('ip_address', 45)
                          ->nullable()
                          ->after('device_info');
                }
                
                if (! $this->hasColumn('metadata')) {
                    $table->json('metadata')
                          ->nullable()
                          ->after('approval_notes');
                }
                
                // Aggiorna timestamps
                $this->updateTimestamps($table, true);
            }
        );
    }
};
```

## 🔧 Campi Spiegati

### 📊 Campi Principali

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `id` | string(36) | ID univoco timbratura |
| `employee_id` | string(36) | Riferimento al dipendente |
| `type` | enum | Tipo timbratura (entrata/uscita/pausa) |
| `timestamp` | timestamp | Data e ora precisa |

### 📍 Geolocalizzazione

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `location_lat` | decimal(10,8) | Latitudine GPS |
| `location_lng` | decimal(11,8) | Longitudine GPS |
| `location_name` | string | Nome location (es. "Ufficio Milano") |
| `location_accuracy` | decimal(8,2) | Precisione GPS in metri |

### 📱 Dispositivo

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `device_type` | string | Tipo dispositivo (mobile/desktop/tablet) |
| `device_info` | string | User agent completo |
| `ip_address` | string(45) | Indirizzo IP |

### ✅ Approvazione

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `status` | enum | Stato (pending/approved/rejected/auto_approved) |
| `approved_by` | string(36) | Chi ha approvato |
| `approved_at` | timestamp | Quando approvata |
| `approval_notes` | text | Note approvazione |

## 🎯 Indici per Performance

```sql
-- Ricerca timbrature per dipendente e data
INDEX (employee_id, timestamp)

-- Ricerca per tipo timbratura
INDEX (employee_id, type)

-- Ricerca per data
INDEX (timestamp)

-- Filtro per stato
INDEX (status)
```

## 🔄 Esempi di Utilizzo

### Timbratura Entrata
```php
TimeEntry::create([
    'employee_id' => 'emp-123',
    'type' => 'clock_in',
    'timestamp' => now(),
    'location_lat' => 45.4642,
    'location_lng' => 9.1900,
    'location_name' => 'Ufficio Milano',
    'device_type' => 'mobile',
    'status' => 'auto_approved'
]);
```

### Timbratura con Foto
```php
TimeEntry::create([
    'employee_id' => 'emp-123',
    'type' => 'clock_out',
    'timestamp' => now(),
    'photo_path' => 'timesheet/photos/emp-123-20250130-1800.jpg',
    'notes' => 'Uscita fine turno',
    'status' => 'pending'
]);
```

## 🚀 Prossimi Passi

1. ✅ **Migration creata**
2. ⏭️ **Creare Model TimeEntry**
3. ⏭️ **Creare Service TimeEntryService**
4. ⏭️ **Creare Filament Resource**
5. ⏭️ **Creare API endpoints**

## 📝 Note Implementazione

- **UUID**: Usa string(36) per compatibilità con sistema esistente
- **Soft Deletes**: Mantiene storico anche se cancellato
- **JSON Metadata**: Permette estensioni future senza modificare schema
- **Indici**: Ottimizzati per query più comuni (per dipendente e data)
- **Enum Values**: Chiari e auto-esplicativi
- **Foreign Keys**: Con cascade delete per integrità referenziale
