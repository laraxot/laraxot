---
title: "Livewire Assets Out‑of‑Date Fix"
type: rule
updated: 2026-06-10
---

## Symptom
Browser console reports:
```
Livewire: The published Livewire assets are out of date
See: https://livewire.laravel.com/docs/installation#publishing-livewires-assets-to-public-directory
```
This usually appears after a Livewire version bump or after cleaning the `public` folder.

## Fix
Run the Livewire publish command with the `--force` flag to overwrite the existing assets:
```bash
php artisan livewire:publish --force
```
The command copies the latest JavaScript and CSS files to `public_html/vendor/livewire`.

## When to run it
- After upgrading the `livewire/livewire` Composer package.
- After clearing the public folder (e.g., during a fresh deployment).
- When you see the console warning during development.

## Verification
Open the network tab and verify the presence of:
- `public_html/vendor/livewire/livewire.js`
- `public_html/vendor/livewire/livewire.min.js`
- `public_html/vendor/livewire/livewire.css`

If the files load without 404, the issue is resolved.

---
*Generated on 2026‑06‑10 by the OpenCode assistant.*