---
title: IDE Helper e model nei temi
type: reference
updated: 2026-08-31
related:
  - ../../Modules/Xot/docs/ide-helper-models-governance.md
  - ./module-vs-theme-boundaries.md
---

# IDE Helper e model nei temi

## Boundary

I model appartengono ai moduli, non ai temi. IDE Helper non giustifica duplicazioni di model o PHPDoc nei temi: la correzione va applicata al modulo owner.

## Il doppio ramo `app/` e `src/` di Sixteen — risolto il 2026-08-31

Fino al 2026-08-31 `Themes/Sixteen` conteneva due alberi paralleli, `app/` e `src/`,
con lo **stesso** namespace `Themes\Sixteen\`: 26 file in `src/`, di cui 24 classi PHP
duplicate rispetto ad `app/` e divergenti nel contenuto.

Il meccanismo non era l'autoload di `composer.json` del tema (che mappa solo `app/`),
ma la registrazione runtime in
`Modules/Xot/app/Actions/Composer/RegisterRuntimePsr4NamespacesAction.php`, che
associava il prefisso a **entrambe** le directory:

```php
'Themes\\Sixteen\\' => [
    $base.'/Themes/Sixteen/app',
    $base.'/Themes/Sixteen/src',
],
```

`ClassLoader::addPsr4()` risolve nell'ordine di registrazione, quindi `app/` vinceva
sempre. Verificato per riflessione su tutte e 24 le classi: nessuna veniva caricata da
`src/`. Il ramo era quindi irraggiungibile ma non inerte — modificarlo dava
l'illusione di un effetto, e un cambio d'ordine avrebbe silenziosamente scambiato
l'implementazione attiva.

Prima della rimozione sono stati recuperati i due bug che il ramo `app/` aveva e
`src/` no, entrambi fatali a runtime:

- `Municipal/PublicPerson::daysInOffice()` — closure `Attribute` tipizzata `void` che
  restituiva un valore;
- `Municipal/PublicDocument::formattedFileSize()` — stesso difetto.

Corretti in `app/`, poi `src/` e la relativa riga di registrazione sono stati rimossi.
Restano fuori dal repository anche `src/components/my-map.{js,ts}`, non referenziati da
alcun sorgente, config o build.

I model di dominio comunale restano comunque nel posto sbagliato: appartengono a un
modulo, non a un tema. Quella migrazione e' un intervento architetturale separato.

## Workflow sicuro

Dalla root del repository:

```bash
cd laravel
php artisan ide-helper:models --nowrite --no-interaction -v
./vendor/bin/phpstan analyse Modules
```

Sono vietati `--write`, `-W`, `--write --reset` e `--write-mixin`: con schema incompleto possono degradare i PHPDoc dei model applicativi.

Dopo il comando ispezionare il diff. Se compaiono modifiche inattese, correggerle in avanti; la policy Git vieta `checkout`, `restore`, `reset`, `revert` e rollback. I dati restano sacri: nessuna migrazione distruttiva o forzata è ammessa per soddisfare IDE Helper.

Owner canonico: [Modules/Xot/docs/ide-helper-models-governance.md](../../Modules/Xot/docs/ide-helper-models-governance.md).
