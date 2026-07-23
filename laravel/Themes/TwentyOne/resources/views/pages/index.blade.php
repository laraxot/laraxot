<?php // pages/dashboard.blade.php
use function Laravel\Folio\{name};

name('home');
?>
<x-layouts.app>
     <x-cms::page side="content" slug="home" />
</x-layouts.app>
