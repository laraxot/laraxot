# Supporto Multilingua - File [slug].blade.php

## Panoramica

Questo documento descrive le modifiche apportate al file `laravel/Modules/Predict/resources/views/pages/predicts/[slug].blade.php` per garantire il supporto multilingua completo, seguendo le `translation-system-rules` del progetto.

## Convenzioni di Naming delle Chiavi di Traduzione

### Struttura Standardizzata
Per garantire consistenza e chiarezza semantica, tutte le chiavi di traduzione seguono una convenzione di naming specifica:

- **Labels**: finiscono con `.label` (es. `total_volume.label`)
- **Descriptions**: finiscono con `.description` (es. `market_analysis.description`)
- **Placeholders**: finiscono con `.placeholder` (es. `share_opinion.placeholder`)
- **Tooltips**: finiscono con `.tooltip` (es. `help_button.tooltip`)

### Vantaggi della Convenzione
1. **Chiarezza semantica**: Il suffisso rende immediatamente chiaro il tipo di contenuto
2. **Organizzazione**: Facilita la ricerca e il mantenimento delle traduzioni
3. **Consistenza**: Standardizza la struttura delle chiavi di traduzione
4. **Scalabilità**: Permette di aggiungere facilmente varianti (es. `.short`, `.long`, `.help`)

## Modifiche Apportate

### 1. Aggiornamento Struttura File di Traduzione

#### File: `laravel/Modules/Predict/resources/lang/it/predict.php`
**Struttura Precedente:**
```php
'titles' => [
    'market' => 'Mercato delle Previsioni',
],
'labels' => [
    'total_volume' => 'Volume Totale',
],
```

**Struttura Aggiornata:**
```php
'titles' => [
    'market' => [
        'label' => 'Mercato delle Previsioni',
    ],
],
'labels' => [
    'total_volume' => [
        'label' => 'Volume Totale',
    ],
],
```

#### File: `laravel/Modules/Predict/resources/lang/en/predict.php`
Aggiornata con la stessa struttura per coerenza multilingua.

### 2. Aggiornamento Utilizzo nel File Blade

#### Chiavi Aggiornate nel File [slug].blade.php:
- `__('predict::messages.order_failed')` → `__('predict::messages.order_failed.label')`

#### Chiavi che Mantengono la Struttura Originale:
- `__('predict::messages.order_placed_successfully')` - Mantiene la struttura con parametri
- `__('predict::time.minutes_ago')` - Mantiene la struttura con parametri

### 3. Sezioni Aggiornate nei File di Traduzione

#### Titles (Titoli):
```php
'titles' => [
    'market' => ['label' => 'Mercato delle Previsioni'],
    'prediction_market' => ['label' => 'Mercato delle Previsioni'],
    'my_positions' => ['label' => 'Le Mie Posizioni'],
    // ... altre chiavi
],
```

#### Descriptions (Descrizioni):
```php
'descriptions' => [
    'market_analysis' => ['description' => 'Analisi e previsioni di mercato'],
    'place_order' => ['description' => 'Piazza un ordine su questo mercato'],
    // ... altre chiavi
],
```

#### Labels (Etichette):
```php
'labels' => [
    'current_price' => ['label' => 'Prezzo Attuale'],
    'volume_24h' => ['label' => 'Volume 24h'],
    'participants' => ['label' => 'Partecipanti'],
    // ... altre chiavi
],
```

#### Status (Stati):
```php
'status' => [
    'active' => ['label' => 'Attivo'],
    'high' => ['label' => 'Alto'],
    'low' => ['label' => 'Basso'],
],
```

#### Actions (Azioni):
```php
'actions' => [
    'share' => ['label' => 'Condividi'],
    'buy' => ['label' => 'Compra'],
    'sell' => ['label' => 'Vendi'],
    // ... altre chiavi
],
```

#### Messages (Messaggi):
```php
'messages' => [
    'loading' => ['label' => 'Caricamento...'],
    'order_failed' => ['label' => 'Impossibile piazzare l\'ordine. Riprova.'],
    'chart_coming_soon' => ['label' => 'Grafico interattivo in arrivo'],
    // ... altre chiavi
],
```

#### Placeholders (Segnaposto):
```php
'placeholders' => [
    'share_opinion' => ['placeholder' => 'Condividi la tua opinione su questo mercato...'],
],
```

## Benefici dell'Implementazione

### 1. Manutenibilità
- Struttura coerente e prevedibile
- Facile identificazione del tipo di contenuto
- Semplifica l'aggiunta di nuove traduzioni

### 2. Scalabilità
- Supporto per varianti di contenuto (es. `.short`, `.long`)
- Possibilità di aggiungere metadati (es. `.tooltip`, `.help`)
- Struttura flessibile per future estensioni

### 3. Qualità del Codice
- Riduce errori di utilizzo delle chiavi
- Migliora la leggibilità del codice
- Facilita il refactoring

### 4. Collaborazione
- Standardizzazione per team di sviluppo
- Documentazione chiara delle convenzioni
- Riduce confusione tra sviluppatori

## Prossimi Passi Suggeriti

### 1. Verifica Completa
- Controllare tutti i file Blade per aggiornare le chiavi mancanti
- Verificare la coerenza tra file IT e EN
- Testare le traduzioni in ambiente di sviluppo

### 2. Estensione
- Applicare la stessa convenzione agli altri moduli
- Creare script di migrazione per progetti esistenti
- Documentare le convenzioni per il team

### 3. Automazione
- Implementare linting per verificare le convenzioni
- Creare test automatici per le traduzioni
- Sviluppare tool di validazione

## Esempi di Utilizzo

### Nel Codice Blade:
```php
{{ __('predict::titles.market.label') }}
{{ __('predict::labels.current_price.label') }}
{{ __('predict::actions.share.label') }}
{{ __('predict::messages.loading.label') }}
{{ __('predict::placeholders.share_opinion.placeholder') }}
```

### Con Parametri:
```php
{{ __('predict::time.minutes_ago', ['count' => 5]) }}
{{ __('predict::messages.order_placed_successfully', [
    'type' => 'BUY',
    'quantity' => 100,
    'price' => '0.75'
]) }}
```

Questa implementazione garantisce un sistema di traduzione robusto, manutenibile e scalabile per l'applicazione Laravel. 