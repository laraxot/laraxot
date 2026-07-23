# Queueable Actions Pattern

## Overview
Il pattern Queueable Actions è basato sulle Action di Spatie e viene utilizzato per incapsulare logica di business riutilizzabile che può essere eseguita in modo sincrono o asincrono.

## Struttura
Le azioni devono essere organizzate per dominio in moduli specifici. Ad esempio:
- `Modules/Geo/app/Actions/` per azioni geografiche
- `Modules/Job/app/Actions/` per azioni relative ai job
- `Modules/User/app/Actions/` per azioni relative agli utenti

## Convenzioni di Naming
- Nome della classe: `{Verbo}{Sostantivo}Action`
- Esempio: `CalculateDistanceAction`, `UpdateCoordinatesAction`

## Implementazione

```php
namespace Modules\Geo\app\Actions;

use Spatie\QueueableAction\QueueableAction;

class CalculateDistanceAction
{
    use QueueableAction;

    public function execute($origin, $destination): float
    {
        // Logica per calcolare la distanza
    }
}
```

## Utilizzo

```php
// Utilizzo sincrono
$distance = app(CalculateDistanceAction::class)->execute($origin, $destination);

// Utilizzo asincrono
app(CalculateDistanceAction::class)->onQueue('geo')->execute($origin, $destination);
```

## Vantaggi
1. **Riutilizzabilità**: Le azioni possono essere utilizzate in qualsiasi parte dell'applicazione
2. **Testabilità**: Facile da testare in isolamento
3. **Manutenibilità**: Logica di business centralizzata e organizzata
4. **Flessibilità**: Può essere eseguito in modo sincrono o asincrono
5. **Separazione delle responsabilità**: Ogni azione ha uno scopo specifico

## Best Practices
1. Mantenere le azioni piccole e focalizzate su un singolo compito
2. Documentare input e output attesi
3. Aggiungere test unitari per ogni azione
4. Utilizzare dependency injection quando necessario
5. Organizzare le azioni per dominio in moduli specifici

## Esempio Pratico: Modulo Geo

```php
// Modules/Geo/app/Actions/CalculateDistanceAction.php
class CalculateDistanceAction
{
    use QueueableAction;

    public function execute($origin, $destination): float
    {
        return // calcolo distanza
    }
}

// Modules/Geo/app/Actions/CalculateTravelTimeAction.php
class CalculateTravelTimeAction
{
    use QueueableAction;

    public function execute($origin, $destination): int
    {
        return // calcolo tempo di viaggio
    }
}

// Modules/Geo/app/Actions/UpdateCoordinatesAction.php
class UpdateCoordinatesAction
{
    use QueueableAction;

    public function execute($address): array
    {
        return // aggiornamento coordinate
    }
}
```

## Note
- Utilizzare le code quando l'operazione è pesante o non richiede risposta immediata
- Considerare la possibilità di cachare i risultati per operazioni costose
- Implementare gestione degli errori appropriata
