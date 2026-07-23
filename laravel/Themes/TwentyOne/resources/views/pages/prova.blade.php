<x-layouts.simple>
    <div class="text-right">
        <livewire:cart-preview />
    </div>

    <div class="grid grid-cols-3 gap-8 mt-8">
        @foreach (range(1, 9) as $item)
            <livewire:item />
        @endforeach
    </div>
</x-layouts.simple>
