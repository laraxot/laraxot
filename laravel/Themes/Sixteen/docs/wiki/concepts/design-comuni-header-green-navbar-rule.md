# Design Comuni Header Green Navbar Rule

## Regola

Nel tema Sixteen, quando il layout comunale usa il branding verde, le voci principali dell'header (`Amministrazione`, `Novita`, `Servizi`, `Vivere il Comune`, `Iscrizioni`, `Estate in citta`, `Polizia locale`, `Tutti gli argomenti`) devono stare sullo stesso fondo verde del logo e dello slogan, non su fondo bianco.

## Best practices

- Governare il colore nel CSS del tema Sixteen, usando selettori di header/nav riusabili.
- Mantenere il file owner dell'header: `resources/views/components/sections/header/v1.blade.php`.
- Usare token/variabili tema gia' presenti quando disponibili (`--dc-green`, `--bs-primary` o equivalenti), evitando valori sparsi.
- Verificare desktop e mobile: dropdown e stato hover/focus devono restare leggibili.

## Bad practices

- Aggiungere classi o stili inline alle singole voci menu.
- Fare CSS per singola pagina o per singolo slug.
- Cambiare il markup owner dell'header senza capire se il runtime usa `<x-section slug="header" />`.

## False friends

- La navbar bianca puo' sembrare corretta guardando una pagina reference diversa, ma nel flusso verde comunale deve ereditare il layer branding.
- Cambiare solo `.it-header-slim-wrapper` non cambia necessariamente la nav principale: slim bar e nav center/nav navbar sono layer distinti.
- Un fix che funziona per desktop puo' lasciare menu mobile/dropdown con contrasto insufficiente.
- Bootstrap Italia porta spesso default blu su `.it-header-navbar-wrapper`, `.navbar`, `.navbar-collapsable` e link correlati: se il fix verde viene scritto prima degli import finali o con selettore meno specifico, il blu ritorna.
- Guardare solo il markup Blade puo' ingannare: il colore finale dipende dall'ordine degli import CSS del tema e dal bundle Vite copiato in `public_html/themes/Sixteen`.

## Root cause ricorsiva

L'errore si ripete quando si corregge "l'header" in un punto solo, senza seguire la cascade completa:

1. il markup owner e' `components/sections/header/v1.blade.php`;
2. Bootstrap Italia e gli override Design Comuni entrano da piu' import in `resources/css/app.css`;
3. alcune regole finali ripristinano blu/bianco sui wrapper navbar;
4. se non si fa `npm run build` e `npm run copy`, il browser continua a leggere asset vecchi;
5. senza screenshot/computed style, si conferma il markup ma non il colore realmente renderizzato.

La correzione deve quindi stare nel CSS finale del tema, usare selettori header riusabili e verificare il computed background delle voci menu.
