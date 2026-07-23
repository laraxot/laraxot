<?php
declare(strict_types=1);
use function Laravel\Folio\{name};
use Livewire\Volt\Component;

name('pages.view');

new class extends Component
{
    public string $slug;
};
?>

<x-layouts.app>
    @volt('pages.view')
    <div>
        <x-page side="content" :slug="$slug" />
    </div>
    @endvolt
</x-layouts.app>
