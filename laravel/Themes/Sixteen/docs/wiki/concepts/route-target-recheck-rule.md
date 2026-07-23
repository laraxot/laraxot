# Route Target Recheck Rule

## Regola

Nel tema Sixteen una correzione visuale o runtime non e' conclusa finche' non viene ricontrollata la route target completa nel contesto reale del tema, con il block/page owner effettivo e con gli asset buildati/copied.

## Perche'

Nel flusso `tests/segnalazione-crea` i problemi emersi di recente dipendevano dal runtime completo:

- CMS block -> widget Livewire -> asset Geo -> CSS tema
- step wizard nascosti che influenzano Leaflet
- regressioni visive visibili solo sulla page route finale

Verificare solo il componente isolato o la pagina base produce falsi positivi.

## Best Practices

- ricontrollare la route target dopo `npm run build` e `npm run copy`
- verificare il tema nel runtime reale, non solo i file sorgente
- fare almeno un controllo visuale o DOM/runtime del componente critico
- se il bug coinvolge step wizard, simulare la navigazione reale
- se il bug e' sul wizard segnalazione, aprire direttamente la URL con query step target:
  - `http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.dati-della-segnalazione::data::wizard-step`
- usare docs del tema per registrare il contratto visivo risultante

## Bad Practices

- fermarsi al rebuild asset senza reload della route
- controllare una route Folio o una view locale diversa dalla route CMS reale
- assumere che un fix CSS globale sia corretto senza vedere il risultato sul tema
- chiudere un fix mappa/header senza vedere il comportamento nell'header/layout veri

## False Friends

- il build Vite riuscito non garantisce parity visuale
- il componente corretto in isolamento puo' rompersi dentro il layout reale
- la pagina base puo' funzionare mentre lo step wizard target fallisce
- vedere il custom element nel DOM non significa che il tema lo stia presentando bene
- vedere la mappa nello step 1 non valida il comportamento nello step `data::wizard-step`

## Contratto operativo

Per issue visuali o runtime su Sixteen:

1. fix nel punto owner corretto
2. rebuild/copy asset se necessario
3. recheck della route target completa (inclusa query step quando presente)
4. verifica visuale esplicita su mappa/header (no solo compile-time checks)
5. aggiornamento docs wiki locale con la regola emersa
