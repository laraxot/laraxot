---
title: "Convenzioni per i Collegamenti nei File Markdown"
type: concept
tags: [kdown, linking, conventions]
created: 2026-07-14
updated: 2026-07-14
qmd: "kdown-linking-conventions convenzioni per i collegamenti nei file markdown"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Convenzioni per i Collegamenti nei File Markdown

## Regola Fondamentale

, **tutti i collegamenti nei file Markdown devono utilizzare percorsi relativi** e non percorsi assoluti.

## Esempi Corretti e Incorretti

### ❌ ERRATO: Percorsi Assoluti

```markdown
[Convenzioni di Naming per le Interfacce](modules/notify/docs/interface-naming-convention.md)
[Chiarimento sulla Struttura delle Interfacce](modules/notify/docs/interface-structure-clarification.md)
[Architettura dei Contratti](modules/notify/docs/contracts-architecture.md)
[Convenzioni di Naming per le Interfacce](modules/notify/docs/interface-naming-convention.md)
[Chiarimento sulla Struttura delle Interfacce](modules/notify/docs/interface-structure-clarification.md)
[Architettura dei Contratti](modules/notify/docs/contracts-architecture.md)
[Convenzioni di Naming per le Interfacce](modules/notify/docs/interface-naming-convention.md)
[Chiarimento sulla Struttura delle Interfacce](modules/notify/docs/interface-structure-clarification.md)
[Architettura dei Contratti](modules/notify/docs/contracts-architecture.md)
```

### ✅ CORRETTO: Percorsi Relativi

```markdown
[Convenzioni di Naming per le Interfacce](./interface-naming-convention.md)
[Chiarimento sulla Struttura delle Interfacce](./interface-structure-clarification.md)
[Architettura dei Contratti](./contracts-architecture.md)
```

Per collegamenti a documenti in altre directory:

```markdown
[Regole Generali per le Chiavi di Traduzione](../../lang/docs/translation-keys-rules-1.md)
[Best Practices per le Chiavi di Traduzione](../../lang/docs/translation-keys-best-practices-1.md)
```

## Motivazione

1. **Portabilità**: I percorsi relativi funzionano indipendentemente dalla posizione di installazione del progetto
2. **Compatibilità tra ambienti**: I percorsi assoluti potrebbero non funzionare in ambienti diversi
3. **Manutenibilità**: I percorsi relativi sono più facili da mantenere quando la struttura del progetto cambia
4. **Standard del progetto**:  segue lo standard di utilizzare percorsi relativi in tutti i documenti Markdown
4. **Standard del progetto**: <nome progetto> segue lo standard di utilizzare percorsi relativi in tutti i documenti Markdown

## Regole per i Collegamenti Relativi

1. **File nella stessa directory**: Utilizzare `./nome-file.md`
2. **File in una sottodirectory**: Utilizzare `./sottodirectory/nome-file.md`
3. **File in una directory superiore**: Utilizzare `../nome-file.md`
4. **File in una directory parallela**: Utilizzare `../directory-parallela/nome-file.md`

## Verifica dei Collegamenti

Prima di committare un file Markdown, verificare sempre che tutti i collegamenti utilizzino percorsi relativi e non assoluti. È possibile utilizzare questo comando per trovare collegamenti assoluti nei file Markdown:

```bash
grep -r "\[.*\](/var" --include="*.md" /percorso/al/progetto
```

## Collegamenti Correlati

- [Convenzioni di Documentazione](../../../../docs/documentation-conventions.md)
- [Markdown Best Practices](../../../../docs/markdown-best-practices.md)
# Convenzioni per i Collegamenti nei File Markdown

## Regola Fondamentale

, **tutti i collegamenti nei file Markdown devono utilizzare percorsi relativi** e non percorsi assoluti.

## Esempi Corretti e Incorretti

### ❌ ERRATO: Percorsi Assoluti

```markdown
[Convenzioni di Naming per le Interfacce](modules/notify/docs/interface-naming-convention.md)
[Chiarimento sulla Struttura delle Interfacce](modules/notify/docs/interface-structure-clarification.md)
[Architettura dei Contratti](modules/notify/docs/contracts-architecture.md)
```

### ✅ CORRETTO: Percorsi Relativi

```markdown
[Convenzioni di Naming per le Interfacce](./interface-naming-convention.md)
[Chiarimento sulla Struttura delle Interfacce](./interface-structure-clarification.md)
[Architettura dei Contratti](./contracts-architecture.md)
```

Per collegamenti a documenti in altre directory:

```markdown
[Regole Generali per le Chiavi di Traduzione](../../lang/docs/translation-keys-rules-1.md)
[Best Practices per le Chiavi di Traduzione](../../lang/docs/translation-keys-best-practices-1.md)
```

## Motivazione

1. **Portabilità**: I percorsi relativi funzionano indipendentemente dalla posizione di installazione del progetto
2. **Compatibilità tra ambienti**: I percorsi assoluti potrebbero non funzionare in ambienti diversi
3. **Manutenibilità**: I percorsi relativi sono più facili da mantenere quando la struttura del progetto cambia
4. **Standard del progetto**: <main module> segue lo standard di utilizzare percorsi relativi in tutti i documenti Markdown

## Regole per i Collegamenti Relativi

1. **File nella stessa directory**: Utilizzare `./nome-file.md`
2. **File in una sottodirectory**: Utilizzare `./sottodirectory/nome-file.md`
3. **File in una directory superiore**: Utilizzare `../nome-file.md`
4. **File in una directory parallela**: Utilizzare `../directory-parallela/nome-file.md`

## Verifica dei Collegamenti

Prima di committare un file Markdown, verificare sempre che tutti i collegamenti utilizzino percorsi relativi e non assoluti. È possibile utilizzare questo comando per trovare collegamenti assoluti nei file Markdown:

```bash
grep -r "\[.*\](/var" --include="*.md" /percorso/al/progetto
```

## Collegamenti Correlati

- [Convenzioni di Documentazione](../../../../docs/documentation-conventions.md)
- [Markdown Best Practices](../../../../docs/markdown-best-practices.md)
