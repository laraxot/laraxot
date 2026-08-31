---
title: "Compatibilità dei Metodi nei Componenti Filament"
type: concept
tags: [component, methods, compatibility]
created: 2026-07-14
updated: 2026-07-14
qmd: "component-methods-compatibility compatibilità dei metodi nei componenti filament"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
related:
  - "./automatic-translations.md"
  - "./best-practices.md"
  - "./component-icon-support.md"
  - "./filament-4-components-guide.md"
  - "./filament-4-migration-guide.md"
  - "./filament-4-migration-summary.md"
  - "./filament-4-migration-sumy.md"
  - "./file-upload-component.md"
---

# Compatibilità dei Metodi nei Componenti Filament

## Panoramica

Questo documento descrive la compatibilità dei metodi tra i diversi componenti Filament, con particolare attenzione ai metodi che non sono universalmente supportati da tutti i componenti.

## Problema: Metodi Non Universali

Un errore comune nello sviluppo con Filament è assumere che tutti i componenti supportino gli stessi metodi. Questo può portare a errori di runtime come:

```
Method Filament\Forms\Components\FileUpload::prefixIcon does not exist.
```

## Tabella di Compatibilità dei Metodi

### Metodo `prefixIcon()`

Il metodo `prefixIcon()` è utilizzato per aggiungere un'icona prima del contenuto di un componente.

| Componente | Supporta `prefixIcon()` | Alternativa |
|------------|--------------------------|-------------|
| `TextInput` | ✅ Sì | - |
| `Select` | ✅ Sì | - |
| `Checkbox` | ❌ No | - |
| `FileUpload` | ❌ No | Non disponibile |
| `DatePicker` | ✅ Sì | - |
| `TimePicker` | ✅ Sì | - |
| `Toggle` | ❌ No | - |
| `Textarea` | ✅ Sì | - |

### Metodo `icon()`

Il metodo `icon()` è utilizzato per aggiungere un'icona a un componente.

| Componente | Supporta `icon()` | Alternativa |
|------------|---------------------|-------------|
| `TextInput` | ❌ No | Usare `prefixIcon()` |
| `Select` | ❌ No | Usare `prefixIcon()` |
| `Checkbox` | ❌ No | - |
| `FileUpload` | ❌ No | Non disponibile |
| `DatePicker` | ❌ No | Usare `prefixIcon()` |
| `TimePicker` | ❌ No | Usare `prefixIcon()` |
| `Toggle` | ❌ No | - |
| `Textarea` | ❌ No | Usare `prefixIcon()` |

### Metodo `extraAttributes()`

Il metodo `extraAttributes()` è utilizzato per aggiungere attributi HTML personalizzati ai componenti.

| Componente | Supporta `extraAttributes()` | Alternativa |
|------------|------------------------------|-------------|
| `TextInput` | ✅ Sì | - |
| `Select` | ✅ Sì | - |
| `Checkbox` | ✅ Sì | - |
| `FileUpload` | ❌ No | - |
| `DatePicker` | ✅ Sì | - |
| `TimePicker` | ✅ Sì | - |
| `Toggle` | ✅ Sì | - |
| `Textarea` | ✅ Sì | - |

### Metodo `description()`

Il metodo `description()` è utilizzato per aggiungere una descrizione a un componente.

| Componente | Supporta `description()` | Alternativa |
|------------|--------------------------|-------------|
| `Section` | ✅ Sì | - |
| `Card` | ✅ Sì | - |
| `Tabs` | ✅ Sì | - |
| `Tabs\Tab` | ❌ No | Usare `Section` all'interno |
| `Wizard\Step` | ✅ Sì | - |

## Esempi di Errori Comuni e Correzioni

### Errore con `prefixIcon()` in `FileUpload`

```php
// ERRATO
Forms\Components\FileUpload::make('certification')
    ->label('Certificazione')
    ->prefixIcon('heroicon-o-document-text') // Questo metodo non esiste!
    ->icon('heroicon-o-document-text') // Questo metodo non esiste!
    ->extraAttributes(['class' => 'bg-blue-50/30']) // Questo metodo non esiste!
```

### Correzione

```php
// CORRETTO
Forms\Components\FileUpload::make('certification')
    ->label('Certificazione')
    // Non è possibile aggiungere un'icona al FileUpload
    // Non utilizzare extraAttributes() perché non è supportato
```

## Best Practices

1. **Consultare la documentazione ufficiale**: Verificare sempre la documentazione di Filament per i metodi supportati
2. **Ispezionare il codice sorgente**: In caso di dubbi, esaminare il codice sorgente della classe del componente
3. **Utilizzare l'IDE**: Sfruttare l'autocompletamento dell'IDE per vedere i metodi disponibili
4. **Test incrementali**: Testare piccole modifiche alla volta per identificare rapidamente gli errori

## Collegamenti Bidirezionali

- [Documentazione Filament](./resources.md)
- [Form Components](../form-components.md)
- [Filament Resources Structure](../filament-resources-structure.md)
- [Documentazione specifica nel modulo Patient](../../patient/docs/filament-component-methods.md)
