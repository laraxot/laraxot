---
title: "Documentation Index — Media Module"
module: "Media"
type: documentation
tags: [index, navigation]
last_updated: 2026-07-28
---

# Documentation Index — Media Module

**Last updated:** 2026-07-28  
**Module Status:** ✅ PHPStan Level 10 Compliant

## Quick Navigation

- **[Overview & README](./README.md)** — Use cases, features, dependencies
- **[Architecture](./ARCHITECTURE.md)** — System design and core patterns
- **[API Documentation](./API.md)** — Actions, Models, Contracts
- **[Components Guide](./COMPONENTS.md)** — FFmpeg, Intervention Image, Storage
- **[Contributing](./CONTRIBUTING.md)** — Development workflow

---

## Module Content Overview

| Category | Count | Notes |
|----------|-------|-------|
| **Actions** | 49 | File upload, processing, S3, FFmpeg, video/image transforms |
| **Models** | 8 | Media, MediaConvert, TemporaryUpload, BaseModel |
| **Controllers** | 2 | HTTP request handling |
| **Migrations** | 3 | Database schema (medias, media_converts, temporary_uploads) |
| **Filament Resources** | 30 | Admin UI components, tables, forms |
| **Services** | 2 | SubtitleService, VideoStream utilities |
| **Conversions** | 2 | ImageGenerators, VideoGenerators |
| **Contracts** | 2 | PathGeneratorContract, custom interfaces |
| **Commands** | 1 | Artisan console commands |
| **Data Objects** | 4 | AttachmentToSaveData, ConvertData, SaveAttachmentsData, CloudFrontData |

**Total:** 101+ PHP classes

---

## Recently Updated Files

| File | Last Modified | Type |
|------|---|------|
| ffmpeg-usage.md | 2026-07-14 | Concept |
| FILE_MANAGEMENT_ARCHITECTURE.md | 2026-07-28 | Architecture |
| PRODUCT_STRATEGY.md | 2026-07-28 | Strategy |
| PRODUCT_ROADMAP.md | 2026-07-28 | Roadmap |
| 00-INDEX.md | 2026-07-14 | Index |
| BAD_PRACTICES.md | 2026-07-28 | Quality |
| MIGRATIONS.md | 2026-07-21 | Reference |
| PRODUCT_LAUNCH_PLAN.md | 2026-07-28 | Launch |
| PERFORMANCE-OPTIMIZATION.md | 2026-07-21 | Performance |
| PROJECT-STRUCTURE.md | 2026-07-21 | Architecture |

---

## Documentation Categories

### Core Documentation
- [README.md](./README.md) — Module overview, features, use cases
- [ARCHITECTURE.md](./ARCHITECTURE.md) — System design, component structure
- [COMPONENTS.md](./COMPONENTS.md) — FFmpeg, Intervention Image, Cloud Storage

### API & Development
- [API.md](./API.md) — Action signatures, model methods, contracts
- [PATTERNS.md](./PATTERNS.md) — Architectural patterns, best practices
- [CONTRIBUTING.md](./CONTRIBUTING.md) — Development workflow

### Operations & Troubleshooting
- [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) — Error resolution, common issues
- [PERFORMANCE-OPTIMIZATION.md](./PERFORMANCE-OPTIMIZATION.md) — Performance tuning
- [MIGRATIONS.md](./MIGRATIONS.md) — Database schema, upgrades

### Quality & Standards
- [BAD_PRACTICES.md](./BAD_PRACTICES.md) — Anti-patterns to avoid
- [testing-guidelines.md](./testing-guidelines.md) — Unit & integration tests

### Strategy & Planning
- [PRODUCT_STRATEGY.md](./PRODUCT_STRATEGY.md) — Strategic vision
- [PRODUCT_ROADMAP.md](./PRODUCT_ROADMAP.md) — Feature roadmap
- [PRODUCT_LAUNCH_PLAN.md](./PRODUCT_LAUNCH_PLAN.md) — Launch timeline

---

## Quick Start

1. **Read first:** [README.md](./README.md)
2. **Understand patterns:** [PATTERNS.md](./PATTERNS.md)
3. **Use the API:** [API.md](./API.md)
4. **Deploy safely:** [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)

---

## Key External Links

- **[Laravel-FFMpeg Documentation](https://github.com/protonemedia/laravel-ffmpeg)** — Video processing library
- **[Intervention Image](https://image.intervention.io/)** — Image manipulation
- **[AWS S3 Integration](./s3test-corrections.md)** — Cloud storage setup

---

## Dependencies

### Composer Packages
- `pbmedia/laravel-ffmpeg:^8.7` — Video/audio encoding
- `intervention/image:^3.0` — Image transformation
- `laravel/framework:^11.0` — Laravel framework
- `spatie/laravel-queueable-action` — Queueable actions

### Required System Packages
- `ffmpeg` — Video encoding engine
- `imagemagick` or `gd` — Image processing

---

## Related Modules

- **[Xot](../../Xot/docs/README.md)** — Framework base, HasMedia trait
- **[CloudStorage](../../CloudStorage/docs/README.md)** — Cloud provider abstraction
- **[Cms](../../Cms/docs/README.md)** — Content media integration
- **[Filament](../../Filament/docs/README.md)** — Admin UI framework

---

**Navigation:** [Home](../README.md) | [Contributing](./CONTRIBUTING.md) | [Troubleshooting](./TROUBLESHOOTING.md)