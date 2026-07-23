# XotBaseField View Override Rule (Themes)

## Context
All Filament form components extending `XotBaseField` resolve their views automatically through `GetViewByClassAction`.

## How Overrides Work
The `GetViewByClassAction` checks for view existence in this specific order:

1.  **Theme Override**: `pub_theme::filament.forms.components.{name}`
2.  **Module Default**: `{module}::filament.forms.components.{name}`

## Rule for Themes
If a theme needs a custom visual representation for a standard module field, it **MUST** provide a matching blade file in its `resources/views/filament/forms/components/` directory.

Example for `MapPicker`:
- File: `Themes/{ThemeName}/resources/views/filament/forms/components/map-picker.blade.php`

## Why this is preferred
This mechanism allows the frontend to stay decoupled from the backend logic while ensuring that visual consistency is maintained across the entire application.
