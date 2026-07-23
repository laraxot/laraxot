### elenco segnalazioni

Obiettivo: convertire il design Bootstrap Italia in classi Tailwind CSS per ottenere la resa visiva come nel modello Designers Italia "Elenco segnalazioni".

Scelte principali:
- **Conversione completa a Tailwind**: Sostituite tutte le classi Bootstrap Italia con equivalenti Tailwind
- **Configurazione personalizzata**: Creato `tailwind.config.js` con colori e dimensioni specifiche Bootstrap Italia
- **Layout responsive**: Utilizzato Flexbox e Grid di Tailwind per layout a due colonne
- **Componenti stilizzati**: Header verde, breadcrumb, tab, mappa con pin, card grigie, footer

Implementazione:
- **Header**: `bg-bootstrap-primary text-white` per sfondo verde
- **Layout**: `flex flex-col lg:flex-row gap-6` per sidebar + contenuto
- **Tab**: `border-b-2 border-bootstrap-primary` per tab attiva
- **Mappa**: Classi custom `.map-box` e `.pin` con posizionamento assoluto
- **Card**: `bg-bootstrap-bg-grey rounded-lg shadow-sm` per sfondo grigio
- **Footer**: `bg-gray-900 text-white` con grid responsive

Impatto:
- **Zero dipendenze esterne**: Solo Tailwind CSS (già configurato)
- **Performance migliorata**: CSS ottimizzato e purged
- **Manutenibilità**: Classi utility facilmente modificabili

Riferimenti:
- Repository di riferimento `italia/design-comuni-pagine-statiche` per struttura e stile dei componenti [`https://github.com/italia/design-comuni-pagine-statiche`](https://github.com/italia/design-comuni-pagine-statiche).
- Documentazione Tailwind CSS per classi utility e configurazione personalizzata.

Note:
- Configurazione Tailwind in `tailwind.config.js` con colori Bootstrap Italia
- Classi custom minime solo per componenti complessi (mappa, rating)


