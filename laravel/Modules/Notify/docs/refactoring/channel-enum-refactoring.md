---
title: "Refactoring: Replace CHANNEL_CONFIG with Smart Enum"
type: concept
tags: [channel, enum, refactoring]
created: 2026-07-14
updated: 2026-07-14
qmd: "channel-enum-refactoring refactoring: replace channel_config with smart enum"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./channel-enum-implementation-complete.md"
  - "./channel-enum-implementation-update.md"
  - "./extract-method-pattern.md"
  - "./final-verification-report-channel-enum.md"
  - "./quality-assurance-report.md"
  - "./record-notification-constructor-slug.md"
  - "./record-notification-zen-constructor.md"
  - "./record-notification-zen-delegation.md"
---

# Refactoring: Replace CHANNEL_CONFIG with Smart Enum

## Goal
Replace the array-based `CHANNEL_CONFIG` constant in `SendRecordNotificationAction` with a strict, type-safe `ChannelEnum`.

## Philosophy & Strategy
-   **Type Safety**: Enums provided strong typing, preventing invalid strings from propagating through the system.
-   **Centralization**: Logic related to channel behavior (e.g., which notification class to use, whether normalization is needed) should live with the Channel definition itself, not in consumer classes. "Smart Enums" encapsulate this behavior.
-   **Refactoring Safety**: Renaming or modifying a channel becomes easier when it's an Enum case usage finding tool friendly) rather than a string literal key.

## Implementation Plan

### 1. Create `ChannelEnum`
Create `Modules/Notify/app/Enums/ChannelEnum.php`.
It will be a string-backed enum:
```php
enum ChannelEnum: string {
    case MAIL = 'mail';
    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';

    public function getNotificationClass(): string { ... }
    public function requiresNormalization(): bool { ... }
    public function requiresSmsContent(): bool { ... }
    // Logic for contact method resolution can be handled via a method that returns the method name, 
    // OR we can make the Action handle it map-based if it's specific to the Action's private methods.
    // For now, returning the method name is a pragmatic step to replace the existing config array 1:1.
}
```

### 2. Refactor `SendRecordNotificationAction`
-   Remove `CHANNEL_CONFIG`.
-   Update `execute` signature or logic to cast strings to `ChannelEnum` (or accept Enums). TO maintain backward compatibility, we'll cast.
-   Replace usage of `$config[...]` with `$channelEnum->method()`.

## Verification
-   PHPStan Level 10.
-   PHPMD / PHPInsights.
