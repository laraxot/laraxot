---
id: fix-missing-base-model-pivot-1788771322
slug: platform-basemodel-corruption-fix
scope: [module:Platform, project:base_workorder_fila5]
status: Done
priority: Critical
created: 2026-09-07
---

## Problema

Un tentativo automatizzato (script shell con quoting errato) di generare
`app/Models/BaseModel.php` per i moduli privi di un intermedio proprio ha
prodotto PHP invalido: testo letterale di shell script (`$(echo $m | sed
...)`) incorporato nella dichiarazione `namespace`, oppure (su `Billing`)
`abstract class BaseModel extends BaseModel` auto-referenziale. Errore fatale
di parsing/reflection, non un semplice lint: nessun modello del modulo
poteva caricare. Rilevato su 11 moduli in un'unica sessione (stesso
timestamp di scrittura, run singolo, non piu' attivo al momento della
scoperta).

## Fix

Ricreato `Modules/Platform/app/Models/BaseModel.php` con contenuto corretto:

```php
abstract class BaseModel extends XotBaseModel
{
    protected $connection = 'platform';
}
```

Per i moduli con versione precedente valida in git (Catalog, Cms, Comment),
il contenuto e' stato recuperato con `git show HEAD:<path>` (studio, non
checkout) e riapplicato manualmente. Per i moduli senza BaseModel.php
precedente (incluso questo), ricostruito seguendo il pattern degli altri
27 moduli (`protected $connection = '<nome_modulo_snake>';`).

## Verifica

`./vendor/bin/phpstan analyse Modules/Platform --memory-limit=-1` (cache pulita) →
0 errori.

## Coordinamento

Dettaglio completo dell'incidente (tutti gli 11 moduli, causa radice,
avviso per il proprietario dello script):
`docs/chat/2026-09-07-URGENT-broken-shell-template-corrupted-10-basemodel-files.md`.
Second-brain: `feedback_shell_template_unquoted_variable_corruption.md`.
