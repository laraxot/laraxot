---
title: "Pattern BaseForm per Filament Resources"
type: memory
tags: [filament, form, base-class, inheritance, modular]
created: 2026-08-26
qmd: "BaseForm XotBaseResourceForm abstract class Form modulo figlio leaf module extends"
trigger: "Class not found Form, BaseForm mancante, Form extends, modulo figlio form"
---

# Pattern BaseForm per Filament Resources

**Appreso**: 2026-08-26

## Lezione

Quando un modulo figlio (leaf module) estende un modulo base e ha una Form identica o simile, NON copiare la Form. Creare:

1. `Base{Model}Form` abstract in modulo base (es. Ptv)
2. `{Model}Form extends Base{Model}Form` nel modulo base
3. `{Model}Form extends Base{Model}Form` nei moduli figli

## Perche

- Evita duplicazione codice
- Un punto di modifica per schema comune
- Metodi specifici (es. `getTypeOptions()`) restano nei figli
- Segue pattern esistente: `BaseRatingForm`, `BaseSchedaForm`, `BaseCriteriEsclusioneForm`

## Struttura

```
Modules/Ptv/app/Filament/Resources/MessageResource/Schemas/
  BaseMessageForm.php      # abstract, getFormSchema() comune
  MessageForm.php          # extends BaseMessageForm, getTypeOptions() locale

Modules/IndennitaResponsabilita/app/Filament/Resources/MessageResource/Schemas/
  MessageForm.php          # extends BaseMessageForm, getTypeOptions() locale
```

## Pattern corretto

```php
// Modules/Ptv/.../Schemas/BaseMessageForm.php
abstract class BaseMessageForm extends XotBaseResourceForm
{
    public static function getFormSchema(): array
    {
        return [
            'type' => Select::make('type')
                ->options(fn () => static::getTypeOptions()), // late static binding
            // ... altri campi comuni
        ];
    }

    abstract protected static function getTypeOptions(): array;
}

// Modules/IndennitaResponsabilita/.../Schemas/MessageForm.php
use Modules\Ptv\Filament\Resources\MessageResource\Schemas\BaseMessageForm;

class MessageForm extends BaseMessageForm
{
    protected static function getTypeOptions(): array
    {
        $model = MessageResource::getModel(); // Resource locale
        // ...
    }
}
```

## Errore comune

**SBAGLIATO**: Cambiare `extends BaseMessageForm` in `extends XotBaseResourceForm` quando la base class non esiste.

**CORRETTO**: Creare la base class mancante nel modulo base (Ptv).

## Riferimenti

- `Modules/Rating/app/Filament/Resources/RatingResource/Schemas/BaseRatingForm.php`
- `Modules/Ptv/app/Filament/Resources/SchedaResource/Schemas/BaseSchedaForm.php`
- Rule: parental-sti-filament-schemas
