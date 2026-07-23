# Module Namespaces Architecture

## Overview
I namespace dei moduli sono definiti nei rispettivi `composer.json` e NON devono essere dedotti dalla struttura delle cartelle.

## ⚠️ ERRORI COMUNI DA EVITARE
1. **Mai assumere il namespace dalla struttura delle cartelle**
   - ❌ `Modules\Geo\app\Actions\CalculateDistanceAction`
   - ✅ `Modules\Geo\Actions\CalculateDistanceAction`

2. **Controllare TUTTI i riferimenti**
   - Quando si modifica un namespace, aggiornare TUTTI i file che lo utilizzano
   - Errori comuni come `Target class [Modules\Geo\app\Actions\CalculateDistanceAction] does not exist` indicano che un riferimento non è stato aggiornato

3. **Verificare dependency injection e service container**
   - Gli errori di classe non trovata spesso si verificano in:
     - Constructor injection
     - Method injection
     - Service container resolution (app()->make, app(), resolve())
     - Chiamate statiche

## Regole Importanti
1. **SEMPRE consultare il composer.json del modulo** per determinare il namespace corretto
2. **NON assumere** che il namespace includa la directory `app/`
3. Il PSR-4 autoloading è configurato nel `composer.json` di ogni modulo
4. **Dopo ogni modifica di namespace**:
   - Eseguire `composer dump-autoload`
   - Verificare TUTTI i file che utilizzano la classe
   - Controllare constructor injection e service container bindings

## Esempi

### Modulo Geo
```json
{
    "autoload": {
        "psr-4": {
            "Modules\\Geo\\": "app/",
        }
    }
}
```
Quindi:
- ✅ Corretto: `Modules\Geo\Actions\CalculateDistanceAction`
- ❌ Errato: `Modules\Geo\app\Actions\CalculateDistanceAction`

### Pattern Comune
La maggior parte dei moduli segue questo pattern:
- Namespace base: `Modules\NomeModulo\`
- Directory base: `app/`

## Struttura Tipica
```
Modules/
  ModuleName/
    app/
      Actions/
      Models/
      Services/
    composer.json  # Definisce il namespace base
```

## Best Practices
1. Sempre verificare il composer.json del modulo
2. Non assumere la struttura del namespace dalla struttura delle cartelle
3. Mantenere coerenza con il PSR-4 autoloading
4. Documentare eventuali eccezioni alla regola generale
5. Dopo ogni modifica di namespace, fare una ricerca globale per trovare tutti i riferimenti
6. Utilizzare l'IDE per il refactoring automatico quando possibile

## Checklist per Modifiche di Namespace
- [ ] Verificato composer.json del modulo
- [ ] Aggiornato namespace nella classe
- [ ] Aggiornati tutti i riferimenti in altri file
- [ ] Eseguito composer dump-autoload
- [ ] Testato tutte le funzionalità che utilizzano la classe
- [ ] Verificati constructor injection e service container bindings

## XotBaseRelationManager e getFormSchema

Le classi che estendono `XotBaseRelationManager` non implementano il metodo `form`, ma utilizzano invece `getFormSchema` per definire lo schema del form. Questo approccio consente una maggiore flessibilità nella definizione dei campi del form e si integra meglio con l'architettura modulare del progetto.

Assicurati di aggiornare il codice esistente per riflettere questa convenzione e di verificare che tutte le classi derivate implementino correttamente `getFormSchema` per evitare errori di runtime.

Esempio:

```php
class ExampleRelationManager extends XotBaseRelationManager
{
    public static function getFormSchema(): array
    {
        return [
            // Definizione dei campi del form
        ];
    }
}
```
