# Filament v4 Upgrade Guide - Project Specific Notes

This document summarizes the key changes and considerations for upgrading this project from Filament v3 to v4, based on the official Filament v4 Upgrade Guide and specific challenges encountered in our modular application structure.

## **Critical Blocking Issue: Composer Dependency Resolution**

The primary blocker for a smooth upgrade is the persistent and unresolvable Composer dependency conflicts, particularly involving `phpstan/phpstan`, `pestphp/pest`, `larastan/larastan`, and `filament/upgrade`. These conflicts prevent `composer install` from completing successfully, leading to:
*   Missing production dependencies (e.g., `spatie/laravel-data`).
*   Inability to install required Filament v4 upgrade tools (`filament/upgrade`).
*   Blocking of full static analysis (PHPStan).
*   Runtime errors due to missing packages (e.g., `SvgNotFound`).

**Resolution:** Manual intervention is required to resolve Composer issues. This typically involves:
1.  **Strictly checking all `composer.json` files** (root, Modules/*, Themes/*) for problematic version constraints.
2.  **Clearing Composer cache:** `composer clear-cache`.
3.  **Removing `vendor/` directories and `composer.lock` files** across the project.
4.  **Careful `composer install -W` or `composer update -W` execution.**

Until Composer is stable, further progress on the Filament upgrade and comprehensive code quality checks will be severely hampered.

## **Key Upgrade Steps & Considerations**

### **1. Automated Upgrade Script (Blocked by Composer)**

*   **Command:**
    ```bash
    composer require filament/upgrade:"^4.0" -W --dev
    vendor/bin/filament-v4
    ```
*   **Notes:** This script automates many changes but does not handle all breaking changes. It requires PHPStan v2+ and Larastan v3+, which are currently blocked by Composer issues.

### **2. New Requirements**

*   **PHP:** 8.2+ (Current: 8.3.28 - OK)
*   **Laravel:** v11.28+ (Current: 12.42.0 - OK)
*   **Tailwind CSS:** v4.1+ (if using custom themes). This project likely uses custom themes, so this is a critical check for theme CSS files (`@import` and `@source` directives).
*   **`doctrine/dbal`:** No longer required by Filament, but must be explicitly added to `composer.json` if used elsewhere in the app.

### **3. Directory Structure Migration (Optional, Post-Composer)**

*   **Command:** `php artisan filament:upgrade-directory-structure-to-v4 --dry-run`
*   **Notes:** This will refactor resource and cluster directory structures. Manual adjustments for class references may be needed. Can be identified with PHPStan after the fact.

### **4. Configuration File Changes**

*   **Publishing:** `php artisan vendor:publish --tag=filament-config`
*   **`default_filesystem_disk`:** Now uses `FILESYSTEM_DISK` env var (was `FILAMENT_FILESYSTEM_DISK`). Projects should update `config/filament.php` or `AppServiceProvider` accordingly.
*   **`file_generation`:** New section in `config/filament.php` to control file generation flags. Relevant for maintaining v3 file generation styles.

### **5. Manual Breaking Changes - High Impact**

*   **`FileUpload`, `ImageColumn`, `ImageEntry` default visibility:** Now default to `private` disk (was `public` in v3).
    *   **Action:** For global `public` visibility, add `configureUsing()` in `AppServiceProvider` (e.g., `FileUpload::configureUsing(fn ($fileUpload) => $fileUpload->visibility('public'));`).
*   **Spatie Translatable Plugin Deprecation:** The official plugin is deprecated.
    *   **Action:** Replace `spatie/laravel-translatable` with `lara-zeus/spatie-translatable`. The automated upgrade script *should* handle this, but manual verification is crucial once Composer is fixed.

### **6. Manual Breaking Changes - Medium Impact**

*   **`Grid`, `Section`, `Fieldset` column spanning:** No longer `columnSpanFull()` by default.
    *   **Action:** Apply `->columnSpanFull()` where full width is desired, or use `configureUsing()` in `AppServiceProvider` for global v3 behavior.
    *   **Relevance:** Directly impacts `Modules\Geo\Filament\Forms\Components\AddressSection` (extends `Section`) and any custom Filament forms/infolists.
*   **`unique()` validation rule behavior:** Now ignores the current record by default.
    *   **Action:** If old behavior is needed, use `->ignoreRecord(false)` or `configureUsing()` globally.
*   **URL parameter names changed:** (e.g., `activeRelationManager` to `relation`).
    *   **Action:** Search for old parameter names in `::getUrl()` calls and update.
*   **Enum field state:** Fields linked to enums now *always* return the enum instance (not just its value).
    *   **Action:** Review code interacting with enum-backed fields to ensure it handles enum instances (e.g., `$state->value`). This impacts `AddressItemEnum` and `ContactTypeEnum`.

### **7. Manual Breaking Changes - Low Impact**

*   **`make()` method overrides:** Custom components overriding `make()` should now use `getDefaultName()` for default names and `setUp()` for default configuration.
    *   **Action:** Review custom Filament components (`AddressSection`, custom columns/fields) for `make()` overrides and refactor to `getDefaultName()` or `setUp()`.
*   **Table default primary key sorting:** Tables now sort by primary key by default.
    *   **Action:** Use `->defaultKeySort(false)` to disable this behavior if needed, or `configureUsing()` globally.
*   **`getTableRecordUrlUsing()` etc. replacements:** Several table methods have changed to `->recordUrl()`, `->recordClasses()`, `->recordAction()`, `->checkIfRecordIsSelectableUsing()`.

## **Project Specific Files to Review/Update**

*   **`laravel/composer.json`:** Resolve conflicts, ensure `filament/upgrade` and `lara-zeus/spatie-translatable` are correct.
*   **`config/filament.php`:** Update `default_filesystem_disk` and add `file_generation` flags.
*   **`app/Providers/AppServiceProvider.php` (or similar):** Add global `configureUsing()` calls for `FileUpload`, `Section`, `Table`, `Field` if desired.
*   **`Modules/Geo/app/Filament/Forms/Components/AddressSection.php`:**
    *   Verify `->columnSpanFull()` if intended.
    *   Review `setUp()` and `getFormSchema()` in light of v4 changes.
*   **`Modules/Notify/resources/views/filament/tables/columns/contact.blade.php`:** Address `SvgNotFound` (already temporarily fixed, permanent solution needs Composer fix and potential icon set re-registration).
*   **`Modules/Notify/app/Enums/ContactTypeEnum.php`:** Review `getIcon()` and usage of `TransTrait` related to icon resolution.
*   **All custom Filament Resources, Pages, Widgets, Forms, Tables, Infolists:** Review for any manual breaking changes, especially around `make()` overrides, column spanning, and URL parameters.
*   **Theme CSS files:** Check `@import` and `@source` directives if using custom Tailwind v4 themes.

## **Workflow for Upgrade**

1.  **Resolve Composer issues (MANUAL INTERVENTION REQUIRED).**
2.  Run `php artisan view:clear` and `php artisan cache:clear` frequently.
3.  Execute automated upgrade script if Composer allows.
4.  Run `php artisan filament:upgrade-directory-structure-to-v4` if desired.
5.  Review generated changes and apply manual breaking changes.
6.  Continuously run PHPStan (once working), PHPMD, PHP Insights.
7.  Perform `git commit` and `git push` after each logical, verified change.
8.  Update module/theme `docs` folders.
