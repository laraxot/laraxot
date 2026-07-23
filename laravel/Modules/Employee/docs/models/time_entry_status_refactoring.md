# Refactoring Stato TimeEntry

## Obiettivo

Questo documento descrive il refactoring del modello `TimeEntry` per migliorare la gestione degli stati, aderendo ai principi DRY (Don't Repeat Yourself) e KISS (Keep It Simple, Stupid) della metodologia Laraxot. L'obiettivo è centralizzare la definizione degli stati e rendere il codice più robusto e leggibile.

## Problema Iniziale

Prima del refactoring, il modello `TimeEntry` utilizzava stringhe hardcoded per rappresentare i vari stati (e.g., 'pending', 'approved') in diversi metodi (`scopePending`, `isApproved`, `isPending`, `isRejected`). Questa pratica introduceva potenziale duplicazione di stringhe e rendeva più difficile la manutenzione e l'evoluzione degli stati.

## Soluzione Implementata

Sono state introdotte delle costanti pubbliche all'interno del modello `TimeEntry` per ogni stato possibile. Questo approccio centralizza la definizione degli stati, rendendo il codice più pulito, meno prono a errori di battitura e più facile da modificare in futuro.

### Dettagli delle Modifiche

#### 1. Aggiunta delle Costanti di Stato

Le seguenti costanti sono state aggiunte alla classe `TimeEntry`:

```php
class TimeEntry extends BaseModel
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_AUTO_APPROVED = 'auto_approved';
    public const STATUS_REJECTED = 'rejected';

    // ...
}
```

#### 2. Refactoring dei Metodi Esistenti

Tutti i metodi che in precedenza utilizzavano stringhe hardcoded per i confronti di stato sono stati aggiornati per utilizzare le nuove costanti:

-   **`scopePending(Builder $query): Builder`**:
    ```php
    return $query->where('status', self::STATUS_PENDING);
    ```

-   **`isApproved(): bool`**:
    ```php
    return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_AUTO_APPROVED], strict: true);
    ```

-   **`isPending(): bool`**:
    ```php
    return $this->status === self::STATUS_PENDING;
    ```

-   **`isRejected(): bool`**:
    ```php
    return $this->status === self::STATUS_REJECTED;
    ```

## Benefici

-   **DRY (Don't Repeat Yourself)**: Eliminazione della duplicazione di stringhe per la definizione degli stati.
-   **KISS (Keep It Simple, Stupid)**: Migliora la chiarezza del codice e ne semplifica la comprensione.
-   **Manutenibilità**: Facilita la gestione degli stati; qualsiasi modifica al valore di uno stato richiede un solo punto di aggiornamento (la costante).
-   **Consistenza**: Garantisce che tutti i controlli di stato utilizzino la stessa definizione.
-   **Robustezza**: Riduce la possibilità di errori dovuti a errori di battitura nelle stringhe di stato.

## Compliance PHPStan

Le modifiche sono state implementate mantenendo la piena compatibilità con PHPStan livello 10, garantendo la correttezza dei tipi e l'aderenza agli standard di qualità del codice.
