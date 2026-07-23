# Confronto struttura HTML — `segnalazione-area-personale`

**Aggiornato**: 2026-04-20  
**Riferimento**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html  
**Locale**: http://127.0.0.1:8000/it/tests/segnalazione-area-personale  

Comando:

```bash
bash bashscripts/html/compare-html.sh \
  "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html" \
  "http://127.0.0.1:8000/it/tests/segnalazione-area-personale" \
  "laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-area-personale"
```

## Esito (similitudine strutturale `compare-html.sh`)

| Metrica | 2026-04-10 (baseline) | 2026-04-20 (dopo story 7-3 incrementale) |
|--------|------------------------|------------------------------------------|
| Similitudine | 43.1% | **48.0%** |
| Righe struttura URL1 | 886 | 1751 |
| Righe struttura URL2 | 891 | 1813 |

> Nota: il numero di righe estratte è cambiato tra le esecuzioni (versione pagina remota / estrattore); confrontare prioritariamente la **percentuale** e il diff aggiornato.

## Cosa è stato migliorato in questa iterazione

- **Header runtime (`header/v1.blade.php`)**: per il path `tests/segnalazione-area-personale`, in assenza di sessione viene reso il blocco `it-user-wrapper` + menu come nel kit statico (prima era solo il pulsante «Area personale» → login), avvicinando la struttura all’URL1 nel diff header.
- **Blocco contenuto**: classi colonna indice allineate al riferimento (`col-12 col-lg-3 d-lg-block mb-4 d-none`).
- **Traduzioni tema**: `pub_theme::ui.header_area_personale.parity_demo_user` (nome demo per etichetta slim).

## Gap principali verso l’obiettivo ≥90%

- **Lingua (slim header)**: locale usa markup Alpine (`x-data`, `@click`, `x-show`) e classi utility Tailwind sul menu lingua; il riferimento usa struttura Bootstrap/Dropdown diversa (più righe combacianti).
- **Blocco `bg-grey-card`**: presente nella scansione blocchi URL1, non identificato allo stesso modo in URL2 (conteggio blocchi 4 vs 3).
- **ID «agnostici»** (`calendario`, `head-section`, `rating`): assenti **in entrambe** le pagine nel set `KEY_IDS` dello script; non sono regressione locale ma allineamento al motore di confronto.

## Prossimi passi suggeriti (fuori scope di questo incremento)

- Allineare il dropdown **lingua** dello slim header al markup statico (o accettare delta documentato se la policy resta Alpine-only).
- Verificare presenza sezione/card `bg-grey-card` nel body locale rispetto al riferimento.
- Ripetere `compare-html.sh` dopo modifiche e aggiornare questo file.

## Output raw

I file timestampati (`report_*.md`, `diff_*.txt`, `page*.html`) generati nello stesso percorso sono allegati opzionali dell’ultima esecuzione.

---

*Script: [`bashscripts/html/compare-html.sh`](../../../../../../bashscripts/html/compare-html.sh)*
