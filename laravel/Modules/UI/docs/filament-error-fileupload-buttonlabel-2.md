---
title: "Errore: Metodo `buttonLabel()` su FileUpload di Filament"
type: concept
tags: [filament, error, fileupload, buttonlabel]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-error-fileupload-buttonlabel-2 errore: metodo `buttonlabel()` su fileupload di filament"
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

# Errore: Metodo `buttonLabel()` su FileUpload di Filament

## Descrizione
Il metodo `buttonLabel()` **NON esiste** sul componente `Filament\Forms\Components\FileUpload`. L'uso di questo metodo genera un errore fatale.

## Componenti coinvolti
- `FileUpload` **(non supporta)**
- Consultare la documentazione ufficiale Filament per i metodi supportati.

## Soluzione
- **Non usare mai** `->buttonLabel()` su FileUpload.
- Per personalizzare il testo del bottone, usare le opzioni di traduzione dedicate nei file di lingua oppure override via slot Blade se disponibile.

## Best Practice
- Consultare sempre la [documentazione ufficiale Filament](https://filamentphp.com/docs/3.x/forms/fields/file-upload) prima di usare metodi non standard.
- Seguire la tabella di compatibilità dei metodi nei componenti Filament (vedi doc modulo Patient e Xot).

## Collegamenti
- [Errore e best practice modulo Patient](../../Patient/docs/filament-error-fileupload-buttonlabel.md)
- Consultare sempre la [documentazione ufficiale Filament](https://filamentphp.com/project_docs/3.x/forms/fields/file-upload) prima di usare metodi non standard.
- Seguire la tabella di compatibilità dei metodi nei componenti Filament (vedi doc modulo Patient e Xot).

## Collegamenti
- [Errore e best practice modulo Patient](../../Patient/project_docs/filament-error-fileupload-buttonlabel.md)
- [Tabella metodi supportati](filament-component-methods.md)

---

> **NOTA IMPORTANTE**: Questo documento è la fonte principale per la gestione degli errori legati a FileUpload. Ogni modulo che implementa FileUpload DEVE collegarsi a questa doc e rispettare la regola.

## Regola vincolante
Questa doc va sempre consultata e linkata in ogni review e sviluppo che coinvolga FileUpload. Ogni modulo coinvolto deve avere un collegamento bidirezionale a questa doc.

## Collegamenti tra versioni di filament-error-fileupload-buttonlabel.md
* [filament-error-fileupload-buttonlabel.md](../../Patient/docs/filament-error-fileupload-buttonlabel.md)

