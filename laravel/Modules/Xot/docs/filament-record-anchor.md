# Tornare all'elenco sulla riga giusta

## Il problema

Si apre un record dall'elenco, lo si modifica, si torna alla lista dal breadcrumb: la
lista riparte dall'inizio e la riga appena toccata va ricercata a mano. Su elenchi lunghi
e' la perdita di tempo piu' frequente segnalata dagli utenti.

## Le due meta' della soluzione

1. **Pagina giusta.** Filtri, ordinamento, ricerche e numero di pagina sono conservati in
   sessione dalle classi base, quindi la riga e' gia' dentro la pagina servita. Vedi
   [filament-table-session-state.md](./filament-table-session-state.md).
2. **Posizione nella pagina.** Un frammento nell'URL, `#record-{chiave}`, porta il browser
   sulla riga.

Serve che entrambe siano vere: il frammento e' inerte se la riga non e' nella pagina
caricata, perche' il frammento non viene inviato al server.

## Dove sta l'ancora

Filament non emette alcun `id` o `data-*` per riga: sul `<tr>` c'e' solo
`wire:key="{idLivewire}.table.records.{chiave}"`, e la parte `{idLivewire}` cambia a ogni
caricamento pagina, quindi non e' un bersaglio stabile per un frammento.

L'ancora viene quindi emessa dalla cella dell'id, da
`Modules\UI\Filament\Tables\Columns\IDColumn`:

```html
<span id="record-1875" class="scroll-mt-24">1875</span>
```

`Modules\Xot\Filament\Support\RecordAnchor` tiene il formato in un posto solo
(`RecordAnchor::id()`, `fragment()`, `appendTo()`), cosi' colonna e link di ritorno non
possono divergere.

Due dettagli che sembrano estetici e non lo sono:

- il prefisso `record-`: un id che inizia per cifra non e' selezionabile con
  `querySelector('#123')` ne' con un selettore CSS;
- il frammento va in coda all'URL, dopo la query string. `.../schedas#record-1875?page=3`
  e' sbagliato: `?page=3` finirebbe dentro al frammento e la pagina non verrebbe letta.
  La forma corretta e' `.../schedas?page=3#record-1875`, e con la pagina gia' conservata
  in sessione il `?page=` diventa superfluo.

## Chi appende il frammento

`XotBaseEditRecord::getResourceBreadcrumbs()` riscrive la sola voce che punta all'elenco
aggiungendo l'ancora del record aperto. Vale per ogni risorsa che usa la base, senza
righe da aggiungere nei moduli.

La voce di menu laterale resta senza ancora: non conosce alcun record. Grazie alla pagina
conservata in sessione riporta comunque alla porzione di elenco giusta.

## Limite noto e alternative

L'ancora esiste solo negli elenchi che mostrano `IDColumn`. Se serve il salto alla riga
su tutte le liste, o un evidenziamento della riga, le strade disponibili in Filament 5
sono due, entrambe piu' invasive:

- `Table::recordClasses()` per marcare la riga a partire da un parametro in query string,
  piu' uno `<script>` iniettato con il render hook
  `PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER` che fa `scrollIntoView`;
- selettore JS sul `wire:key` esistente, con match per suffisso
  (`[wire\:key$=".table.records.1875"]`), sempre via render hook.

Entrambe introducono JavaScript in una parte oggi senza JavaScript: da fare solo se la
richiesta diventa generale.

## Riferimenti

- `Modules/Xot/app/Filament/Support/RecordAnchor.php`
- `Modules/Xot/app/Filament/Resources/Pages/XotBaseEditRecord.php`
- `Modules/UI/app/Filament/Tables/Columns/IDColumn.php`
