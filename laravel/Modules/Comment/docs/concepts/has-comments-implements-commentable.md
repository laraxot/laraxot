---
title: "HasComments ⇒ implements Commentable (obbligatorio)"
type: concept
module: Comment
tags: [commentable, has-comments, contract, instanceof, 500, widgets]
created: 2026-06-10
updated: 2026-06-10
qmd: "HasComments implements Commentable instanceof InvalidArgumentException CommentsWidget requires Commentable model 500"
related:
  - ./filament-widgets-subject-subfolders.md
---

# HasComments ⇒ implements Commentable

## Regola

Ogni modello che usa `Modules\Comment\Models\Concerns\HasComments` **DEVE**
dichiarare `implements \Modules\Comment\Models\Contracts\Commentable`.

Il trait soddisfa l'intero contratto (`comments()`, `comment()`) ma PHP non
collega trait→interfaccia: senza la dichiarazione esplicita `instanceof
Commentable` è `false` e `CommentsWidget::resolveCommentable()` lancia
`InvalidArgumentException: CommentsWidget requires a Commentable model` → **500**
sulla pagina che monta il widget (caso reale: `/it/tickets/{id}`, 2026-06-10).

```php
use Modules\Comment\Models\Concerns\HasComments;
use Modules\Comment\Models\Contracts\Commentable;

class Ticket extends BaseModel implements Commentable /* , ... */
{
    use HasComments; // fornisce comments() + comment(); abstract commentableName()/commentUrl()
}
```

## Modelli conformi (2026-06-10)

- `Modules\Fixcity\Models\Ticket` ✅ (fix del 500)
- `Modules\Comment\Models\Comment` ✅ (nested comments)

## Checklist nuovo consumer

1. `use HasComments;` + `implements Commentable`
2. Implementare `commentableName()` e `commentUrl()`
3. Pest: `expect($model)->toBeInstanceOf(Commentable::class)`
