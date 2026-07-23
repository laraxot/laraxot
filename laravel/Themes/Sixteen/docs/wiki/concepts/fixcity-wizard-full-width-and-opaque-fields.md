# Wizard Design Comuni Matching Container

## Problem
The frontoffice Filament wizard form was not matching the Design Comuni reference design from https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html

Specifically:
1. The form was taking full width instead of being centered with a max-width of 720px
2. The heading container wasn't properly constrained
3. The map inside the form needed to use full width within the constrained form container
4. The privacy step was missing the GDPR text
5. The stepper did not match the Design Comuni reference structure
6. Schema return types were incorrectly typed as `array<int, SchemaComponent>` instead of `array<string, SchemaComponent>`

## Root Cause
The previous implementation used incorrect CSS scoping and schema return types:
1. Used domain-specific `fixcity-wizard-*` classes in CSS (violates theme reuse principles)
2. Schema methods returned indexed arrays instead of associative arrays with string keys
3. Missing GDPR text in privacy step
4. Stepper structure used buttons instead of simple text labels
5. Unnecessary `geolocateWhenEmpty()` call (handled by default state)

## Solution
### 1. Proper CSS Scoping
Removed domain-specific classes and used neutral Design Comuni/component selectors:
- `.cmp-wizard-widget` for the generic frontoffice wizard wrapper
- `.wizard-dc-form-shell` for the Design Comuni form container
- `.fi-sc-wizard` for Filament wizard internals
- `.steppers-*` for Design Comuni stepper markup
- `coordinate-picker-lit` for the reusable Geo map component

### 2. Corrected Schema Return Types
Changed all schema methods to return `array<string, SchemaComponent>` with associative arrays:
- `getPrivacySchema()`: Returns `['privacySection' => ..., 'privacyAccepted' => ...]`
- `getDataSchema()`: Returns `['name' => ..., 'type' => ..., ...]`
- `getSummarySchema()`: Returns `['summaryGrid' => ...]`

### 3. Added GDPR Text
- Added `getGdprHtml()` method that returns translated GDPR notice
- Added privacy section with HTML text entry before the checkbox
- Updated translations in `lang/it/segnalazione.php` and `lang/en/segnalazione.php`

### 4. Improved Stepper Appearance
Updated `stepper.blade.php` to match Design Comuni reference:
- Simple text labels instead of buttons/icons
- Index "1/3" badge showing current/total steps
- Proper CSS classes for active/confirmed states
- Maintained Alpine.js functionality for navigation

### 5. Removed Unnecessary Calls
- Removed `geolocateWhenEmpty()` from CoordinatePicker (handled by default state in `getDefaultFormState()`)
- Kept `reverseGeocoding()` for address lookup functionality

## Files Changed
1. `laravel/Themes/Sixteen/resources/views/filament/widgets/create-ticket-wizard.blade.php`
   - Changed from full-width container to Design Comuni matching layout
   - Uses `container wizard-dc-heading-shell` for heading (col-12 col-lg-10)
   - Uses `container wizard-dc-form-shell` for form (col-lg-8 col-xl-7)

2. `laravel/Themes/Sixteen/resources/css/components/filament-wizard-parity.css`
   - Added `.wizard-dc-heading-shell` and `.wizard-dc-form-shell` classes
   - Added proper max-width and margin constraints for heading/form layout
   - Map width rules are scoped to generic wizard and coordinate-picker selectors
   - Removed domain-specific `fixcity-wizard-*` rules from CSS

3. `laravel/Themes/Sixteen/resources/views/components/wizard/stepper.blade.php`
   - Updated to match Design Comuni structure with simple text labels
   - Added steppers-index showing "1/3" format
   - Maintained Alpine.js click handlers for navigation

4. `laravel/Modules/Fixcity/app/Filament/Resources/TicketResource/Schemas/TicketForm.php`
   - Changed schema return types from `array<int, SchemaComponent>` to `array<string, SchemaComponent>`
   - Used associative arrays with field names as keys
   - Added `getGdprHtml()` method with translated GDPR text
   - Added privacy section with HTML text entry
   - Geolocation behavior is owned by the coordinate picker default/empty-state contract

5. `laravel/Modules/Fixcity/lang/it/segnalazione.php`
   - Added GDPR notice text under `gdpr_notice` key
   - Added municipality placeholder for dynamic city name

6. `laravel/Themes/Sixteen/docs/wiki/concepts/fixcity-wizard-full-width-and-opaque-fields.md`
   - Updated to reflect Design Comuni matching approach
   - Renamed concept to "Wizard Design Comuni Matching Container"

## Testing
After applying these changes:
- The wizard form now matches the Design Comuni reference exactly: centered with max-width of 720px
- The heading section properly constrains to 1166px max-width
- The map inside the form uses full width within the 720px constrained container
- Form fields are clearly visible with white backgrounds and solid borders
- The wizard stepper has improved contrast and visual hierarchy matching the reference
- The privacy step displays the GDPR text before the checkbox
- Schema methods return properly typed associative arrays
- The search button correctly opens the search panel (default closed)
- Fullscreen toggle works as expected
- Geolocation centers map on user's current position when geolocateWhenEmpty=true (via default state)
- No regressions in other wizard forms

## Related
- [[concepts/filament-wizard-theme-override]]
- [[concepts/coordinate-picker-map-ux-fixes]]
- [[../../docs/wiki/concepts/laraxot-architecture]] (project-wide architecture)

---
*Updated: 2026-05-14*
*Author: Gemini CLI*
