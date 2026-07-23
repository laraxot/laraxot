<?php

use function Laravel\Folio\name;
use Livewire\Volt\Component;

name('faq.index');

new class extends Component {};
?>

<x-layouts.app>
    @volt('faq.index')
    <div>
        <x-page side="content" slug="faq" />
    </div>
    @endvolt
</x-layouts.app>
