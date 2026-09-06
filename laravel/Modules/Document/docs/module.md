---
title: "Document Module — Doctrine"
type: doctrine
tags: [document, allegati, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Document module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Media/docs/module.md"
---

# Document Module — Doctrine

## Scope (Scopo)

Document gestisce gli allegati e documenti associati a entità del sistema tramite relazioni polimorfiche. Estrae automaticamente metadati (MIME, size) e supporta storage multipli. È il ponte sicuro tra entità di dominio e file fisici.

## Religion (Religione)

**"I documenti sono metadati, non BLOB."** La convinzione non negoziabile è che ogni documento è tracciato, verificato, e gestito correttamente. Il file system è la fonte di verità per proprietà (size, type), il DB è il registro.

## Philosophy (Filosofia)

- **MorphTo pattern**: associazioni polimorfiche flessibili
- **SoftDeletes**: recupero documenti eliminati
- **Immutability**: metadata extraction al momento dell'upload
- **Storage abstraction**: mediazione tramite Laravel storage

## Policy (Politica)

- Relazione morphTo obbligatoria
- Metadata estratti e salvati all'upload
- Percorso fisico e disco registrati
- Soft delete obbligatorio
- Accesso mediato via storage service

## Why (Perché)

Document è una preoccupazione trasversale che serve a Intervention, WorkOrder, e altri moduli ma richiede logiche specifiche di gestione file. L'incapsulamento garantisce coerenza.

## Zen

*"Ogni file ha un nome, un tipo, una storia. Registrato, non perduto."*

## Gap

- Nessun test visibile
- Policies assenti
- Azioni limitate
- Eventi di dominio mancanti
- Convenzioni di naming per storage non documentate

## Add

- Policies per operazioni documenti
- Test feature e unit
- Azioni per sostituzione, rinominazione, spostamento
- Eventi di dominio
- Policy di retention automatica

## Split/Merge

**Mantenere come-is.** Potrebbe fondersi con Media (entrambi gestiscono file), ma Document si concentra sulle associazioni mentre Media sulla trasformazione. Separazione giustificata.

## Future Enhancements

1. **Version control**: versioning dei documenti
2. **Digital signature**: firma digitale integrata
3. **OCR**: estrazione testo da PDF/images
4. **Preview generation**: thumbnail per documenti
5. **Encryption**: cifratura documenti sensibili
6. **Audit trail**: tracking completo accessi
