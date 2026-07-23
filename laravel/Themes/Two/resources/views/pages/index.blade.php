<?php

use function Laravel\Folio\name;

name('home');

?>

<x-layouts.app>
    @volt('home')
    <div>
    
        <x-page side="content" slug="home" />
        
    </div>
    @endvolt
</x-layouts.app>
