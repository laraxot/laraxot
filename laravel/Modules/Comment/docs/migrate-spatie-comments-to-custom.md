# Migration of Spatie Comments Packages to Custom Comment Module

## Overview
This document outlines the migration of the [Spatie Comments](https://github.com/spatie/laravel-comments) packages into the custom **Comment** module within the Fixcity codebase. The goal is to replace external dependencies with an internally managed implementation that adheres to our **DRY + KISS** principles, while providing a more robust, branded, and maintainable solution.

## Current Structure
The existing implementation uses two external packages:
1. `spatie/laravel-comments` – Core comment functionality.
2. `spatie/laravel-comments-livewire` – Livewire integration for comment forms.

Both are located under:
```
/laravel/Modules/Comment/packages/spatie/
```

The current structure includes:
- Controllers, models, and controllers from Spatie.
- Livewire components for UI interactions.
- Configuration files defining allowed reactions, permissions, and transformations.

## Migration Goals
1. **Remove External Dependencies**: Eliminate reliance on external Spatie packages.
2. **Custom Implementation**: Build a proprietary comment system with enhanced features.
3. **Adhere to Project Conventions**: Use internal naming, structure, and coding standards.
4. **Improve Maintainability**: Centralize logic, reduce third-party dependencies.
4. **Preserve Functionality**: Ensure all existing features (commenting, reactions, notifications) remain intact or improved.

## Migration Steps

### 1. **Route and Controller Migration**
- Move routes from `Spatie\Comments\Routes` to custom routes in `RoutesServiceProvider`.
- Update controller methods to align with custom logic (e.g., approval workflows, notifications).

### 2. **Livewire Components Replacement**
- Replace Spatie Livewire components with custom Livewire components:
  - `CreateComment`, `ApproveComment`, `RejectComment`, `RenderComment`.
3. Update Blade views to use custom components.

### 4. **Database Schema Adjustments**
- Use Spatie's migration as a base but customize fields (e.g., add `branding_color` for theme alignment).
- Ensure relationships with `User`, `Post`, and `Tag` models are preserved.

### 4. **UI Components**
- Rebuild Blade views to comply with Fixcity design system:
  - Use `@components` directives for reusable parts (e.g., `comment-form`, `comment-list`).
  - Apply consistent styling via `ckm-ui-styling` and `ckm-ui-styling` configurations.

### 5. **Notifications and Alerts**
- Modify `ApprovedCommentNotification` and `PendingCommentNotification` to align with project branding.
- Adjust notification channels (email, in-app alerts) as per `aide-notification` standards.

### 5. **Security and Validation**
- Update validation rules to match project standards (e.g., stricter input sanitization).
- Audit permissions using `CheckPermissionMiddleware`.

## Documentation

Update the following documentation files:
- `docs/wiki/concepts/comment-migration-guide.md`
- `docs/wiki/module-convention.md` (update to reflect new comment module structure).
- `docs/wiki/rules/module-documentation-standards.md` – Ensure all module docs include:
  - Purpose
  - Responsibilities
  - Technologies Used
  - Migration Notes (if applicable)

## Directory Structure
```
/laravel/Modules/Comment/
├── src/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CommentController.php
│   │   │   └── Livewire/
│   │   │       ├── CreateComment.php
│   │   │       └── RenderComment.php
│   │   ├── Livewire/
│   │   │   ├── CommentList.php
│   │   │   └── CommentForm.php
│   │   └── Resources/
│   │       ├── Views/
│   │           ├── Components/
│   │               ├── CommentForm.blade.php
│   │               └── CommentCard.blade.php
│   │       └── ViewsServiceProvider.php
│   ├── Models/
│   │   ├── Comment.php
│   │   └── CommentNotificationSubscription.php
│   ├── Jobs/
│   │   ├── ApproveComment.php
│   │   └── RejectComment.php
│   ├── Events/
│   │   ├── CommentApproved.php
│   │   └── CommentRejected.php
│   ├── Resources/
│   │   ├── Lang/
│   │   └── en/
│   │       └─ comments.php
│   └── Services/
│       └── CommentService.php
└── views/
    ├── components/
    │   ├── comment-form.blade.php
│   │   └── comment-card.blade.php
```

## Migration Checklist
- [ ] Migrate all database migrations.
- [ ] Replace Spatie Livewire components with custom implementations.
- [ ] Update Blaze Blade templates to use new components.
- [ ] Migrate language strings and validation rules.
- [ ] Update database seeder to include initial comment data.
- [ ] Test all comment workflows (create, approve, reject, reply).

## Backward Compatibility
- Maintain DB schema compatibility where possible.
- Provide migration scripts in `database/migrations/YYYY_MM_DD_create_comments_table.php`.

## Performance Considerations
- Implement caching for comment lists using `Cache::remember()`.
- Optimize database queries with eager loading.

## Future Work
- Integrate with `pm evidenced` for audit trails.
- Add rich text formatting via Trix Editor.
- Enable reactions with animated feedback.

## References
- [Spatie Comments Package](https://github.com/spatie/laravel-comments)
- [Fixcity Design Guidelines](https://github.com/laraxot/base_fixcity_fila5)
- [Design System Documentation](../design-system.md)

---

*Document maintained by the Development Team. Last updated: 2026-06-06.*