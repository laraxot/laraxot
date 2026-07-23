# Theme Guidance: Wizard Summary (Sixteen)

Summary step changes in module code require theme awareness when rendering summary views or custom submit button partials. Keep the following in theme docs:

- If using theme partials for submit button or summary wrappers, ensure they accept Infolist markup and do not assume `SchemaView` placeholders.
- Prefer lightweight CSS overrides; do not embed logic in Blade partials that duplicate label/value mapping.

Related: https://filamentphp.com/docs/5.x/infolists/overview

Notes for theme developers

- If a theme provided `pub_theme::filament.wizard.submit-button` view exists, it remains compatible; Infolist renders standard HTML entries.
- Avoid creating Blade partials that attempt to re-map wizard state — let the widget schema (Infolist) handle state mapping.