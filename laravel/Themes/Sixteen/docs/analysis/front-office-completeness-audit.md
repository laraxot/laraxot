# Audit di completezza del front office Sixteen

## Contratto verificato

Il front office usa Folio come punto di ingresso del tema:

`resources/views/pages/[container0]/[slug0]/index.blade.php`

Il dettaglio Blog, Eventi e Ticket deve quindi essere verificato come flusso HTTP reale, non soltanto come vista isolata. I dati pubblici arrivano dalle Action dei moduli; il tema compone esclusivamente layout, contenuto e widget.

## Correzioni applicate

- Il thread Comment viene montato anche per gli ospiti sui ticket pubblici, in sola lettura.
- La lettura del commentabile usa `view`; la scrittura continua a usare `create`/`update` nei singoli widget.
- Il widget Comment espone la risposta al singolo commento e monta `MarkdownEditor` solo dopo «Rispondi».
- Le risposte annidate mantengono il limite server-side `comments.ui.max_reply_depth`.

## Gap rilevati da completare

1. Eseguire browser QA autenticato e guest a 375, 768 e 1440 px per editor Markdown, focus, errori e tastiera.
2. Verificare con richieste HTTP la precedenza fra shell Folio del tema e viste legacy dei moduli.
3. Sostituire i placeholder `href="#"` e gli URL hardcoded dei blocchi Blog/Ticket con `FrontofficeRoutes` localizzate.
4. Coprire con test il contratto `view` separato da `create`, inclusi ticket pubblici, ticket privati, proprietario e moderatore.
5. Rendere il renderer dei blocchi editoriali completo e sanitizzato: il dettaglio non deve scartare heading, liste, citazioni, link o immagini.

## Regola di verifica

Ogni nuova pagina o flusso deve avere almeno un test guest e uno autenticato, oltre a una prova della route Folio completa. I test di classe o di composizione non dimostrano che la pagina pubblica venga realmente raggiunta.
