# AddressItemEnum con XotBaseMigration Pattern

## Overview

Questo documento mostra come utilizzare correttamente `AddressItemEnum::columns()` e `AddressItemEnum::updateColumns()` con il pattern XotBaseMigration che separa i blocchi CREATE e UPDATE.

## Il Pattern XotBaseMigration

XotBaseMigration utilizza due blocchi distinti:

1. **CREATE block** - Per la creazione iniziale della tabella
2. **UPDATE block** - Per modifiche successive, con controlli di esistenza delle colonne

## Utilizzo Corretto

### 1. In una nuova migrazione

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Geo\Enums\AddressItemEnum;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            
            // Utilizza AddressItemEnum::columns() nel CREATE block
            // Aggiunge tutte le colonne standard dell'indirizzo
            AddressItemEnum::columns($table, true); // true = include legacy fields
            
            // Altri campi specifici
            $table->string('email')->nullable();
            
            $this->addCommonFields($table);
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            // Per modifiche sicure, usa AddressItemEnum::updateColumns()
            // Questo controlla l'esistenza delle colonne prima di aggiungerle
            AddressItemEnum::updateColumns($table, $this, true);
            
            // Altri campi aggiuntivi con controllo esplicito
            if (! $this->hasColumn('business_closed')) {
                $table->boolean('business_closed')->default(false);
            }
            
            if (! $this->hasColumn('notes')) {
                $table->text('notes')->nullable();
            }

            $this->updateTimestamps($table, true);
        });
    }
};
```

### 2. In una migrazione di update

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Geo\Enums\AddressItemEnum;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        // Solo UPDATE block - la tabella esiste già
        $this->tableUpdate(function (Blueprint $table): void {
            // Aggiungi colonne indirizzo se mancanti
            AddressItemEnum::updateColumns($table, $this);
            
            // Altri aggiornamenti
            if (! $this->hasColumn('is_verified')) {
                $table->boolean('is_verified')->default(false);
            }

            $this->updateTimestamps($table);
        });
    }
};
```

## Differenze Chiave

### AddressItemEnum::columns()
- **Uso**: Solo nel CREATE block
- **Comportamento**: Aggiunge tutte le colonne senza controlli
- **Vantaggi**: Più veloce, ideato per tabelle nuove

### AddressItemEnum::updateColumns()
- **Uso**: Solo nel UPDATE block
- **Comportamento**: Controlla l'esistenza prima di aggiungere
- **Vantaggi**: Sicuro per tabelle esistenti, idempotente

## Best Practices

### 1. Sempre usare il metodo corretto per il blocco

```php
// ✅ CORRETTO
$this->tableCreate(function (Blueprint $table): void {
    AddressItemEnum::columns($table); // CREATE block
});

$this->tableUpdate(function (Blueprint $table): void {
    AddressItemEnum::updateColumns($table, $this); // UPDATE block
});

// ❌ SBAGLIATO
$this->tableUpdate(function (Blueprint $table): void {
    AddressItemEnum::columns($table); // Errore: non controlla l'esistenza
});
```

### 2. Gestire i campi legacy

```php
// Con compatibilità legacy
AddressItemEnum::columns($table, true); // CREATE
AddressItemEnum::updateColumns($table, $this, true); // UPDATE

// Senza compatibilità legacy
AddressItemEnum::columns($table); // CREATE
AddressItemEnum::updateColumns($table, $this); // UPDATE
```

### 3. Combinare con altri controlli

```php
$this->tableUpdate(function (Blueprint $table): void {
    // Prima aggiungi le colonne indirizzo
    AddressItemEnum::updateColumns($table, $this);
    
    // Poi aggiungi campi specifici
    if (! $this->hasColumn('custom_field')) {
        $table->string('custom_field')->nullable();
    }
    
    // Infine aggiorna i timestamp
    $this->updateTimestamps($table, true);
});
```

## Pattern Avanzato

### 1. Migrazione con condizionali complesse

```php
$this->tableUpdate(function (Blueprint $table): void {
    // Aggiungi colonne indirizzo solo se servite
    if (! $this->hasColumn(AddressItemEnum::ROUTE->value)) {
        AddressItemEnum::updateColumns($table, $this);
    }
    
    // Aggiungi indici solo se le colonne esistono
    if ($this->hasColumn(AddressItemEnum::POSTAL_CODE->value) && 
        ! $this->hasIndex('idx_geo_postal_code')) {
        $table->index(AddressItemEnum::POSTAL_CODE->value, 'idx_geo_postal_code');
    }
});
```

### 2. Migrazione di refactoring

```php
$this->tableUpdate(function (Blueprint $table): void {
    // Prima aggiungi le nuove colonne
    AddressItemEnum::updateColumns($table, $this);
    
    // Poi migra i dati dalle vecchie colonne
    if ($this->hasColumn('old_address')) {
        // Logica di migrazione dati...
    }
    
    // Infine rimuovi le vecchie colonne (se necessario)
    if ($this->hasColumn('old_address')) {
        $table->dropColumn('old_address');
    }
});
```

## Filosofia del Pattern

Questo pattern segue i principi:

- **DRY**: Non ripetere la definizione delle colonne
- **KISS**: Metodi semplici e chiari per ogni contesto
- **Sicurezza**: I controlli impediscono errori in produzione
- **Flessibilità**: Supporta sia tabelle nuove che esistenti
- **Compatibilità**: Gestisce campi legacy quando necessario

## Riepilogo

| Contesto | Metodo da usare | Controlli esistenza |
|----------|-----------------|---------------------|
| CREATE block | `AddressItemEnum::columns()` | No |
| UPDATE block | `AddressItemEnum::updateColumns()` | Sì |
| Con legacy | `..., true)` | Sì/No a seconda del blocco |

Questo pattern garantisce migrazioni sicure e manutenibili, seguendo la filosofia XotBaseMigration del progetto.