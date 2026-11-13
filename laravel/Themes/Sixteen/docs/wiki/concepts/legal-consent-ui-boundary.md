---
title: "Legal Consent UI Boundary"
type: concept
theme: Sixteen
sources:
  - "../../../../../../bmad-output/registration-compliance-audit.md"
  - "../../../../../../bmad-output/stories/5.3.registration-consent-evidence.story.md"
  - "../../../../../../bmad-output/stories/6.13.legal-identity-content-checklist.story.md"
confidence: high
created: 2026-10-14
updated: 2026-10-14
tags: [sixteen, legal, consent, accessibility, localization, design-comuni]
related:
  - "../../../../../Modules/Gdpr/docs/wiki/concepts/registration-account-lifecycle.md"
  - "../../../../../Modules/User/docs/wiki/concepts/auth-registration-verification-lifecycle.md"
status: draft
qmd: "legal consent ui boundary sixteen"
---

# Legal consent UI boundary

## Theme responsibility

Sixteen owns the accessible presentation of registration and privacy controls:
labels, links, focus order, error messaging, responsive layout, locale rendering
and Design Comuni visual language. It does not decide legal bases, retention,
document versions or whether a treatment is required.

## Required UI contract

- Required privacy and terms controls are separate, unchecked by default and
  visibly linked to the localized published documents.
- Optional marketing consent is clearly optional and never coupled to account use.
- Links and validation messages work with keyboard, screen readers and every
  enabled locale.
- The UI exposes the current preference/revocation entry point after login without
  presenting cookie choices as authenticated consent.
- No Bootstrap runtime is introduced: use the existing TailwindCSS + Alpine.js +
  Lit + DaisyUI + Flowbite stack and preserve the semantic Design Comuni contract.

## Verification

Visual review alone is insufficient. The story must combine browser checks for
keyboard/focus/error states, localized link checks and evidence that the rendered
URLs resolve to the same published documents recorded by Gdpr.

## References

- [Gdpr registration and account boundary](../../../../../Modules/Gdpr/docs/wiki/concepts/registration-account-lifecycle.md)
- [User authentication lifecycle](../../../../../Modules/User/docs/wiki/concepts/auth-registration-verification-lifecycle.md)
- [BMAD consent-evidence story](../../../../../../bmad-output/stories/5.3.registration-consent-evidence.story.md)

