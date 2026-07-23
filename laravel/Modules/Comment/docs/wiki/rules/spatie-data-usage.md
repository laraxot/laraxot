---
title: "Widget property consolidation with Spatie Laravel‑Data"
type: decision
tags: [widget, data, spatie, refactor]
created: 2026-06-10
updated: 2026-06-10
---

## Decision

The `CommentWidget` currently defines many public properties controlling its behaviour (e.g. `$showAvatar`, `$newestFirst`, `$writable`, `$replyText`, `$isEditing`, `$editText`, `$showReplies`, `$showReactions`). Maintaining a long list of independent properties can be error‑prone and makes the widget harder to test.

**Proposed approach**: group these UI‑state flags into a single immutable data object using the **Spatie Laravel‑Data** package. The widget would receive a `CommentWidgetData` (or similar) instance via constructor or `mount()` and expose only the data object to the Blade view.

### Benefits
- **Type safety**: each property is strongly typed and validated by Laravel‑Data.
- **Reduced boilerplate**: eliminates repetitive `$this->property = …` assignments.
- **Improved testability**: data objects can be instantiated in isolation for unit tests.
- **Future‑proof**: adding new UI flags only requires updating the Data class, not the widget class.

### Implementation sketch
```php
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class CommentWidgetData extends Data
{
    public bool $showAvatar = true;
    public bool $newestFirst = false;
    public bool $writable = true;
    public string $replyText = '';
    public bool $isEditing = false;
    public string $editText = '';
    public bool $showReplies = false;
    public bool $showReactions = false;
}
```
In the widget:
```php
protected CommentWidgetData $state;

public function mount(): void
{
    $this->state = CommentWidgetData::fromArray([]);
    // ... populates commentId, etc.
}
```
Blade would reference `$state->showAvatar` etc.

### Impact
- No functional change for existing UI. The refactor can be applied gradually.
- Allows future extensions (e.g. localisation, theming) with minimal widget churn.

### Status
- **Proposal** – documented for future sprint. No code changes performed yet.

---

*Documented as part of the module’s wiki to capture architectural reasoning.*