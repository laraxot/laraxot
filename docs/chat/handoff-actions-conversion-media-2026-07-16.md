# Handoff — Media Services/Support → QueueableAction (2026-07-16)

## Scope
Convert `laravel/Modules/Media/app/Services` and `app/Support` to `app/Actions/{Context}`
QueueableAction pattern. `tests/Support` left untouched (out of scope).

## State found
Action equivalents were already present and committed. Remaining legacy files (still `.php`)
plus one stray misplaced copy were finalized this session.

## Legacy → Action mapping

| Legacy (now `.bak`) | Action(s) | Location |
|---|---|---|
| `Services/VideoStream.php` | `StreamVideoAction` | `Actions/Stream/` |
| `Services/SubtitleService.php` | `ParseSubtitleXmlAction`, `ExtractSubtitlePlainTextAction`, `ConvertSrtToVttAction`, `UpdateModelSubtitleFieldAction` | `Actions/Subtitle/` |
| `Support/TemporaryUploadPathGenerator.php` | `GenerateTemporaryUploadPathAction`, `GetTemporaryUploadPathAction`, `GetTemporaryUploadConversionPathAction`, `GetTemporaryUploadResponsivePathAction` | `Actions/`, `Actions/TemporaryUpload/` |
| `Support/Ffmpeg/MediaExporterResolver.php` | `ResolveMediaExporterAction` | `Actions/Ffmpeg/` |
| `Actions/Stream/SubtitleService.php` (stray copy, namespace `Services`) | removed (`.bak`) | — |

## Callers
No application code referenced the old namespaces. `rg "Modules\Media\Services\\"` and
`"Modules\Media\Support\\"` returned only the legacy files themselves + docs. No caller
edits required.

## Files renamed to `.bak` (never git rm)
- `app/Services/VideoStream.php.bak`
- `app/Services/SubtitleService.php.bak`
- `app/Support/TemporaryUploadPathGenerator.php.bak`
- `app/Support/Ffmpeg/MediaExporterResolver.php.bak`
- `app/Actions/Stream/SubtitleService.php.bak`

## Quality gates
- **PHPStan** `Modules/Media`: 12 errors, ALL pre-existing in `tests/` (missing
  `Modules\Media\Tests\Unit\Actions\TestCase`). None in app/ code; conversion introduced 0.
- **Pest** `Modules/Media`: blocked by same pre-existing missing TestCase scaffolding.
- **check-no-app-support.sh**: clean for Media (only `.bak` remain).
- **audit-queueable-action-trait.sh**: conversion Actions compliant. Pre-existing
  MISSING_TRAIT on unrelated Actions (`S3/*`, `Image/SvgExistsAction`,
  `SaveAttachmentsAction`, `GetAttachmentsSchemaAction`) — separate follow-up.
- phpmd / phpinsights: not run (not part of the blocking set here).

## Follow-ups
1. Fix `tests/Unit/Actions/TestCase` scaffolding (unblocks phpstan+pest for Media).
2. Add `QueueableAction` trait to the pre-existing non-compliant Actions listed above.
3. Note: `ResolveMediaExporterAction` has no callers yet (mirrors old resolver, which
   was also unused) — wire into Video conversion actions if/when needed.
</content>
