# Architecture "Religion": Data-Driven Pages

## The Core Philosophy
In this project, **we do NOT create specific Blade files for content pages** (e.g., `about.blade.php`, `contact.blade.php`, `services/index.blade.php`).

Instead, the architecture is **strictly data-driven**:
1.  **Content**: Stored in `config/local/techplanner/database/content/pages/{slug}.json`.
2.  **Rendering**: Handled by a **single generic component** usually located at `Themes/Two/resources/views/pages/pages/[slug].blade.php`.
3.  **Logic**: The `[slug].blade.php` file reads the corresponding JSON file based on the URL slug and renders `x-blocks` accordingly.

## Why? (The Politics)
-   **DRY (Don't Repeat Yourself)**: We don't want 50 blade files that differ only in text.
-   **Separation of Concerns**: Content is in JSON (easy to edit/manage), Structure is in Components.
-   **Maintainability**: Fixing a bug in `[slug].blade.php` fixes it for ALL content pages.


## The Rule (The Law)
> **NEVER** create a directory like `pages/about/index.blade.php` if `about` is just a content page.
> **ALWAYS** check if usage of `[slug].blade.php` + `about.json` is sufficient.

## Forbidden Practices: No Controllers
> **NEVER** create Laravel Controllers (e.g., `PagesController`, `BlogController`) for frontend pages.
> **NEVER** define routes in `routes/web.php` for content pages (e.g., `Route::get('/about', ...)`).

**Why?**
-   We use **Laravel Folio** for routing.
-   We use **Livewire Volt** for logic.
-   We use **Laraxot** for architecture.

**Correct Approach:**
-   Routing: Handled automatically by `Themes/Two/resources/views/pages/pages/[slug].blade.php`.
-   Logic: Handled by View Components (Blocks) or Volt components if interactivity is needed.
-   Data: Sourced from strict JSON files in `config/local/techplanner/database/content/pages/`.

## How to add a new page
1.  Create `config/local/techplanner/database/content/pages/my-new-page.json`.
2.  Define the `blocks` (Hero, Text, Grid, etc.) inside that JSON.
3.  Visit `/it/pages/my-new-page` (or configured route).
4.  **DONE**. No Blade file needed. No Controller needed. No Route needed.
