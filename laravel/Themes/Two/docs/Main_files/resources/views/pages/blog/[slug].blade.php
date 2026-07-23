<?php
use function Laravel\Folio\name;
use Livewire\Volt\Component;

name('blog.show');

new class extends Component {
    public string $slug;
};
?>

<x-layouts.app>
    @volt('blog.show')
    <div>
        <x-page side="content" :slug="'blog/'.$slug" />
    </div>
    @endvolt
</x-layouts.app>
