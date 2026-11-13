---
name: "Disclaimer and Cookie Preferences Integration"
title: "Integrate Disclaimer and Cookie Preferences into Sixteen Theme"
description: "Integrate GDPR-compliant disclaimer page and cookie preferences management into the Sixteen theme using BMAD framework, ensuring multilingual support (it/en/de/es) and proper consent management."
status: "ready-for-dev"
module: "Sixteen"
epic: "Front Office Redesign"
created: "2026-10-14"
updated: "2026-10-14"
github:
  repository: "https://github.com/your-org/your-repo"
  issue: "https://github.com/your-org/your-repo/issues/XXX"
  discussion: "https://github.com/your-org/your-repo/discussions/XXX"
links:
  github_issue: "https://github.com/your-org/your-repo/issues/XXX"
---

## Overview
This story documents the integration of a GDPR-compliant disclaimer page and cookie preferences management into the Sixteen theme, ensuring alignment with Italian regulations and multilingual support (it, en, de, es).

## Steps Taken
1. **Disclaimer Page Creation**:
   - Created `disclaimer.blade.php` in `laravel/Themes/Sixteen/resources/views/` extending the guest layout.
   - Populated with content from termsfeed.com and servizindustria.it, including sections on purpose, legal notices, and contact info.
   - Linked to `terms.blade.php` for consistency.

2. **Cookie Preferences**:
   - Updated `cookie-bar.blade.php` to link to the actual preferences route (`/cookie-consent/preferences`) instead of `#preferences`.
   - Ensured the preferences page displays cookie inventories in 4 languages (it, en, de, es) using Laravel localization.
   - Verified that `data-bs-accept="cookiebar"` is preserved for cookie consent functionality.

3. **BMAD Documentation**:
   - Created `docs/stories/disclaimer-integration.story.md` with full details.
   - Updated `docs/bmad/decision-log.md` to reflect new decisions.

## Acceptance Criteria
- [ ] Disclaimer page is accessible and compliant with GDPR/Italian law.
- [ ] Cookie preferences link directs to a page with full inventories in 4 languages.
- [ ] Cookie bar functions correctly with `data-bs-accept="cookiebar"`.
- [ ] All BMAD docs include `github_issue` link.

## Related
- `cookiebar-bootstrap-italia-integration.story.md`
- `terms-integration.md` (investigation file)