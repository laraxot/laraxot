# Segnalazioni HTML Parity Summary

Batch run eseguito con `bashscripts/html/html-structure-compare.sh` contro le reference Design Comuni e le route locali `/it/tests/*`.

## Risultati

| Pagina | Parity | Stato |
|---|---:|---|
| `segnalazione-01-privacy` | 93.52% | PASS |
| `segnalazione-03-riepilogo` | 92.75% | PASS |
| `segnalazione-02-dati` | 90.52% | PASS |
| `segnalazione-area-personale` | 88.43% | FAIL |
| `segnalazione-04-conferma` | 77.31% | FAIL |
| `segnalazione-dettaglio` | 75.18% | FAIL |
| `ticket-list` | 53.87% | FAIL |

## Priorita operative

1. `ticket-list`
   Gap ampio su tag, id e classi. E' la pagina piu distante dalla reference.
2. `segnalazione-dettaglio`
   Tag/id/classi quasi allineati, ma restano molti elementi mancanti/extra: va chiusa la struttura centrale e laterale.
3. `segnalazione-04-conferma`
   Il problema piu evidente e sugli id e sulla composizione dei blocchi finali.
4. `segnalazione-area-personale`
   E' molto vicina al target: pagina piu facile da portare oltre il 90% con interventi mirati.

## Report per pagina

- `prompts/segnalazione-area-personale/body-structure-comparison/`
- `prompts/ticket-list/body-structure-comparison/`
- `prompts/segnalazione-dettaglio/body-structure-comparison/`
- `prompts/segnalazione-01-privacy/body-structure-comparison/`
- `prompts/segnalazione-03-riepilogo/body-structure-comparison/`
- `prompts/segnalazione-04-conferma/body-structure-comparison/`
- `prompts/segnalazione-02-dati/body-structure-comparison/`

## Note

- La route resta `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`.
- Nessuna blade dedicata per singola pagina va introdotta.
- Le classi Bootstrap vanno tenute nel markup per la parity HTML; Bootstrap CSS/JS non va caricato.
- Le traduzioni devono restare nel formato `fixcity::contesto.collezione.chiave.tipo`.
