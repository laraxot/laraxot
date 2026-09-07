---
title: "Blog: Schemas/Tables audit — UserResource assente"
type: bmad-story
module: Blog
status: done
priority: P3
github_issue_url: https://github.com/laraxot/base_workorder_fila5/issues/25
github_discussion_url: https://github.com/laraxot/base_workorder_fila5/discussions/23
---

# BMAD Story — Blog: Schemas/Tables audit — UserResource assente

## Understand

Audit locale Blog collegato a [STORY-2.10](https://github.com/laraxot/base_workorder_fila5/issues/25).

Stato `Modules/Blog/app/Filament/Resources/UserResource`:

- Directory presente ma priva di `UserResource.php` (file Resource principale mancante)
- Contiene solo `Filters/.gitkeep` (placeholder vuoto)
- Nessun `Modules\Blog\Models\User` (esiste `Modules\User\Models\User`)
- Nessun riferimento in codebase a `Blog\Filament\Resources\UserResource`

Verifica: `find laravel/Modules/Blog -name "UserResource.php"` → vuoto.

## Plan

1. Confermare che `Blog\UserResource` è una directory scheletro/abbandonata
2. NON creare `Schemas/Tables` finché `UserResource.php` non esiste (sarebbe codice morto)
3. Aggiungere nota in story; nessun file `.php` da creare per questa wave

## Implement

Nessun file creato. Risultato: nessun intervento necessario in questa wave.

## Verify

- `find laravel/Modules/Blog -name "UserResource.php"` → nessun risultato
- `ls laravel/Modules/Blog/app/Filament/Resources/UserResource/` → solo `Filters/.gitkeep`
- grep `Blog\\\\Filament\\\\Resources\\\\UserResource` → nessun match

## Document

- Aggiornamento backlog se in futuro servisse aggiungere il Resource:
  - richiedere prima `Modules\Blog\Models\User` (esiste già `Modules\User\Models\User`)
  - valutare estensione `Modules\User\Filament\Resources\BaseUserResource` (canonica)
  - poi applicare il pattern Schemas/Tables canonico

## Status

- Branch: dev
- Module: Blog
- Done: nessun file creato; copertura UserResource rinviata
- Next: se Blog vuole gestire utenti specifici del modulo, creare prima il Model e la Resource canonica; poi riaprire sotto-issue