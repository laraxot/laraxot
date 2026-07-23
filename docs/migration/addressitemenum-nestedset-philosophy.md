# AddressItemEnum e NestedSet: Filosofia, Politica, Religione e Zen

## La Logica (Logic)

Il pattern NestedSet si basa su una rappresentazione matematicamente elegante delle gerarchie:

1. **Intervallo Numerico**: Ogni nodo occupa un intervallo [_lft, _rgt]
2. **Inclusione Totale**: I figli sono completamente contenuti nell'intervallo del padre
3. **Query O(1)**: Verificare parentela con semplici confronti numerici

```php
// La logica di base
if ($node->_lft > $parent->_lft && $node->_rgt < $parent->_rgt) {
    // $node è discendente di $parent
}
```

## La Filosofia (Philosophy)

### Principio della "Single Source of Truth"

AddressItemEnum::columns() incarna il principio DRY:

- **Centralizzazione**: Un solo punto dove definire la struttura degli indirizzi
- **Consistenza**: Tutte le tabelle usano la stessa nomenclatura
- **Evoluzione**: Cambiare in un posto si propaga ovunque

### Principio della "Eleganza Matematica"

Il NestedSet trasforma ricorsività complessa in aritmetica semplice:

- **Da N query a 1**: Le gerarchie non richiedono join ricorsive
- **Ordinamento Naturale**: L'ordine di inserimento è l'ordine di visualizzazione
- **Integrità Garantita**: La struttura matematica previene incoerenze

## La Politica (Politics)

### Governance dei Dati

AddressItemEnum stabilisce le "regole del gioco":

1. **Standardizzazione Globale**: Tutti i moduli parlano la stessa lingua
2. **Sovranità del Modulo Geo**: Geo definisce, gli altri implementano
3. **Diplomazia API**: Interfacce coerenti tra frontend e backend

### Potere e Responsabilità

```php
// Il potere di definire la struttura
AddressItemEnum::columns($table); // Centralizzato nel modulo Geo

// La responsabilità di usarla correttamente
$table->string(AddressItemEnum::ROUTE->value); // Nei moduli client
```

## La Religione (Religion)

### Dogmi Fondamentali

1. **Non ci sono indirizzi, solo componenti**: Ogni parte è un atomo indivisibile
2. **La gerarchia è sacra**: Via → Civico → Località → Comune → Provincia → Regione → Nazione
3. **Il tipo è la verità**: Strong typing previene l'eresia dei dati inconsistenti

### Riti e Cerimonie

```php
// Il battesimo di una nuova tabella indirizzi
$this->tableCreate(function (Blueprint $table): void {
    $table->id();
    AddressItemEnum::columns($table); // Il sacramento della struttura
    $table->timestamps();
});
```

### Peccati Capitali

- **Orgoglio**: Reinventare la struttura degli indirizzi in un modulo
- **Avarizia**: Usare stringhe hardcoded invece dei valori dell'enum
- **Lussuria**: Mescolare logica di business con struttura dati

## Lo Zen (Zen)

### Il Principio del "Vuoto Strutturato"

AddressItemEnum::columns() crea il "vuoto perfetto":

- **Forma senza Forma**: La struttura esiste ma non è visibile
- **Il Tao dell'Indirizzo**: Seguire il flusso naturale dei dati geografici
- **Wu Wei**: Lasciare che l'enum guidi l'implementazione

### Koan dell'Indirizzo

> "Qual è il suono di un indirizzo senza struttura?"
> 
> *Risposta: Il silenzio di AddressItemEnum::columns()*

### Illuminazione

```php
// Prima dell'illuminazione
$table->string('address'); // Caos
$table->string('city'); // Dualità
$table->string('province'); // Sofferenza

// Dopo l'illuminazione
AddressItemEnum::columns($table); // Unità
```

## Implementazione Pratica

### Il Metodo AddressItemEnum::columns()

```php
<?php

namespace Modules\Geo\Enums;

use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;

class AddressItemEnum
{
    // ... enum cases ...

    /**
     * Aggiunge le colonne standard per indirizzi gerarchici
     * Seguendo la filosofia NestedSet + AddressItemEnum
     */
    public static function columns(Blueprint $table): void
    {
        // Colonne geografiche gerarchiche (nested set)
        $table->string(self::ROUTE->value)->nullable()->comment('Via/Piazza');
        $table->string(self::STREET_NUMBER->value)->nullable()->comment('Numero civico');
        $table->string(self::LOCALITY->value)->nullable()->comment('Località/Frazione');
        $table->string(self::ADMINISTRATIVE_AREA_LEVEL_3->value)->nullable()->comment('Comune/Città');
        $table->string(self::ADMINISTRATIVE_AREA_LEVEL_2->value)->nullable()->comment('Provincia/Sigla');
        $table->string(self::ADMINISTRATIVE_AREA_LEVEL_1->value)->nullable()->comment('Regione');
        $table->string(self::COUNTRY->value, 2)->nullable()->comment('Codice paese ISO');
        $table->string(self::POSTAL_CODE->value, 20)->nullable()->comment('CAP');

        // Colonne geocoding
        $table->text(self::FORMATTED_ADDRESS->value)->nullable();
        $table->string(self::PLACE_ID->value)->nullable()->comment('Google Places ID');
        $table->decimal(self::LATITUDE->value, 15, 10)->nullable();
        $table->decimal(self::LONGITUDE->value, 15, 10)->nullable();

        // Colonna contatto (non gerarchica ma correlata)
        $table->string(self::PHONE->value)->nullable()->comment('Numero di telefono');

        // Indici per performance zen
        $table->index([self::ADMINISTRATIVE_AREA_LEVEL_3->value, self::ADMINISTRATIVE_AREA_LEVEL_2->value]);
        $table->index(self::POSTAL_CODE->value);
        $table->index([self::LATITUDE->value, self::LONGITUDE->value]);
    }

    /**
     * Rimuove le colonne AddressItemEnum
     */
    public static function dropColumns(Blueprint $table): void
    {
        $columns = [
            self::ROUTE->value,
            self::STREET_NUMBER->value,
            self::LOCALITY->value,
            self::ADMINISTRATIVE_AREA_LEVEL_3->value,
            self::ADMINISTRATIVE_AREA_LEVEL_2->value,
            self::ADMINISTRATIVE_AREA_LEVEL_1->value,
            self::COUNTRY->value,
            self::POSTAL_CODE->value,
            self::FORMATTED_ADDRESS->value,
            self::PLACE_ID->value,
            self::LATITUDE->value,
            self::LONGITUDE->value,
            self::PHONE->value,
        ];

        $table->dropIndex($columns);
        $table->dropColumn($columns);
    }
}
```

### La Via della Migrazione

```php
// Il sentiero otto volte piegato
return new class extends XotBaseMigration {
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Il vuoto primordiale
            $table->string('name')->nullable();
            
            // La struttura diventa manifesto
            AddressItemEnum::columns($table);
            
            // Il tempo scorre
            $table->timestamps();
        });
    }
};
```

## Conclusione

AddressItemEnum::columns() non è solo un metodo, è una filosofia completa che unisce:

- **Logica Matematica** (NestedSet intervals)
- **Filosofia DRY** (Single Source of Truth)
- **Politica di Governance** (Standardizzazione)
- **Religione del Tipo** (Strong Typing)
- **Zen della Struttura** (Forma senza Forma)

Implementare AddressItemEnum::columns() significa abbracciare questa visione olistica della gestione degli indirizzi.

## Riferimenti

- [Tao Te Ching] - "Il Tao che può essere nominato non è il Tao eterno"
- [Zen and the Art of Motorcycle Maintenance] - Qualità nella struttura
- [Clean Code] - La sacralità del codice pulito
- [Domain-Driven Design] - Ubiquitous Language per indirizzi