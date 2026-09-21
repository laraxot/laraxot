---
name: discovery-pattern-vs-configuration
description: "Quando un dato è schema-derivabile usa un getter con introspection, mai setter+property"
metadata:
  type: lesson-learned
  created: 2026-09-15
  github_issues: []
---

# Discovery pattern batte configuration quando il dato è schema-derivabile

## L'errore che si è ripetuto

Proposto per `HasXotTable`:

```php
// SBAGLIATO
protected ?string $orderColumn = null;

public function setOrderColumn(?string $column): static
{
    $this->orderColumn = $column;
    return $this;
}

public function getOrderColumn(): ?string
{
    return $this->orderColumn;
}
```

## Perché è sbagliato

Il dato (`order_column`) è già derivabile dallo schema del model: se la colonna esiste, va
usata automaticamente. Il setter+property introduce uno stato duplicato che deve essere
sincronizzato manualmente, aggiunge un punto di configurazione che nessun caller userà nel
99% dei casi, e nasconde il vero comportamento (auto-discovery) dietro un'API che sembra
richiedere configurazione esplicita.

## Come si fa correttamente

```php
protected function getOrderColumn(): ?string
{
    if ($this->hasColumn('order_column')) {
        return 'order_column';
    }
    return null;
}
```

Nessun setter, nessuna property. Chi vuole un comportamento diverso fa `override` del
metodo `getOrderColumn()` nella propria classe — non chiama un setter.

## Come riconoscerlo in futuro

Prima di aggiungere `set*()` + property privata per un valore, chiediti: "questo dato è già
deducibile da qualcos'altro che possiedo (schema, convenzione di naming, altra property)?".
Se sì, scrivi un getter con introspection, non una coppia setter/getter.

```bash
# Grep per individuare setter sospetti da rivedere
rg "public function set[A-Z]\w+\(.*\): (self|static)" laravel/Modules/*/app/Filament/Traits/
```

## Riferimenti

- Story 5.103 (`Modules/Xot/docs/stories/`)
- Memory: `discovery-pattern-precedence.md`
