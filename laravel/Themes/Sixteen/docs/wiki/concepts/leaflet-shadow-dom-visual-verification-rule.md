# Leaflet Shadow DOM Visual Verification Rule

## Regola Tema

Quando Sixteen renderizza un web component Geo con Leaflet, la visual parity va controllata sul risultato browser:

- screenshot desktop/mobile;
- colore/overlay;
- tile mappa completi;
- assenza di scrollbar fullscreen;
- controlli visibili sopra la mappa.

## False friend

Un componente alto e visibile non garantisce che la mappa sia completa. Nel caso Shadow DOM, il CSS Leaflet globale puo' non raggiungere i tile.

## Checklist

- `coordinate-picker-lit.shadowRoot` ha contenuto.
- `.leaflet-container` ha width/height non zero.
- i tile non lasciano quadrati vuoti persistenti.
- il fullscreen non mostra contenuti wizard sopra la mappa.
- il controllo visuale usa Playwright o browser automation, non solo `curl`.

