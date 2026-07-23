# Header Navbar Verde: Regola di Componente

## Regola

La navbar principale dell'header Sixteen deve usare lo stesso verde della fascia logo/slogan quando richiesto dalla variante Design Comuni del progetto. La regola e' di componente, non di pagina.

## Causa dell'errore ricorsivo

Bootstrap Italia applica colori di default su piu' livelli:

- `.it-header-navbar-wrapper`;
- `.it-header-navbar-wrapper .container`;
- `.row`, `.col-12`;
- `.navbar`;
- `.navbar-nav .nav-link` e stati hover/active;
- varianti responsive dentro media query mobile.

Cambiare solo un livello lascia altri override attivi e la navbar torna blu o bianca dopo build/copy, cambio viewport o cascade successiva.

## Best Practices

- Correggere tutto il blocco di componente in `resources/css/app.css`.
- Coprire wrapper, container, row, col, navbar, link, hover, active e icone toggler.
- Se una media query mobile ridefinisce background, deve usare lo stesso token del componente.
- Dopo modifica CSS tema eseguire `npm run build` e `npm run copy` dalla cartella del tema.

## Bad Practices

- Aggiungere selettori per pagina come `[data-slug="tests.segnalazione-crea"]`.
- Aggiungere varianti per wizard come `.ticket-wizard-root`.
- Cambiare solo `.nav-link`: il background reale puo' stare sui wrapper.
- Affidarsi a classi condizionali locali per una regola che vale per l'header del sito.

## False Friends

- Se il link e' bianco ma lo sfondo resta blu, non e' un problema del link: e' il wrapper Bootstrap Italia.
- Se desktop sembra corretto e mobile no, controllare le media query finali: possono riaprire `background: white` o `background: transparent`.
