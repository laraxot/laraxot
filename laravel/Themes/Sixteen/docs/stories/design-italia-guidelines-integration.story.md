---
name: "Design Italia Guidelines Integration"
title: "Integrate Design Italia Guidelines into Sixteen Theme"
description: "Implement the Design Italia guidelines for PA digital services into the Sixteen theme, ensuring compliance with accessibility, usability, and design principles."
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
This story documents the integration of Design Italia guidelines (https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/) into the Sixteen theme for PA digital services compliance.

## Key Principles from Guidelines

### 1. Human-Centered Design (I cittadini al centro)
- Involve citizens and operators at every stage
- Model services on concrete needs and existing resources
- Design clear interaction flows
- Structure content simply with consistent communication style

### 2. Accessibility (Accessibilità)
- Render services accessible to all users (inclusivity principle)
- Comply with legal accessibility requirements
- Progressive improvement of existing services
- A/B testing for continuous improvement

### 3. Usability (Usabilità)
- Allow users to reach information/services simply
- Common PA criteria for information architecture
- User interviews and usability testing
- Language simplicity for broad audience

### 4. Content Design (Content Design)
- Simple, understandable language
- Brevity and use of lists
- Examples with images and links

## Implementation Steps
1. **Audit current Sixteen theme** against Design Italia principles
2. **Update design tokens** to match PA color palette (verde PA #007A52)
3. **Ensure WCAG 2.1 AA compliance** across all components
4. **Implement AGID-compliant header** with skip links
5. **Add breadcrumb navigation** (AGID standard)
6. **Verify multilingual support** (it/en/de/es)

## Acceptance Criteria
- [ ] All components pass WCAG 2.1 AA (APCA Lc ≥75 body, ≥45 large-bold)
- [ ] Header includes AGID skip links and institution branding
- [ ] Breadcrumb navigation follows AGID standard
- [ ] Design tokens align with PA palette
- [ ] Content follows simple language guidelines
- [ ] BMAD docs created with `github_issue` links

## Related
- `design-comuni-theme-identity-decision.md`
- `wcag-a11y-footer-header-language-access-improvements.md`
- `cookiebar-bootstrap-italia-integration.story.md`