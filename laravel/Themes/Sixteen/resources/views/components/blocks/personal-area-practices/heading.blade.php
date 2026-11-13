{{-- Alias verso il chrome condiviso: vedi personal-area/heading.blade.php (DRY area-personale). --}}
@include('pub_theme::components.blocks.personal-area.heading', [
    'active' => $active,
    'title' => $title,
    'subtitle' => $subtitle,
    'cta' => $cta ?? null,
])
