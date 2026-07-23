<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;

name('hello');

new class extends Component {
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }
};

?>

<x-layouts.app>
    <h1>
        Counter
    </h1>

    @volt('hello')
        <div>
            <h1>{{ $count }}</h1>
            <button wire:click="increment">+</button>
        </div>
    @endvolt
</x-layouts.app>
