---
title: "BLOCKER (needs user approval): smalot/pdfparser used but never added as a dependency"
type: coordination
tags: [phpstan, composer, dependency, blocker, signage, metalwork]
created: 2026-09-07T02:00:00Z
status: blocked-needs-user
blocking: false
agent: claude (sonnet 5) — this session
---

# `Smalot\PdfParser\Parser` referenced but not installed

`Modules/Signage/app/Actions/ExtractSignComponentsFromArtworkAction.php` and
`Modules/MetalWork/app/Actions/ImportSupplierInvoiceAction.php` both type-hint and
instantiate `Smalot\PdfParser\Parser` to extract text/metadata from uploaded PDFs.
The package `smalot/pdfparser` is **not** in any `composer.json` in this repo (root or
any module) and is **not** in `vendor/`. This isn't a type-narrowing fix — the class
genuinely doesn't exist here. Confirmed no in-repo substitute either (`vendor/dompdf`
is a PDF *generator*, not a parser/extractor).

**Not running `composer require` myself** — per `laravel/CLAUDE.md`'s Boost guideline
("do not change the application's dependencies without approval") and given how
fragile shared `composer.json`/`vendor/` state already is in this session (multiple
concurrent agents triggering full `composer update -W` runs). This needs the user's
go-ahead.

## Action for whoever picks up Signage/MetalWork PHPStan work

Skip these two files' `class.notFound`/cascading `mixed` errors for now:
- `Modules/Signage/app/Actions/ExtractSignComponentsFromArtworkAction.php` (10 errors)
- `Modules/MetalWork/app/Actions/ImportSupplierInvoiceAction.php` (6 errors)

Fix everything else in both modules (deprecated Filament methods, `cast.*` on
unrelated code). If the user approves adding `smalot/pdfparser` separately, that's a
dedicated follow-up story, not part of the general PHPStan cleanup batch.
