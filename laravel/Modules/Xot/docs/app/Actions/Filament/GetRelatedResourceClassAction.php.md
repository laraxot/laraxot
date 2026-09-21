---
title: "Proposta nuova Action — GetRelatedResourceClassAction (non applicata)"
type: code-proposal
status: proposta — nessun codice di produzione creato
mirrors: ../../../../../app/Actions/Filament/GetRelatedResourceClassAction.php (non esiste ancora)
created: 2026-09-11
updated: 2026-09-11
tags: [xot, filament, dry, kiss, bmad, spatie-queueable-action]
related:
  - ../../Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md
  - ../../../../app/Actions/Filament/GetResourceClassNameByModelClassAction.php
  - ../../../../app/Filament/Resources/RelationManagers/XotBaseRelationManager.php
---

# Proposta — `GetRelatedResourceClassAction`

**Solo documentazione. Nessun file reale creato in `app/`.**

## Perche' una Action e non un metodo inline nella pagina

Stesso principio gia' applicato a `PersonColumn`
(`Modules/UI/app/Filament/Tables/Columns/PersonColumn.php`, vedi memoria
`personcolumn-schema-org-aggregate`): quando si riconosce un pezzo di logica
non banale che si ripeterebbe, si estrae nel modulo corretto invece di
scriverlo inline nella classe consumer. Qui il "pezzo" e' la risoluzione
Model-relazione → Resource UI, e il modulo corretto e' `Xot` (infrastruttura
Filament condivisa, come `GetResourceClassNameByModelClassAction` gia'
esistente li'). Segue la regola "no-services" del progetto: logica
riutilizzabile come Spatie Queueable Action (`use QueueableAction`, metodo
`execute()`), non come classe `*Service` o metodo statico sparso.

## Controllo di ridondanza (fatto, non solo dichiarato)

```bash
grep -rl "RelatedResourceClass\|RelatedUiResource\|GetRelatedResource" Modules --include="*.php"
find Modules -iname "Get*ResourceClass*Action.php" -o -iname "*ResourceClass*Action.php"
```

Trovato **un solo** precedente, gia' noto e gia' escluso per un motivo preciso
(non solo "non lo uso"):

- **`GetResourceClassNameByModelClassAction`** (Xot) — usa
  `Filament::getModelResource()`, **scoped al pannello corrente**: verificato
  dal vivo che torna `NULL` per model di un altro modulo, e per
  `QuestionChart` risolverebbe una Resource diversa da quella che la pagina
  dichiara esplicitamente in `$relatedResource`. Non adatto qui.
- **`XotBaseRelationManager::getResource()`/`getResourceClass()`** (Xot,
  classe gemella per i RelationManager a tab, non le pagine
  `ManageRelatedRecords` a pagina intera) — stesso obiettivo, gia' con nomi
  `get*` (mai `resolve*`: nota presa, vedi sotto), ma risolve la Resource
  **risalendo il namespace della classe stessa**
  (`Modules\{Modulo}\Filament\Resources\{Nome}\RelationManagers\{Questo}` →
  `Modules\{Modulo}\Filament\Resources\{Nome}`) — funziona perche' un
  `RelationManager` per convenzione vive DENTRO la cartella della Resource
  correlata. Una pagina `XotBaseManageRelatedRecords` vive invece dentro la
  cartella della Resource **proprietaria**
  (`SurveyPdfResource\Pages\ManageContacts`): la stessa risalita di namespace
  risolverebbe `SurveyPdfResource`, non `ContactResource` — lo stesso errore
  di `$this->getResource()` che l'intera discussione sta cercando di evitare.
  Non riusabile verbatim, ma e' la conferma che la convenzione `get*` (mai
  `resolve*`) e' gia' quella in uso nel progetto per questo tipo di metodo.

Nessuna Action con questo scopo esatto esiste oggi — creane una nuova e'
giustificato, non ridondante.

## Correzione di naming (feedback utente)

`resolveRelatedResourceClass()`/`resolveRelatedUiResourceClass()` (versioni
precedenti di questa proposta, di due sessioni diverse) sono scartati: il
progetto preferisce `get*` a `resolve*` per questo tipo di metodo (coerente
con `XotBaseRelationManager::getResource()`, `XotBaseResource::getFormClass()`
`/getTableClass()`/`getModel()` — mai un `resolve*` in tutta la codebase
Filament di Xot, verificato). Il suffisso "Ui" in `RelatedUiResourceClass`
non aggiunge informazione (non esiste una "Resource non-UI" nel dominio di
questa classe) — rimosso.

## Codice proposto

```php
<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use Illuminate\Support\Str;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Spatie\QueueableAction\QueueableAction;

/**
 * Risolve la XotBaseResource del model MOSTRATO da una pagina
 * XotBaseManageRelatedRecords (il model della relazione, mai quello della
 * Resource proprietaria della pagina — vedi XotBaseManageRelatedRecords.php.md
 * per il perche' $this->getResource() e' sbagliato per questo scopo).
 *
 * Priorita': prima $relatedResource nativo Filament (se la pagina lo
 * dichiara, come gia' fa ManageQuestionCharts), poi convenzione per nome SOLO
 * nello stesso modulo del model (mai Filament::getModelResource(): verificato
 * scoped al pannello corrente, si veda GetResourceClassNameByModelClassAction
 * per il motivo per cui non e' adatto qui).
 */
class GetRelatedResourceClassAction
{
    use QueueableAction;

    /**
     * @param  object  $page  istanza di una XotBaseManageRelatedRecords
     * @return class-string<XotBaseResource>|null
     */
    public function execute(object $page): ?string
    {
        if (method_exists($page, 'getRelatedResource')) {
            /** @var class-string<XotBaseResource>|null $relatedResource */
            $relatedResource = $page::getRelatedResource();
            if ($relatedResource !== null) {
                return $relatedResource;
            }
        }

        if (! method_exists($page, 'getModelClass')) {
            return null;
        }

        /** @var class-string<\Illuminate\Database\Eloquent\Model> $modelClass */
        $modelClass = $page->getModelClass();
        $moduleName = Str::between($modelClass, 'Modules\\', '\Models\\');
        $modelName = class_basename($modelClass);
        $guess = 'Modules\\'.$moduleName.'\Filament\Resources\\'.$modelName.'Resource';

        if (class_exists($guess) && is_subclass_of($guess, XotBaseResource::class)) {
            /** @var class-string<XotBaseResource> $guess */
            return $guess;
        }

        return null;
    }
}
```

Uso previsto da `XotBaseManageRelatedRecords` (vedi doc collegato): un metodo
`getRelatedResourceClass(): ?string` che chiama
`app(GetRelatedResourceClassAction::class)->execute($this)` — `get*`, non
`resolve*`, coerente con la correzione sopra.
