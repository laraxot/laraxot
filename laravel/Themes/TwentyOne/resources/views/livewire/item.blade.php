<?php

use function Livewire\Volt\state;

state('done', false);

$add = function () {
    $this->dispatch('product-added-to-cart');

    $this->done = true;
};

?>

<div>
    <img src="https://via.placeholder.com/512x512.png/f3f4f6" alt="Product Image" />

    <div class="flex items-start justify-between mt-4">
        <div>
            <div>{{ fake()->sentence(2) }}</div>
            <div class="text-2xl font-bold">${{ rand(10, 100) }}</div>
        </div>

        <button
            class="px-3 py-2 text-sm font-bold text-white bg-blue-600 rounded disabled:bg-gray-200 disabled:text-gray-400"
            @if ($done) disabled @endif
            wire:click="add"
        >
            @if ($done)
                Added
            @else
                Add to Cart
            @endif
        </button>
    </div>
</div>
