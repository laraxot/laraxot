# context compression plugin runtime

## contesto tema

Sixteen contiene molti report Design Comuni, screenshot, HTML raw e diff. Questi file sono utili come raw corpus, ma non devono essere caricati in blocco in una sessione agent.

## regola Sixteen

- Consultare prima `laravel/Themes/Sixteen/docs/wiki/index.md`.
- Usare query mirate su step/pagina (`segnalazione-03-riepilogo`, `header`, `body parity`).
- Riassumere decisioni persistenti in `docs/wiki/` del tema.
- Evitare dump completi di HTML debug, screenshot metadata o batch parity se basta una riga di diagnosi.

## plugin

Il `context-compression plugin` citato dagli errori API e una feature OpenRouter per richiesta (`plugins: [{ "id": "context-compression" }]`). Nel tema non si installa nulla: si documenta la regola e si mantiene il corpus interrogabile via LLM Wiki/QMD.

