# Brainstorming — spostamento formFieldLabel e ratingXlsValuePath in RatingData

## Contesto
Due metodi statici di `HasRatingsTrait` (`formFieldLabel` e `ratingXlsValuePath`) sono utilizzati in diversi punti (RatingData, CompilaIndennitaResponsabilita, DecoratesRatingFormFields, test). Sono utility su rating, non su relazioni. L'utente suggerisce di spostarli in `RatingData.php`.

## Analisi pro/contro (con percentuali)

### Pro spostamento (85%)
- **Coerenza semantica** (90%): i metodi operano su rating, non su relazioni. `RatingData` è il DTO di rating, quindi ha senso che contenga utility su rating.
- **Riduzione coupling** (80%): il trait `HasRatingsTrait` sarebbe meno pesante e più focalizzato sulle relazioni.
- **Centralizzazione** (75%): tutte le utility rating vivono in `RatingData` (insieme a `fromArray`, `updateColumns`, etc.).
- **Manutenibilità** (70%): un solo posto per modificare la logica di label/path.
- **Allineamento con richiesta utente** (100%): l'utente ha esplicitamente chiesto lo spostamento.

### Contro spostamento (15%)
- **Allagamento responsabilità DTO** (60%): `RatingData` è un DTO UI; aggiungere utility export potrebbe mescolare preoccupazioni. Tuttavia, già contiene `updateColumns` (schema), quindi è parzialmente giustificato.
- **Impatto refactor** (40%): bisogna aggiornare callers e test, ma è un cambio meccanico.
- **Perdita di coerenza con trait** (30%): se altri metodi del trait dipendono da questi, potrebbero rompersi. Ma i metodi sono statici e indipendenti.

### Dubbi (risolti)
- **Dubito che RatingData sia il posto giusto?** No, perché l'utente lo chiede esplicitamente e perché i metodi sono generici su rating (non legati a una relazione specifica).
- **Dubito che la rottura dei callers sia significativa?** No, è un cambio di namespace solo; il pattern è simile (static::method()).
- **Dubito che il test debba essere aggiornato?** Sì, il test deve chiamare `RatingData::formFieldLabel` invece del trait.

## Punti di forza
- **Forza (95%)**: elimina la duplicazione di utility tra trait e DTO (RatingData già chiama il trait).
- **Forza (85%)**: migliora la leggibilità: gli sviluppatori cercano utility rating in RatingData, non in un trait.
- **Forza (75%)**: facilita il testing: si può testare il metodo senza istanziare il trait.

## Punti deboli
- **Debolezza (30%)**: RatingData diventa più "grasso", ma è accettabile per il dominio rating.
- **Debolezza (20%)**: se in futuro altri moduli volessero usare questi metodi, dovranno importare RatingData (non grave).

## Decisione
Spostare entrambi i metodi in `RatingData.php` come metodi statici pubblici. Aggiornare tutti i callers e i test. Mantenere il trait `HasRatingsTrait` senza questi due metodi (o con alias di compatibilità? Meglio non avere alias per evitare confusione e duplicazione).

## Prossimi passi
- Creare architecture doc per il nuovo design.
- Creare story BMAD per il task.
- Implementare con lock, verifica PHPStan/Pest, aggiornare second brain.