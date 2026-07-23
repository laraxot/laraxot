<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;

name('test_volt');

new class extends Component {
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }
};

?>

@volt('test_volt')
<div>
    <h1>{{ $count }}</h1>
    <button wire:click="increment">+</button>
</div>
@endvolt
