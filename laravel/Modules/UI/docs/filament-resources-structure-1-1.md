---
title: "Struttura delle Filament Resources"
type: concept
tags: [filament, resources, structure]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-resources-structure-1-1 struttura delle filament resources"
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

# Struttura delle Filament Resources

## Panoramica
Questo documento descrive la struttura e l'organizzazione delle Filament Resources nel progetto il progetto, con particolare attenzione all'integrazione con il modulo Xot.

## Struttura Base
Le Filament Resources seguono una struttura gerarchica standardizzata:

```
laravel/
└── Modules/
    ├── Xot/
    │   └── app/
    │       └── Filament/
    │           └── Resources/
    │               └── Pages/
    │                   ├── XotBaseListRecords.php
    │                   ├── XotBaseCreateRecord.php
    │                   └── XotBaseEditRecord.php
    └── {ModuleName}/
        └── app/
            └── Filament/
                └── Resources/
                    └── {Model}Resource.php
```

## Classi Base di Xot
Le classi base di Xot forniscono funzionalità comuni a tutte le resources:

1. **XotBaseListRecords**
   - Gestione della lista dei record
   - Filtri e ordinamento
   - Azioni di massa

2. **XotBaseCreateRecord**
   - Creazione di nuovi record
   - Validazione dei dati
   - Gestione delle traduzioni

3. **XotBaseEditRecord**
   - Modifica dei record esistenti
   - Validazione dei dati
   - Gestione delle traduzioni

## Namespace e Import
Le classi base devono essere importate dal namespace corretto:
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
```

## Documentazione Correlata
- [Documentazione CMS Module](../laravel/Modules/Cms/docs/filament-resources.md)
- [Documentazione Xot Module](../laravel/Modules/Xot/docs/filament-resources.md)

## Best Practices
1. **Namespace**
   - Usa sempre il namespace completo
   - Importa le classi base dal percorso corretto
   - Mantieni la coerenza tra i moduli

2. **Ereditarietà**
   - Estendi sempre le classi base di Xot
   - Usa il prefisso "Base" per le classi base
   - Implementa solo le funzionalità specifiche del modulo

3. **Documentazione**
   - Mantieni aggiornata la documentazione
   - Crea collegamenti bidirezionali
   - Documenta le dipendenze

## Note Importanti
- Le classi base sono nel namespace `Modules\Xot\Filament\Resources\Pages\`
- Non esistono classi senza il prefisso "Base"
- La documentazione deve essere mantenuta sincronizzata

## Links
- [Documentazione Filament](https://filamentphp.com/)
- [Laravel Modules](module-structure.md)
- [Best Practices](best-practices.md)

## Note
Questa documentazione è collegata bidirezionalmente con la documentazione specifica dei moduli. Per dettagli su resources specifiche, consultare la documentazione del modulo corrispondente. 
