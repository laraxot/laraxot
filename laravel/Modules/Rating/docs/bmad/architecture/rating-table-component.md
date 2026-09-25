# Componente Blade riutilizzabile per tabella rating

## Scopo
Fornire un componente Blade `<x-rating-table>` riutilizzabile nel modulo Rating per visualizzare le righe di rating associate a un modello host (tramite relazione `ratings`), con supporto per:
- Visualizzazione del testo formattato (`getTxtHtml()`)
- Valore in grassetto (`getValueHtml()`)
- Nota aggregata con `rowspan` sull'ultima riga (`getNoteHtml()`)

## Architettura
Il componente risiede in `resources/views/components/rating-table.blade.php` e segue le convenzioni Laravel Blade components.

### Input (props)
| Prop | Tipo | Default | Descrizione |
|------|------|---------|-------------|
| `$row` | `Illuminate\Database\Eloquent\Model` (deve avere relazione `ratings`) | **obbligatorio** | Istanza del modello host (es. `Scheda`, `IndennitaResponsabilita`) |
| `$showNote` | `boolean` | `true` | Mostra la quarta colonna con la nota aggregata |
| `$rowspan` | `boolean` | `true` | Abilita il `rowspan` sulla nota per l'ultima iterazione |
| `$alignValue` | `string` (`'left'\|'center'\|'right'`) | `'right'` | Allineamento orizzontale della colonna valore |

### Template Blade
```blade
{{-- Componente blade riutilizzabile per tabella rating. --}}
{{-- Usage: <x-rating-table :row="$row" /> --}}
{{-- Opzioni: --}}
{{--   $showNote  => true,  --}}
{{--   $rowspan    => true, --}}
{{--   $alignValue => 'right' --}}

@php
    $showNote = $showNote ?? true;
    $rowspan  = $rowspan ?? true;
    $alignValue = $alignValue ?? 'right';
    $note = '';
@endphp

<table class="rating-table">
    <colgroup>
        <col style="width: 55%">
        <col style="width: 15%">
        <col style="width: 15%">
        @if($showNote)
            <col style="width: 15%">
        @endif
    </colgroup>
    @foreach($row->ratings as $rating)
        <tr>
            <td>{!! $rating->getTxtHtml() !!}</td>
            <td align="{{ $alignValue }}">
                <b>{!! $rating->getValueHtml() !!}</b>
            </td>
            @php
                $note .= $rating->getNoteHtml() ?? '';
            @endphp
            @if($showNote && $loop->last)
                <td align="{{ $alignValue }}" rowspan="{{ $loop->count }}" style="vertical-align: middle; text-align: center;">
                    <span>{!! $note !!}</span>
                </td>
            @endif
        </tr>
    @endforeach
</table>
```

### Assunzioni
- Il modello passato come `$row` deve avere una relazione pubblica `ratings()` che restituisce un `Illuminate\Database\Eloquent\Relations\MorphToMany` o `BelongsToMany` verso `Modules\Rating\Models\BaseRating`.
- I metodi `getTxtHtml()`, `getValueHtml()`, `getNoteHtml()` esistono su `BaseRating` (o sulle sue sottoclassi STI) e restituiscono stringhe sicure per output HTML grezzo (`{!! !!}`).

### Dipendenze
- Nessuna dipendenza esterna oltre a Laravel Blade e al modello Rating.
- Il componente non esegue query aggiuntive; si aspetta che la collezione `$row->ratings` sia già caricata (eager loaded) per evitare N+1.

### Testing
- Unit test sul componente Blade non sono richiesti in quanto presenta minima logica di presentazione; la correttezza è verificata tramite:
  - Test sui metodi `BaseRating::getTxtHtml()`, `getValueHtml()`, `getNoteHtml()` (esistenti nel modulo Rating).
  - Integrazione tramite test Pest sulle viste che usano il componente (es. esportazioni XLS, viste scheda).

### Sicurezza
- L'output usa `{!! !!}` poiché i metodi del modello restituiscono già stringhe HTML-safe (per `getTxtHtml()`) o valori escapati (per `getValueHtml()` quando necessario).
- `getNoteHtml()` restituisce una stringa che può contenere HTML semplice (es. `<br>`); è responsabilità del chiamante assicurarsi che il contenuto sia safe. Nel contesto attuale del modulo Rating, la nota è testo semplice o markup minimale già validato.

## Note di implementazione
- Il componente è stato introdotto per evitare duplicazione di Blade logic nelle viste di esportazione e nelle schede dettaglio.
- È progettato per essere usato sia in contesti di esportazione (XLSX tramite Blade) sia in viste HTML standard.
- La classe CSS `rating-table` è lasciata vuota intenzionalmente; lo stile può essere aggiunto globale tramite `app.css` o tramite variabili CSS se necessario.