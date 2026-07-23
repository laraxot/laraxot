# Page Component Runtime

## Regola

La blade [page.blade.php](../resources/views/components/page.blade.php) deve restare minimale.

La logica del componente pagina vive nel file PHP [Page.php](../../../../Modules/Cms/app/View/Components/Page.php).

## Responsabilita di `Page.php`

`Modules\Cms\View\Components\Page` deve:

- ricevere `data`, `side`, `slug`, `type`
- derivare lo slug finale
- caricare i blocchi con `PageModel::getBlocksBySlug()`
- passare alla view solo i dati gia risolti

## Responsabilita della blade

La blade deve solo:

- ciclare i blocchi
- includere la view di ciascun blocco
- stampare il contenitore minimo della pagina

Non deve:

- risolvere slug
- interrogare modelli
- decidere fallback applicativi
- contenere logica di business

## Perche

### DRY

La logica di risoluzione del contenuto deve stare in un solo punto.

### KISS

La blade e un renderer, non un orchestratore.

### Override tema sicuro

Se il tema override la blade `page`, deve mantenere un contratto minimale e non duplicare il comportamento del componente PHP del modulo CMS.

## Stato corrente

La blade del tema e identica a quella del modulo CMS e rispetta questa regola.

## Collegamenti

- [layout-runtime-contract.md](./layout-runtime-contract.md)
- [component-page-alias-rule.md](./component-page-alias-rule.md)
- [../../Modules/Cms/docs/component-page-runtime.md](../../Modules/Cms/docs/component-page-runtime.md)
