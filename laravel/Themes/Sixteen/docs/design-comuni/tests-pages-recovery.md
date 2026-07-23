# Recovery pagine `tests.*` design-comuni

## Contesto

Nel catalogo `laravel/config/local/fixcity/database/content/pages/` erano presenti molte pagine `tests.*.json` vuote o non valide. Il problema non era la route Folio, ma il contenuto CMS mancante o corrotto.

## Criterio usato

1. Confronto tra pagine sorgente `sito/*.hbs` del repository `design-comuni-pagine-statiche`.
2. Confronto con i file `tests.*.json` locali.
3. Individuazione dei file con uno di questi stati:
   - JSON invalido
   - `content_blocks.it` assente
   - `content_blocks.it` con 0 o 1 blocco
4. Rigenerazione dei file difettosi con struttura minima coerente con `x-page`:
   - `navigation`
   - `hero`
   - `details`
   - `list`

## Script usato

```text
bashscripts/theme/fill_missing_tests_pages.php
```

Lo script rigenera in batch i file `tests.*.json` vuoti o invalidi senza toccare quelli già popolati con più blocchi.

## Esito

- File rigenerati: `54`
- Verifica finale: tutti i `tests.*.json` risultano validi
- Verifica finale: nessun file `tests.*.json` rimane con 0 o 1 blocco

## Limiti attuali

Questo recovery non equivale ancora alla replica pixel-perfect delle pagine ufficiali. Serve come base CMS coerente per:

- evitare errori runtime dovuti a JSON corrotti
- garantire uno slug `tests.<slug>` valido
- avere una struttura a blocchi estendibile
- proseguire con la vera conversione per parità HTML/visiva

## Prossimo passo corretto

Per ogni pagina prioritaria bisogna sostituire i blocchi generici con blocchi semantici e riutilizzabili derivati dall'HTML sorgente, evitando blocchi specifici per pagina.

## Riferimenti

- `laravel/Themes/Sixteen/Main_files/design-comuni-pagine-statiche/src/pages/sito`
- `laravel/Themes/Sixteen/Main_files/design-comuni-html/dist/servizi`
- `laravel/config/local/fixcity/database/content/pages`
