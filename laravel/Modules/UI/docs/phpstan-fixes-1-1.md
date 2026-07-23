---
title: "Correzioni PHPStan Livello 7 - Modulo UI"
type: concept
tags: [phpstan, fixes]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan-fixes-1-1 correzioni phpstan livello 7 - modulo ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Correzioni PHPStan Livello 7 - Modulo UI

Questo documento traccia gli errori PHPStan di livello 7 identificati nel modulo UI e le relative soluzioni implementate.

## Errori Identificati

### 1. Errori in TableLayoutToggleTableAction.php

```
Metodo toggleLayout() ha il parametro $livewire senza type hint specificato.
Cannot call method dispatch() on class-string|object.
Cannot call method resetTable() on class-string|object.
```

## Soluzioni Implementate

### 1. Correzione in TableLayoutToggleTableAction.php

Per risolvere i problemi di type safety nella classe `TableLayoutToggleTableAction`, sono stati apportati i seguenti cambiamenti:

1. Aggiunto il type hint `mixed` al parametro `$livewire` del metodo `toggleLayout()` invece di forzare un tipo specifico, poiché il parametro potrebbe essere di vari tipi:

```php
protected function toggleLayout(mixed $livewire = null): void
```

2. Aggiunti controlli `method_exists` e `property_exists` prima di chiamare metodi o accedere a proprietà sull'oggetto `$livewire`:

```php
if ($livewire) {
    // Use property_exists to safely check if the property exists
    if (property_exists($livewire, 'layoutView')) {
        $livewire->layoutView = $newLayout;
    }
    
    // These methods should be available on Filament components
    if (method_exists($livewire, 'dispatch')) {
        $livewire->dispatch('$refresh');
        $livewire->dispatch('refreshTable');
    }
    
    if (method_exists($livewire, 'resetTable')) {
        $livewire->resetTable();
    }
}
```

Questo approccio è più robusto e previene errori a runtime quando l'oggetto `$livewire` non ha i metodi o le proprietà previste.
