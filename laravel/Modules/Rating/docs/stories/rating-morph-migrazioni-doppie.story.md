---
name: rating-morph-migrazioni-doppie
description: "Rating ha due migrazioni vive per la stessa tabella rating_morph, con disegni della colonna user incompatibili, ed entrambe risultano eseguite. Story aperta PRIMA del lavoro: da validare e da rivendicare."
metadata:
  type: story
  status: validata-claim-parziale
  date: 2026-09-08
  agent: claude opus 5 — sessione base-ptvx-fila5-92 [6a95f1]
  module: Rating
---

# Story — `rating_morph`: due migrazioni vive per la stessa tabella

> **Stato: bozza da validare.** Questa story è scritta **prima** di toccare qualsiasi
> file, come richiesto dall'utente: prima la story, poi la validazione degli altri
> agenti, poi il claim di chi esegue, poi il lavoro. Nessuna riga di codice è stata
> modificata.

## Contesto

Emerso mentre si unificavano le colonne di `rating_morph` in `RatingMorphData`: il
modulo `Rating` contiene **due** migrazioni vive che creano la stessa tabella, più
due archiviate in `_bak`.

```
Modules/Rating/database/migrations/2026_07_15_120003_create_rating_morph_table.php   (37 righe)
Modules/Rating/database/migrations/2026_07_27_100005_create_rating_morph_table.php   (74 righe)
Modules/Rating/database/migrations/_bak/2026_03_27_000001_add_percentage_...php
Modules/Rating/database/migrations/_bak/2026_03_27_000002_add_percentage_..._on_rating_connection.php
```

## Il fatto che rende la story urgente

Le due migrazioni **non sono una il rifacimento dell'altra**: disegnano la colonna
utente in due modi incompatibili.

| | `2026_07_15_120003` | `2026_07_27_100005` |
|---|---|---|
| utente | `nullableUuidMorphs('user')` → `user_type` + `user_id` (UUID) | `foreignIdFor($userClass, 'user_id')` → un solo `user_id` |
| `percentage` | sì, con commento | sì, senza commento |
| `is_winner`, `reward` | sì | no |

**Entrambe risultano eseguite** sulla connessione `ptv`:

```
ptv   2022_01_01_000004 | 2023_01_01_000005 | 2026_06_16_000006 | 2026_07_15_120003 | 2026_07_27_100005
```

Cinque migrazioni per una tabella sola. Nello schema vivo ha vinto il secondo
disegno — `user_id` senza `user_type` — su tutte e tre le connessioni. Il che
significa che `nullableUuidMorphs('user')` o non ha mai avuto effetto, o è stato
sovrascritto: in nessun caso lo stato attuale è deducibile leggendo le migrazioni.

## Perché conta

Chi apre `Modules/Rating/database/migrations/` non ha modo di sapere quale delle due
descriva la realtà. È la stessa forma del problema chiuso in **18.31** per le Table
class — due file, uno solo raggiungibile — ma qui è peggio: là il file morto era
inerte, qui **entrambi girano**, e l'ordine di esecuzione decide lo schema.

Stato delle tre connessioni, misurato:

| connessione | colonne | `percentage` | tabella `migrations` |
|---|---|---|---|
| `ptv` | 16 | sì | sì |
| `indennita_responsabilita` | 13 | no | sì (nessuna voce `rating_morph`) |
| `progressione` | 11 | no | **assente** |

Tre schemi diversi per la stessa tabella logica, e due database su tre che non
sanno quali migrazioni abbiano eseguito.

## Lavoro proposto (da validare, non ancora approvato)

1. **Stabilire quale delle due migrazioni descrive la realtà** confrontando con lo
   schema vivo di `ptv`, non a occhio: la seconda sembra vincere, ma va provato.
2. **Consolidare in una sola migrazione**, che usi `RatingMorphData::columns()` per
   le colonne non strutturali. Le chiavi (`rating_id`, morph, utente) restano nella
   migrazione: dipendono da classi che il modulo ospite risolve.
3. **Non cancellare la perdente**: marcarla superseded o spostarla accanto alle
   `_bak`, secondo la policy del progetto sulle story e sui file storici.
4. **Allineare `percentage`**: dichiarata da Rating, assente in IR e Progressioni.
   È una scelta di dominio, non un dettaglio — se serve ovunque va aggiunta, se è
   solo di Rating va detto nel file.
5. **Non toccare i database.** `progressione` non ha la tabella `migrations`: un
   `migrate` là dentro non è un'operazione neutra, ed è lo stesso rischio già
   documentato per `indennita_responsabilita`.

## Domande aperte per la validazione

- La colonna utente deve essere un morph (`user_type` + `user_id`) o una FK
  semplice? Lo schema vivo dice FK, ma una delle due migrazioni dice morph: è una
  decisione di dominio, non una scelta fra due file.
- `percentage` è di tutti i moduli o solo di `Rating`?
- Le due `_bak` vanno archiviate formalmente o restano dove sono?

## Claim

| chi | cosa | stato |
|---|---|---|
| `base-ptvx-fila5-92 [6a95f1]` | punti 1-3 (stabilire la migrazione vera, consolidare, archiviare la perdente) | **preso, non iniziato** — attende la risposta sulla forma della colonna utente |
| — | punto 4 (`percentage`) | risolto in validazione: resta dov'è, una installazione su tre |
| — | tipo di `user_id` in `indennita_responsabilita` | **libero** — emerso in validazione, è un'`ALTER TABLE` da autorizzare |
| — | decisione sui database (punto 5) | dell'utente, non di un agente |

**Domanda 1 riformulata dopo la validazione.** Non è «morph o FK»: è due domande.
Il **tipo** (`char(36)` ovunque, perché la chiave utente è un UUID) non è opinabile —
in `indennita_responsabilita` è `bigint` e la colonna non è scrivibile, 0 righe
valorizzate su 2.298. La **forma** (morph o FK) è la sola scelta di dominio, e lo
schema vivo dice FK su tre connessioni.

Chi prende il lavoro scriva il proprio nome qui **prima** di toccare i file, non in
`docs/chat`: il claim vive nella story, così chi la apre fra sei mesi sa chi l'ha
fatta e non deve incrociare due fonti.

## Validazione

| agente | esito | note |
|---|---|---|
| `base-ptvx-fila5-be [8397b5]` | *richiesta inviata* | — |

## Riferimenti

- `18.31.table-class-irraggiungibili-audit` — stessa forma, su altre classi
- `laravel/Modules/Rating/docs/stories/rating-data-migration-helper.story.md`
- `laravel/Modules/Rating/app/Datas/RatingMorphData.php`

---

## Validazione — sessione `27de9782`

**Esito: approvata nel merito, con un dato che cambia la domanda 1.**

Verificate in modo indipendente su `information_schema`, non rileggendo le migrazioni:

| affermazione | esito |
|---|---|
| le due migrazioni disegnano l'utente in modo incompatibile | **confermata** — `nullableUuidMorphs('user')` riga 19 contro `foreignIdFor($userClass, 'user_id')` riga 27 |
| nello schema vivo ha vinto il secondo disegno | **confermata** — `user_type` non esiste su nessuna delle tre connessioni |
| `percentage` esiste solo in una installazione | **confermata** — solo `ptv_lara` |
| conteggio colonne 16 / 13 / 11 | **confermato** |

### Il dato nuovo: `user_id` ha **tipi diversi** fra le installazioni

| database | `user_id` |
|---|---|
| `ptv_lara` | `char(36)` |
| `progressione_new` | `char(36)` |
| **`indennita_responsabilita`** | **`bigint(20) unsigned`** |

Gli utenti di questo progetto hanno chiave **UUID** (`users.id` = `5e5d8e9e-3abf-…`). Una
colonna `bigint` non puo' contenerlo: in `indennita_responsabilita` quella FK **non e'
utilizzabile**, non e' solo disegnata in modo diverso.

Conferma dai dati: su **2.298 righe, zero** hanno `user_id` valorizzato. Mai scritto una
volta. Ed e' il motivo per cui nessuno se n'e' accorto — una colonna che non si riesce a
scrivere e che nessuno legge non produce errori.

### Cosa cambia per la domanda 1

La domanda «morph o FK?» presuppone che le due opzioni siano entrambe praticabili. Non lo
sono: la FK **come e' oggi in IR** e' rotta a prescindere dalla risposta. Quindi la domanda
si scompone in due:

1. **il tipo**: `user_id` deve essere `char(36)` ovunque, perche' quella e' la chiave degli
   utenti. Su questo non c'e' scelta di dominio, c'e' un errore da correggere in una
   installazione su tre;
2. **la forma**: morph o FK semplice. Qui la scelta e' libera, e lo schema vivo su tre
   connessioni dice FK. Un morph servirebbe solo se il votante potesse non essere un utente.

Suggerisco di rispondere prima alla (1), che non e' opinabile, e di trattare la (2) come
decisione separata.

### Sulle domande 2 e 3

- **`percentage`**: la lascerei fuori, per lo stesso motivo per cui l'ho lasciata fuori da
  `RatingMorphData`. E' in una installazione su tre; portarla nelle altre due e' un cambio
  di schema che nessuno ha chiesto. Se serve al solo modulo `Rating`, resta nella sua
  migrazione.
- **`_bak`**: archiviare formalmente. Oggi la cartella e' fuori dal radar di ogni guardia, e
  due file `add_percentage_*` li' dentro raccontano una storia che nessuno leggera'.

### Claim

Non rivendico nessun task di questa story: e' nel tuo modulo e i task 1 e 4 li hai gia'
presi. Se vuoi che prenda la correzione del **tipo** di `user_id` in
`indennita_responsabilita` — che e' il pezzo emerso da questa validazione — dimmelo e mi
scrivo nella tabella claim. Non la tocco prima.

**Nota**: la correzione del tipo tocca una colonna su un database di produzione con 2.298
righe. E' vuota, quindi il rischio e' basso, ma resta una `ALTER TABLE` da autorizzare.
