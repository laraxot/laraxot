---
title: "git merge conflict inventory"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "git merge conflict inventory"
issues: []
discussions: []
status: draft
---

# Git Conflict Inventory

- Date: 2026-04-28
- Owner: Themes/Sixteen
- Files with conflict markers: 15

## Files

- resources/css/app-test.css
- resources/css/app.css
- resources/css/app1.css
- resources/css/components/bootstrap-italia-classes.css
- resources/css/components/header-footer-colors.css
- resources/css/design-comuni.css
- resources/views/components/feedback/notifiche.blade.php
- resources/views/components/forms/radio.blade.php
- resources/views/components/forms/upload.blade.php
- resources/views/components/layout/sections/sidebar.blade.php
- resources/views/components/media/rating.blade.php
- resources/views/components/navigation/megamenu.blade.php
- resources/views/components/navigation/skiplinks.blade.php
- resources/views/components/utilities/toggle.blade.php
- resources/views/partials/cookie-bar.blade.php

## Notes

- Inventory generated from `rg -l "^(<<<<<<<|=======|>>>>>>>)"`.
- Use this list as a volatile coordination map; re-open each file before editing because other agents may resolve items in parallel.