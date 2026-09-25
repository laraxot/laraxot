# Brainstorming — componente Blade riutilizzabile per tabella rating

## Contesto
Il blocco Blade per la visualizzazione delle righe di `ratings` (testo, valore, nota con rowspan) è duplicato in più punti (viste schede, esportazioni XLS, viste PDF). Si vuole estrarlo in un componente Blade riutilizzabile.

## Domande chiave
1. **Quale nome per il componente?** `rating-table` (coerente con il naming esistente del modulo Rating, usa `x-rating-table`).
2. **Quali props?** `row`, `showNote`, `rowspan`, `alignValue`. Le props sono sufficienti per coprire tutti i casi d'uso attuali?
3. **Dove collocarlo?** `resources/views/components/rating-table.blade.php` (standard Laravel Blade component, nessun namespace custom richiesto).
4. **Dipendenze?** Solo il modello `BaseRating` (metodi `getTxtHtml`, `getValueHtml`, `getNoteHtml`). Nessuna dipendenza esterna.
5. **Come gestire la nota aggregata?** Accumulo nel loop con `@php $note .= ...` e `rowspan` sull'ultimo iterazione, esattamente come nel blocco originale.
6. **Test?** PHPStan verifica i tipi delle props (`$row` come oggetto con relazione `ratings`). Nessun test unitario dedicato richiesto (componente puramente presentazionale), ma l'integrazione deve essere verificata tramite test Pest nei punti d'uso.
7. **Sicurezza?** Uso di `{!! !!}` per `getTxtHtml()` e `getValueHtml()` perché i metodi restituiscono già HTML-safe. `getNoteHtml()` restituisce testo semplice nel contesto attuale.

## Decisione
- Procedere con il componente Blade `rating-table`.
- Non introdurre un componente Filament (non è richiesto per l'uso nelle viste Blade generiche).
- Non creare una classe PHP separata; il componente Blade è sufficiente per il livello di astrazione richiesto.

## Riferimenti
- Story BMAD: `rating-table-component-reusable.story.md`
- Architecture spec: `rating-table-component.md`
- Codice esistente: `BaseRating.php` (metodi `getTxtHtml`, `getValueHtml`, `getNoteHtml`)
