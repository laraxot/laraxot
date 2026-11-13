@props(['title' => '', 'metaDescription' => '', 'bodyPage' => '', 'searchLinks' => [], 'headerActive' => ''])
{{--
    Alias legacy con l'header dell'utente autenticato: il riferimento delle aree personali (`*-area-personale`, `graduatoria-messaggio`)
    mostra il menu utente al posto di "Accedi all'area personale". Si sceglie con `"layout": "design-comuni-authenticated"` nel JSON della pagina
    (pages/tests/[slug].blade.php); l'header legge la variabile di vista `dcSignedIn` e prende nome, avatar e menu da sections/header.json (`account.signed_in`).
--}}
@php(view()->share('dcSignedIn', true))
<x-layouts.app :title="$title" :meta-description="$metaDescription" :body-page="$bodyPage" :search-links="$searchLinks" :header-active="$headerActive">{{ $slot }}</x-layouts.app>
