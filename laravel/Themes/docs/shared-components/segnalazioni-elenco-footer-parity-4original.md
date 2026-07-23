# Parità footer segnalazioni-elenco (4original vs locale)

Riferimento visivo: `4original.png` (Design Comuni) vs `4a.png` (locale `/it/#`).

## Gap individuati (2026-06-02)

| Area | Reference | Locale (prima del fix) |
|------|-----------|------------------------|
| Stelle feedback | Grigie (#d9e1e8), oro solo hover | Tutte oro (`active` + CSS) |
| Icone contatti | SVG sprite BI visibili | Sprite **assente** in `public_html` → icona vuota |
| Telefono | `05 0505` | Placeholder `:phone` non sostituito |
| Icona telefono | `it-phone` | `it-hearing` |

## Cause

1. `sprites.svg` non copiato in `public_html` (serve `npm run copy` / `build:with-webroot` dopo fix `server.php`).
2. `vertical-navigation.blade.php` non applicava `str_replace(':phone', ...)`.
3. CSS `04-homepage-components.css` forzava `fill: #ffb400` su `.rating-star.active` (classe su tutte le label).

## Fix

- `npm run copy` + asset sprite in `public_html/themes/Sixteen/design-comuni/.../sprites.svg`
- Blade contatti: phone + `it-phone`
- `home.json`: `"phone": "05 0505"`
- Rating: rimossa classe `active` di default; stelle grigie fino a hover
- CSS allineato a `segnalazione-parity.css`

## Verifica autonoma

```bash
curl -s http://127.0.0.1:8000/it/ | grep "numero verde"
# atteso: Chiama il numero verde 05 0505 (no :phone)

curl -s -o /dev/null -w "%{http_code}\n" \
  http://127.0.0.1:8000/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg
# atteso: 200
```

Collegamenti: [segnalazioni-elenco-visual-gap-audit.md](./segnalazioni-elenco-visual-gap-audit.md)
