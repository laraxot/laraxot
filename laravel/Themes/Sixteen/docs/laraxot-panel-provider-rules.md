# Regole Fondamentali per AdminPanelProvider - Laraxot

## REGOLA ASSOLUTA E INVIOLABILE

Il file `app/Providers/Filament/AdminPanelProvider.php` **DEVE SEMPRE** estendere `XotBaseMainPanelProvider`.

**QUESTA È LEGGE!**

## Struttura Corretta Obbligatoria

```php
<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
}
```

## Regole da Rispettare SEMPRE

### ✅ DA FARE SEMPRE
- Estendere `XotBaseMainPanelProvider`
- Mantenere il namespace `App\Providers\Filament`
- Includere `declare(strict_types=1);`

### ❌ NON FARE MAI
- **NON** usare `XotBasePanelProvider`
- **NON** usare `PanelProvider` direttamente
- **NON** cambiare la classe base senza autorizzazione esplicita

## Motivazione

Questa regola è stata stabilita come **legge fondamentale del progetto Laraxot** e deve essere rispettata sempre, senza eccezioni.

La classe `XotBaseMainPanelProvider` fornisce:
- Configurazioni specifiche per il progetto Laraxot
- Middleware e impostazioni personalizzate
- Integrazione con i moduli Xot
- Compatibilità con l'architettura del framework

## Conseguenze della Violazione

La violazione di questa regola causa:
- Errori di classe non trovata
- Malfunzionamento del pannello Filament
- Incompatibilità con l'architettura Laraxot
- Problemi di avvio dell'applicazione

## Controllo e Verifica

Prima di ogni commit, verificare che:
1. `AdminPanelProvider` estenda `XotBaseMainPanelProvider`
2. L'import sia corretto: `use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;`
3. Non ci siano errori di autoload

## Note per Sviluppatori

- Questa regola ha priorità assoluta su qualsiasi altra considerazione
- In caso di dubbi, consultare sempre questa documentazione
- Aggiornare questa documentazione solo con autorizzazione esplicita
- Mantenere la memory e le regole sempre aggiornate

---

**Stabilito**: 31 Luglio 2025  
**Autorità**: Utente del progetto  
**Stato**: LEGGE INVIOLABILE  
**Priorità**: MASSIMA
