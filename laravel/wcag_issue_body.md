# WCAG 2.1 Remediation Plan for Theme Two

We are undertaking a project to bring **Theme Two** into compliance with WCAG 2.1 Level AA. This effort focuses on several key techniques identified by the W3C.

## Targeted Techniques
- **H44**: Using label elements to associate text labels with form controls.
- **F78**: Avoiding styling that removes visual focus indicators.
- **G195**: Using an author-supplied, visible focus indicator.
- **H30**: Providing descriptive link text.
- **C8**: Using CSS letter-spacing instead of spaces.
- **C38**: Using CSS flexbox for form layouts.
- **G18**: Ensuring contrast ratio of at least 4.5:1.
- **H98**: Using HTML autocomplete attributes.
- **ARIA6**: Using aria-label for objects.

## Remediation Plan
A detailed remediation plan has been created in `Themes/Two/docs/wcag-remediation-plan.md`. This plan includes specific actions for:
1.  Auditing all form controls for proper labeling.
2.  Ensuring visible focus indicators are present and high-contrast (no `outline: none`).
3.  Updating link text to be descriptive.
4.  Standardizing letter spacing via CSS.
5.  Verifying responsive form layouts.
6.  Checking contrast ratios.
7.  Adding autocomplete attributes to personal data fields.
8.  Implementing ARIA labels for icons and buttons.

We will track progress on this initiative through this issue/discussion.
