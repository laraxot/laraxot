# Footer Replication Plan

## Overview
This document outlines the plan to implement the Footer V1 component for Theme Two, matching the target site `https://lightseagreen-dogfish-560272.hostingersite.com/` and using data from `footer.json`.

## Target Site Analysis
- **Background**: Dark Blue / Gradient (likely matching the header brand blue `#1E5A96`).
- **Layout**: 4 Columns.
    1.  **Brand**: Logo/Name, Subtitle, Description, Social Icons.
    2.  **Normative**: "Normative & Certificazioni" list.
    3.  **Services**: "Servizi" list.
    4.  **Contact**: Address, Email, Phone, P.IVA, REA.
- **Bottom Bar**: Copyright text + Legal Links (Privacy, Cookies, Terms).

## JSON Data Mapping (`footer.json`)
The JSON file has the following structure for each locale (e.g., `it`):
- `blocks`: Array of blocks.
- Target Block Slug: `main-footer`.
- Data Fields:
    - `brand`: `name`, `subtitle`, `description`.
    - `social`: `linkedin`, `facebook`, `instagram`.
    - `normative`: `title`, `items` (array).
    - `services`: `title`, `items` (array).
    - `contact`: `title`, `address`, `city`, `email`, `phone`, `piva`, `rea`.
    - `legal`: `copyright`, `links` (array of label/url).

## Implementation Details (`v1.blade.php`)
- **Block Iteration**: The component must iterate through `$blocks` to find the one with `slug == 'main-footer'`.
- **Fallbacks**: Use `??` to prevent errors if data is missing.
- **Styling**: Use Tailwind CSS to match the target site. Use `bg-[#1E5A96]` or similar brand colors.
- **Icons**: Use `heroicons` or `filament::icon` as appropriate.
- **Multi-language**: Data comes from JSON which is already localized (different JSON blocks for `it` vs `en`). Functional links should respect `LaravelLocalization`.

## Verification
1.  **Code Quality**: No `property_exists`, strict types where possible (in Blade PHP blocks).
2.  **Visual Parity**: Match colors, spacing, and typography.
3.  **Functionality**: Links work, responsive design (stacking columns on mobile).
