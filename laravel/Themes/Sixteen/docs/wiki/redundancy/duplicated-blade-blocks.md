---
title: "Duplicated Blade Blocks Across Modules and Themes"
type: redundancy
owner: Themes/Sixteen
severity: medium-high
created: 2026-05-21
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
updated: 2026-05-25
related:
  - ../../../../../Modules/Xot/docs/wiki/redundancy/byte-identical-files-static-scan.md
  - ../../../../../Modules/Xot/docs/wiki/redundancy/audit-profondo-ridondanze-holistic.md
tags: [redundancy, blade]
<<<<<<< HEAD
=======
>>>>>>> a931b1c (.)
>>>>>>> laraxot/dev
---

# Duplicated Blade Blocks (Content-Identical Components)

## Problem
Multiple Blade components under `resources/views/components/blocks/` are byte-for-byte identical (or extremely similar) across different modules and between modules and themes.

Examples of content-hash collisions:
- `with_fading_background_image.blade.php`
- `split_with_image.blade.php`
- `volume.blade.php`
- `simple_on_brand.blade.php`
- `ratings.blade.php`
- `list_of_markets.blade.php`
- `filter_list.blade.php`
- etc.

## Impact
- Same UI component maintained in 3–5 different places
- Risk of divergence
- Violates the "one canonical block" principle that the Design Comuni work was supposed to solve

## Recommended Fix
1. Move all truly shared blocks into a central location (e.g. `Modules/UI/resources/views/components/blocks/` or a dedicated `Blocks` package).
2. Make Themes and other Modules consume them via proper view namespaces or published stubs.
3. Remove the duplicated copies.

## Related
<<<<<<< HEAD

- Epic [#90](https://github.com/laraxot/base_fixcity_fila5/issues/90) (main redundancy tracker)
- Previous static scan already flagged many of these files
- Inventario tecnico trasversale: [`audit-profondo-ridondanze-holistic.md`](../../../../../Modules/Xot/docs/wiki/redundancy/audit-profondo-ridondanze-holistic.md)
- Scan byte-identical aggiornato: [`byte-identical-files-static-scan.md`](../../../../../Modules/Xot/docs/wiki/redundancy/byte-identical-files-static-scan.md)
=======
<<<<<<< HEAD
- Issue #90 (main redundancy tracker)
- Previous static scan already flagged many of these files
=======

- Epic [#90](https://github.com/laraxot/base_fixcity_fila5/issues/90)
- Inventario tecnico trasversale: [`audit-profondo-ridondanze-holistic.md`](../../../../../Modules/Xot/docs/wiki/redundancy/audit-profondo-ridondanze-holistic.md)
- Scan byte-identical aggiornato: [`byte-identical-files-static-scan.md`](../../../../../Modules/Xot/docs/wiki/redundancy/byte-identical-files-static-scan.md)
>>>>>>> a931b1c (.)
>>>>>>> laraxot/dev
