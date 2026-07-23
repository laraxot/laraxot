# Homepage Governance

## Obiettivo

Ridurre il caos operativo della homepage `predict.local/it` con poche regole stabili, facili da verificare e facili da mantenere.

## Regole

### 1. Una sola homepage canonica

La configurazione CMS deve avere un solo record con `slug: home`.

- corretto: `1.json` con `slug: home`
- errato: piu' file JSON con `slug: home`

I file alternativi o sperimentali devono usare slug diversi, ad esempio:

- `home-kalshi-legacy`
- `home-empty-legacy`

### 2. La navbar deve usare il linguaggio del dominio

Per il tema `TwentyOne`, la voce italiana `markets` deve essere tradotta come `Mercati`, non come `Categorie`.

Motivo:

- `Mercati` descrive il prodotto;
- `Categorie` descrive solo una tassonomia;
- la homepage deve parlare la lingua del dominio prima di quella tecnica.

### 3. I lookup CMS per slug devono essere univoci

Quando il dominio si aspetta una sola pagina per slug, il codice deve usare `sole()` e non `first()`.

Motivo:

- `first()` nasconde duplicati;
- `sole()` rende esplicita la violazione del contratto;
- il problema emerge subito in sviluppo invece di diventare comportamento casuale.

### 4. Il footer non deve dipendere dall'ordine dei blocchi

Il footer deve cercare i blocchi per `type` o per semantica del blocco, non per posizione numerica nell'array.

## Stato attuale

- copertura regola homepage canonica: `100%`
- copertura regola label `Mercati`: `100%`
- copertura regola lookup univoco slug CMS: `100%` sui punti corretti toccati
- copertura regola footer order-independent: `100%`

## Verifica

La governance e' protetta anche da test in `Modules/Cms/tests/Feature/HomepageConfigurationGovernanceTest.php`.
