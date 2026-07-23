<?php
use function Laravel\Folio\name;
use Livewire\Volt\Component;

name('services.detail');

new class extends Component {
    public string $slug;
};
?>

<x-layouts.app>
    @volt('services.detail')
    <div>
        <x-page side="content" :slug="'services/'.$slug" />
    </div>
    @endvolt
</x-layouts.app>