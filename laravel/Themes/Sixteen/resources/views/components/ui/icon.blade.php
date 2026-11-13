@props([
    'name' => '',
    'class' => 'h-6 w-6',
    'size' => null,
])

@php
$sizeClass = $size ? "h-{$size} w-{$size}" : '';
$classes = trim(implode(' ', [$class, $sizeClass]));
$svg = match($name) {
    'it-search' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 010 14.65Z"/>',
    'it-plus', 'it-add' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/>',
    'it-arrow-right', 'it-arrow-right-circle' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m2 0l-4-4m4 4-4 4"/>',
    'it-exclamation-triangle' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="12" y1="9" x2="12" y2="13"/><line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="12" y1="17" x2="12.01" y2="17"/>',
    'it-check-circle', 'it-check' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 11c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2s10 4.48 10 10Z"/><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>',
    'it-map-pin', 'it-pin' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M21 10.5c0 6-9 13.5-9 13.5S3 16.5 3 10.5a9 9 0 0 1 18 0Z"/><circle stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" cx="12" cy="10.5" r="3"/>',
    'it-calendar' => '<rect stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x="3" y="4" width="18" height="16" rx="2"/><line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="16" y1="2" x2="16" y2="6"/><line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="8" y1="2" x2="8" y2="6"/>',
    'it-pa' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-5 9 5v10a2 2 0 0 1-1 1.7l-9 5a2 2 0 0 1-2 0L3 16.7A2 2 0 0 1 3 14.7V9z"/>',
    'it-phone' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.49 9.91a16 16 0 0 0 6.59 6.59 2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
    'it-mail' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="22,6 12,12 2,6"/>',
    'it-twitter' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 4.01c-.37.92-.83 1.81-1.43 2.58-.77-.36-1.61-.59-2.48-.67.89-.52 1.57-1.34 1.89-2.34-.84.45-1.76.79-2.72 1.01A4.16 4.16 0 0 0 11.62 2.1c-2.27 0-4.12 1.8-4.12 4.03 0 .32.01.63.04.93C6.53 6.73 4.46 5.38 2.92 3.26a4.3 4.3 0 0 0-.56 2.05c0 1.39.69 2.62 1.74 3.35a3.97 3.97 0 0 1-1.87-.51v.05a4.13 4.13 0 0 0 3.33 4.04c-.32.1-.65.16-.98.16-.24 0-.47-.03-.7-.07.47 1.48 1.83 2.57 3.48 2.6a8.3 8.3 0 0 1-5.17 1.78c-.33 0-.67-.02-1-.08a11.5 11.5 0 0 0 6.28 1.85C11.54 21 16.64 17.98 16.64 12.5c0-.18-.01-.35-.02-.53A8.14 8.14 0 0 0 22 4.01z"/>',
    'it-facebook' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3v-4h-3V7a2 2 0 0 1 2-2h3z"/>',
    'it-youtube' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22.54 6.43c-.51-1.97-2.19-3.54-4.21-4.01A23 23 0 0 0 12 2c-3.34 0-6.45.38-9.33 1.08-2.02.47-3.7 2.04-4.21 4.01A42.3 42.3 0 0 0 0 10.22c-.03 1.28-.03 2.56 0 3.84.51 1.96 2.19 3.53 4.21 3.99A42.8 42.8 0 0 0 12 18.54a42.8 42.8 0 0 0 9.33-1.08c2.02-.46 3.7-2.03 4.21-3.99.48-1.27.74-2.55.74-3.83s-.26-2.56-.74-3.83z"/><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 15l5.5-3L10 9v6z"/>',
    'it-telegram' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 10.5c0 .33-.01.66-.02 1L11 17.5l-3.5-1.75L2 22l1-5 8-3 7.5-4.5Z"/>',
    'it-whatsapp' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12c0 2.11.85 4.03 2.17 5.5-.18 1.07-.17 1.37-.04 2.38 0 .31.11.6.37.75.24.14.67.17 1.02-.24.53-.9-.1-1.47-.48-2.58.53-1.25.86-2.62 1-3.95A9.86 9.86 0 0 0 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/>',
    'it-expand' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>',
    'it-chevron-right' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>',
    'it-close', 'it-close-big' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>',
    'it-rss' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 11a8 8 0 0 1 13.45-5.9A5.5 5.5 0 0 0 9 9a5.5 5.5 0 0 0 2 4.35 5.5 5.5 0 0 0 4.35-2A8 8 0 0 1 4 11z"/><line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="12" y1="19" x2="12.01" y2="19"/>',
    'it-burger' => '<line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="3" y1="6" x2="21" y2="6"/><line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="3" y1="12" x2="21" y2="12"/><line stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x1="3" y1="18" x2="21" y2="18"/>',
    'it-user' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" cx="12" cy="7" r="4"/>',
    'it-files' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="14 2 14 8 20 8"/><polyline stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="8,13 16,13"/><polyline stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="8,19 16,19"/>',
    'it-settings' => '<circle stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" cx="12" cy="12" r="3"/><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 0 0 .33 2.84 1.65 1.65 0 0 1-1.68 1.24 12 12 0 0 1-3.6.38 12 12 0 0 1-3.6-.38 1.65 1.65 0 0 1-1.68-1.24 1.65 1.65 0 0 0 .33-2.84 12 12 0 0 1 0-3.6 1.65 1.65 0 0 0-.33-2.84 1.65 1.65 0 0 1 1.68-1.24 12 12 0 0 1 3.6-.38 12 12 0 0 1 3.6.38 1.65 1.65 0 0 0 1.68 1.24 1.65 1.65 0 0 0-.33 2.84 12 12 0 0 1 0 3.6z"/>',
    'it-funnel' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 12H10m12 0l-4-4m4 4-4 4M2 5v14a2 2 0 0 0 2 2h12.59l3.41 3.41a1 1 0 0 0 1.66-.67V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v3z"/>',
    'it-services' => '<circle stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" cx="12" cy="12" r="10"/><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2 2"/>',
    'it-folder' => '<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>',
    'it-star' => '<polygon stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="12 2 15 11 22 11 17 17 20 26 12 21 4 26 7 17 2 11 9 9"/>',
    default => '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>',
};
@endphp

<svg class="{{ $classes }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
    {!! $svg !!}
</svg>
