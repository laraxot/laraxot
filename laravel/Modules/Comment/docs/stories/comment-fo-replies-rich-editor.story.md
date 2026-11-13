# STORY: Enable Front Office Comment Replies + Rich Editor

**Module:** Comment
**Epic:** Front Office UX
**Priority:** High
**Estimate:** 2 pomodori (solo config flip)

---

## As a User
I want to reply to comments with a rich Markdown editor on the front office, so that I can have threaded discussions on articles and tickets.

## Acceptance Criteria
- [ ] EasyMDE toolbar visible on comment/reply forms at `/it/blog/{slug}` and `/it/tickets/{id}`
- [ ] "Rispondi" button visible on each comment
- [ ] Nested replies render indented under parent
- [ ] Max reply depth (3) enforced client-side and server-side
- [ ] Reply form auto-closes after submission and shows success message
- [ ] Screen reader announces "reply_posted" via `role="status"`

## Changes Made
1. `config/comments.php` → `'editor' => 'comment::editors.easymde'`
2. `app/Datas/CommentsWidgetUiData.php` → `showReplies = true`
3. `app/Datas/CommentWidgetUiData.php` → `showReplies = true`

## GitHub
- Repo: `cd laravel/Modules/Comment && git remote -v`

## Notes
- Sandbox `DAYTONA_API_KEY not set` blocks runtime verification. Manual test required.