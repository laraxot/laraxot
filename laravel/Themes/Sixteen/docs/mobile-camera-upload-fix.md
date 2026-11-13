# Fix — Ticket Detail UI/UX (Photo + Map Layout)

## Issue
Page /it/tickets/2 shows broken image (filename text) and missing map; photo section above meta.

## Design Requirement (Reference / Design Comuni)
- Title + meta
- Map (location from $ticket->location)
- Photos UNDER the map
- File caption visible for diagnostics

## Technical Fix (Ponytail)
### Storage
`public/storage` symlink created (`ln -sf storage/app/public public/storage`)

### Blade (`Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-dettaglio.blade.php`)
- Added `map-location` card with `<x-blocks::geo.mappa-static>` (reuse existing component)
- Moved `$images` card after `map-location` card
- Fixed image rendering: `getUrl()` (primary), `getFullUrl()` (link), `onerror` fallback, `alt` preserved
- File label preserved (`File: filename.jpg`) for diagnostics

### Config
- `media-library`: `imagick` + 50MB (HEIC support)
- `image.php`: `imagick`
- PHP limits: `upload_max_filesize=20M`

## Verification
- `grep upload_max_filesize /etc/php/8.4/fpm/php.ini` → 20M
- `php -m | grep imagick` → OK
- Storage symlink: `ls -la public/storage` → OK
- Image file exists: `storage/app/public/10/grcp...` → OK
- Page renders: map visible, photo visible under map (375/768/1440)
