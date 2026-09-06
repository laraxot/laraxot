# Media module philosophy

## Zen: Media is a pipeline, not a blob

Media is fundamentally a *transformation pipeline*, not a storage bucket. An uploaded file enters as raw bytes and exits as consumable, optimized artifacts: thumbnails, streaming-ready video, responsive images, or archival copies. The pipeline is stateful, async-first, and observable at every stage.

A single source file may spawn dozens of derivatives (conversions, thumbnails, transcodes). The module treats each derivative as a first-class tracked object with its own metadata, progress, error state, and expiry window.

## Architecture

### Core models

**Media** (polymorphic source file)
- Extends `Spatie\MediaLibrary\MediaCollections\Models\Media`
- Belongs to any model via polymorphic relation: users upload to `TemporaryUpload`, then move to their real parent
- Tracks original metadata: `mime_type`, `size`, `custom_properties`, `generated_conversions`
- Maintains creator audit trail via `Updater` trait
- Provides `getUrlConv()` for accessing named conversion variants (thumb, 800, 400)

**MediaConvert** (async conversion record)
- One record per scheduled or completed conversion
- Tracks codec selection, bitrate, resolution, thread count, speed preset
- Stores conversion progress: `percentage`, `remaining` time, `execution_time`
- Contains `disk`, `file`, `converted_file` accessors to reconstruct S3 paths
- Linked to parent Media via `BelongsTo` relation

**TemporaryUpload** (staging area for fresh uploads)
- Isolated per session via `session_id` (enforced unless config disabled)
- MassPrunable: auto-deletes after configurable TTL (prevents disk bloat)
- Accepts uploads via `createForFile()` or `createForRemoteFile()` class methods
- Generates optional preview conversion on demand
- Provides `moveMedia()` to atomically transfer to final parent model with UUID preservation

### Action pipeline (4 + diagnostics)

1. **SaveAttachmentsAction**: Marshals form-uploaded files from temporary disk into MediaLibrary
   - Reads temp file → creates temp copy → stores via MediaLibrary → updates parent model fields
   - Cleanup: removes temp copy after transaction

2. **AttachMediaAction**: Queueable stub for polymorphic media attachment (ready for extension)

3. **ConvertVideoAction**: Delegates FFmpeg transcoding via `laravel-ffmpeg` library
   - Opens source file from disk
   - Applies X264 format + 1Mbps bitrate
   - Saves to target disk/path
   - Returns signed S3 URL

4. **Diagnostic Actions**: Deep S3/CloudFront troubleshooting (15+ subactions)
   - AWS credential/permission validation
   - CloudFront distribution config checks
   - IAM policy simulation
   - Bucket-level and object-level access verification

## Pipeline walkthrough

### Upload phase
1. User uploads file to form (chunked or single-shot)
2. Stored temp on `attachments` disk (or configured temp disk)
3. `TemporaryUpload::createForFile()` creates staging record with session-scoped UUID
4. Optional preview conversion triggered if `config('media-library.generate_thumbnails_for_temporary_uploads')` is true

### Validation phase
1. `FileExtensionRule` validates against whitelist (prevents upload of .exe, .scr, etc.)
2. Mime-type detection via MediaLibrary internals
3. Size constraints enforced at upload time or form validation

### Conversion phase (async)
1. `SaveAttachmentsAction` moves temp file into permanent collection
2. Each collection name maps to a disk/path strategy (e.g., avatars → public/avatars, documents → private/docs)
3. `MediaConvert` job spawned for each profile (only if async conversion enabled)
4. FFmpeg profiles run headless: VP9/WebM for streaming, X264 for archive, thumbnail for preview
5. Progress tracked in `MediaConvert` table for user-facing progress bars

### Delivery phase
1. S3 signed URLs via `GetCloudFrontSignedUrlAction` (time-limited, revocable)
2. CloudFront distribution serves cached copies (origin = S3)
3. Private collections: signed URLs required
4. Public collections: direct S3 URLs or CDN paths

## FFmpeg and codec handling

### Profiles (Wireable ConvertData objects)
- **thumbnail**: VP9 @ ultrafast, 300x300 crop, Vorbis audio
- **streaming**: VP9 @ medium preset, multi-bitrate (ABR ready), 4-thread, speed=4
- **archive**: X264 @ 1Mbps, full-quality audio, production-grade codec detection
- Custom profiles: define new `ConvertData` instances per use case

### Metadata extraction
- Codec detection: `getFFMpegFormat()` infers format from filename extension + mime type
- Frame extraction: `GetVideoScreenshotAction` grabs first I-frame
- Duration: `GetVideoDurationAction` parses via FFmpeg mediainfo
- Subtitle parsing: `ParseSubtitleXmlAction`, `ExtractSubtitlePlainTextAction`, `ConvertSrtToVttAction`

### Error resilience
- `ConvertData` is Livewire-wireble for real-time status updates
- `MediaConvert` records persist failed states (retry-safe)
- FFmpeg errors logged with full command context
- Fallback: if conversion fails, original file remains accessible

## Integration points

### Notify module
- Media attachments sent in email via `SaveAttachmentsAction` callback
- Signed URLs embedded in HTML emails (no direct S3 access from external systems)

### Cms module
- Article/Post images stored via Media module
- Responsive conversions auto-generated (800, 400 pixel variants)
- Collection name: `article_images` or `post_images`

### User module
- Avatar upload via TemporaryUpload staging
- Profile picture accessed via signed URL (CloudFront cached)
- Collection: `avatars`

### Polymorphic AttachMedia
- Any model can call `$model->addMedia($file)->toMediaCollection('images', 's3-disk')`
- Attachment type tracked via Enum: `AttachmentTypeEnum::Image`, `::Video`, `::Document`
- Audit trail: `created_by`, `updated_by` fields automatically populated

## Best practices and anti-patterns

### Best practices
- Always use async conversion: queue ConvertVideoAction, never process FFmpeg synchronously
- Signed URLs with short expiry (30–60 minutes) for sensitive assets
- Codec detection via file extension (fast) + mime type fallback
- Batch delete orphaned MediaConvert records after media is deleted
- Use TemporaryUpload session affinity (default true) to prevent cross-session hijacking
- Test file extension rule against known malicious types (.exe, .dll, .scr)

### Anti-patterns to avoid
- Sync FFmpeg encoding on request: kills response time, locks database
- Storing raw S3 URLs in database: breaks if bucket/CDN changes
- Skipping file extension validation: media player errors or security holes
- Using service-role S3 keys for client-side uploads: use pre-signed POST instead
- Orphaned MediaConvert records: auto-prune after 30 days inactivity

## Security

### File validation
1. Whitelist file extensions (not blacklist)
2. Verify mime type (not just extension)
3. Reject files > 5GB unless explicitly allowed
4. Scan for malware signatures (roadmap: integrate ClamAV or VirusTotal)

### URL delivery
1. CloudFront signed URLs only (never raw S3 bucket URLs)
2. URL expiry enforced server-side (renewable on-demand)
3. CORS headers restricted: only trusted origins
4. No direct web access to source S3 bucket (block public-read ACL)

### Storage isolation
1. Private collections stored in bucket without public-read ACL
2. TemporaryUpload disk isolated per session (prevents cross-user access)
3. CloudFront origin access identity (OAI) required for S3 reads
4. Bucket policies restrict to known principals only

## Roadmap

### Phase 1: Adaptive bitrate (ABR)
- Generate multiple WebM/MP4 variants at 480p, 720p, 1080p, 4K
- HLS manifest generation for streaming players
- Bandwidth-aware player switches quality on network change

### Phase 2: Image optimization
- WebP/AVIF conversion for modern browsers
- Responsive image generation (srcset for 1x, 2x, 3x pixel ratios)
- EXIF data stripping before delivery
- Lossless/lossy compression tuning

### Phase 3: AI tagging and search
- Auto-extract text from images (OCR)
- Video scene detection and keyframe extraction
- Content moderation: flag NSFW or violent content
- Semantic search: find images by description, not filename

### Phase 4: Advanced antivirus and compliance
- ClamAV daemon integration for file scanning
- Quarantine infected uploads
- Audit log: who downloaded what, when
- GDPR: auto-delete after retention period
- DLP (data loss prevention): detect credit cards, SSNs in documents

### Phase 5: Streaming quality metrics
- Real-time bitrate monitoring from viewers
- Rebuffer rate tracking
- Player event logging (play, pause, seek)
- AB testing: A/B test codec pairs (VP9 vs H.265)

## Debugging and observability

### Logs
- FFmpeg command stdout/stderr in `storage/logs/media-convert.log`
- S3 operation errors logged with bucket, key, and exception
- CloudFront signed URL generation logged (expires, key_pair_id, principal)

### Database inspection
```php
// Find conversions in progress
MediaConvert::where('percentage', '<', 100)->get();

// Find failed conversions (null percentage)
MediaConvert::whereNull('percentage')->where('created_at', '<', now()->subHours(2))->get();

// Find orphaned temp uploads (older than 24h)
TemporaryUpload::where('created_at', '<', now()->subDay())->delete();
```

### Diagnostic skill (Filament command)
- Run S3 connection test: `php artisan media:test-s3`
- Test CloudFront signed URL: `php artisan media:test-cloudfront`
- Validate IAM permissions: `php artisan media:test-iam`

---

**Module owner:** Media team  
**Last updated:** 2026-09-06  
**Documented via:** Direct code analysis (Models, Actions, Datas, integrations)
