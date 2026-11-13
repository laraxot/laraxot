@props(['title' => '', 'metaDescription' => '', 'bodyPage' => ''])
{{--
    Layout senza chrome per il catalogo dei template (pages/tests/index): il riferimento (index.html) ha solo `<main>`, senza skiplink, header,
    footer ne' modale. Stessa regola di layouts/design-comuni: `<body>` plain, stile sotto `body:has(.page-content[data-dc])`.
--}}
<x-layouts.main :title="$title" :description="$metaDescription" :body-page="$bodyPage">
    <main>{{ $slot }}</main>
</x-layouts.main>
