---
title: "Login loading feedback"
type: note
status: draft
updated: 2026-10-19
tags: [docs, sixteen, auth]
qmd: "login loading feedback sixteen"
---
# Login loading feedback

## Intent

The login action is a Livewire request. The submit control must make the click
immediately perceivable, prevent duplicate submissions, and expose the same
state to assistive technology without changing the page layout.

## Contract

- `wire:target="save"` scopes the loading state to the login action.
- `wire:loading.attr="disabled"` prevents duplicate submissions.
- `aria-busy`, `role="status"`, and `aria-live="polite"` expose progress.
- The visible spinner is decorative; the translated status text is the
  announcement.
- `motion-reduce:transition-none` and `motion-reduce:transform-none` honour
  reduced-motion preferences.
- The form keeps the same button dimensions in default and loading states.

Bootstrap Italia's progress-indicator guidance was used as an accessibility
reference only. The implementation remains Tailwind + Livewire + Filament;
Bootstrap Italia is not loaded or copied into the theme.
