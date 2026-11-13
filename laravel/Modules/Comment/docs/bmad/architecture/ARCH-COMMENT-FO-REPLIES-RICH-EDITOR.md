# ARCH-COMMENT-FO-REPLIES-RICH-EDITOR

**Status:** Completed (code changes applied, runtime verification blocked by sandbox)
**Date:** 2026-10-26
**Module:** Comment
**Author:** AI Agent (Ponytail mode)

---

## Problem Statement

Front Office comment system lacked:
1. **Reply threading** — `showReplies = false` by default in both `CommentsWidgetUiData` and `CommentWidgetUiData`
2. **Rich text editor** — default editor was `comment::editors.textarea` (plain textarea) instead of available `comment::editors.easymde` (EasyMDE Markdown editor)

Both features existed in codebase but were disabled by default configuration.

---

## Root Cause Analysis

| Component | Setting | Default Value | Effect |
|-----------|---------|---------------|--------|
| `CommentConfigData::uiSettings['editor']` | editor | `comment::editors.textarea` | No Markdown toolbar, no preview |
| `CommentsWidgetUiData::$showReplies` | list-level replies | `false` | No "Reply" button on comments, no nested rendering |
| `CommentWidgetUiData::$showReplies` | widget-level replies | `false` | Even if list enabled, individual widget hides reply UI |

**Why it happened:** Defaults were conservative (minimal UI). No documentation or test enforced these features ON for FO.

---

## Files Changed

| File | Change | Rationale |
|------|--------|-----------|
| `config/comments.php` | `'editor' => 'comment::editors.easymde'` | Enable rich Markdown editor by default |
| `app/Datas/CommentsWidgetUiData.php` | `showReplies = true` | Enable reply button in list view |
| `app/Datas/CommentWidgetUiData.php` | `showReplies = true` | Enable reply/edit/delete in comment widget |

---

## Architecture Impact

### Data Flow (unchanged)
```
CommentsWidget (list) 
  → CommentsWidgetUiData (showReplies=true) 
  → foreach comment: CommentWidget(comment, showReplies=true) 
  → CommentWidgetUiData (showReplies=true) 
  → canReply() → CheckReplyAllowedAction (depth < max_reply_depth=3)
```

### Rendering Path
- **Before:** `comments.blade.php` → `@if($uiConfig->showReplies) @livewire(CommentWidget) @else @component(comment-item) @endif`
- **After:** Always takes `CommentWidget` branch → full reply/edit/delete + nested replies via `nestedComments` relation

### Security
- `#[Locked]` on public properties (`comment`, `uiConfig`) prevents tampering
- `authorizeReply()` re-checks policy + `CheckReplyAllowedAction` server-side
- Rate limiting via `EnsureCommentRateLimitAction`

---

## Verification Required (Blocked)

**Sandbox Limitation:** `DAYTONA_API_KEY not set` — cannot run:
- `php -l` on modified files
- `phpstan analyse Modules/Comment`
- `pest` tests for Comment module
- Browser test at `http://192.168.1.40:8001/it` (private IP)

**Manual Verification Checklist** (run when sandbox available):
- [ ] `php -l config/comments.php`
- [ ] `php -l app/Datas/CommentsWidgetUiData.php`
- [ ] `php -l app/Datas/CommentWidgetUiData.php`
- [ ] `cd laravel && vendor/bin/phpstan analyse Modules/Comment --level=max`
- [ ] `cd laravel && vendor/bin/pest Modules/Comment/tests`
- [ ] Visit `/it/blog/{slug}` → verify EasyMDE toolbar visible on comment form
- [ ] Post comment → verify "Rispondi" button appears
- [ ] Click "Rispondi" → verify nested reply form loads (EasyMDE)
- [ ] Verify nested replies render under parent (indented)
- [ ] Verify max depth = 3 enforced

---

## Prevention — Never Again

### 1. Add Default-Enforcement Test
Create `Modules/Comment/tests/Unit/CommentDefaultsTest.php`:
```php
test('FO comment defaults enable replies and rich editor', function () {
    $config = \Modules\Comment\Datas\CommentConfigData::make();
    expect($config->uiSettings['editor'])->toBe('comment::editors.easymde');

    $listUi = new \Modules\Comment\Datas\CommentsWidgetUiData([]);
    expect($listUi->showReplies)->toBeTrue();

    $itemUi = new \Modules\Comment\Datas\CommentWidgetUiData([]);
    expect($itemUi->showReplies)->toBeTrue();
});
```

### 2. Document in Module README
Add "Front Office Defaults" section to `Modules/Comment/README.md`:
> Default config enables: `showReplies=true`, `editor=easymde`. Override via config/comments.php or widget parameters.

### 3. Add to Quality Gate
Update `docs/wiki/rules/post-edit-quality-gate.md` → include "Comment FO defaults" check in module verification script.

---

## Related

- **Story:** `docs/stories/comment-fo-replies-rich-editor.story.md`
- **Brainstorming:** `docs/bmad/brainstorming/BMAD-BRAINSTORMING-COMMENT-FO-REPLIES.md`
- **Theme Sixteen integration:** `laravel/Themes/Sixteen/docs/wiki/concepts/comment-fo-integration.md`