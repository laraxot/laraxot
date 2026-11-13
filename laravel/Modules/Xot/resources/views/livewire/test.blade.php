<?php

declare(strict_types=1);
?>
<div>
    <div class="btn-group group-toggle">
        <label class="btn btn-danger">
            <input wire:model="animal" name="animal" type="radio" value="cats" /> Cats
        </label>
        <label class="btn btn-danger">
            <input wire:model="animal" name="animal" type="radio" value="dogs" /> Dogs
        </label>
        <label class="btn btn-danger">
            <input wire:model="animal" name="animal" type="radio" value="both" /> Both
        </label>
    </div>
    [{{ $animal }}]
    <hr />
    {{--
    https://forum.laravel-livewire.com/t/unable-to-get-checkbox-value/157/7
    --}}

    <div class="mb-4">
        @foreach ($options as $key => $option)
            <div>
                <label class="inline-flex items-center">
                    <input wire:model="options.{{ $key }}" name="options[{{ $key }}]" value="1" type="checkbox">
                    <span class="ml-2">{{ $key }}</span>
                </label>
            </div>
        @endforeach
    </div>

    @foreach ($options as $key => $option)
        {{ $key }}: {{ $option ? 'true' : 'false' }}<br />
    @endforeach
    <hr />
    @for ($i = 0; $i < 3; $i++)
        <h3>{{ $i }}</h3>
        <div class="btn-group btn-group-toggle">
            <label class="btn btn-danger">
                <input type="radio" wire:model="qty1.{{ $i }}" name="qty1[{{ $i }}]" value="-1" />
                <span>-</span>
            </label>
            <label class="btn btn-secondary">
                <input type="radio" wire:model="qty1.{{ $i }}" name="qty1[{{ $i }}]" value="0" />
                <span>&nbsp;</span>
            </label>
            <label class="btn btn-primary">
                <input type="radio" wire:model="qty1.{{ $i }}" name="qty1[{{ $i }}]" value="1" />
                <span>+</span>
            </label>
        </div>
    @endfor
    <pre>{{ print_r($qty1) }}</pre>
    <hr />



    @foreach ($products as $product)
        <h3>{{ $product->title }}</h3>
        @foreach ($change_cats as $change_cat)
<<<<<<< HEAD
            <h4>[{{ $change_cat->id }}]{{ $change_cat->title }}</h4>
            @foreach ($changes->where('id_cat', $change_cat->id) as $change)
                <h5>[{{ $change->id }}]{{ $change->title }}</h5>
=======
            <h4>[{{ $change_cat-> }}]{{ $change_cat->title }}</h4>
            @foreach ($changes->where('id_cat', $change_cat->) as $change)
                <h5>[{{ $change-> }}]{{ $change->title }}</h5>
>>>>>>> 77b1e2748 (first)


                <div class="btn-group btn-group-toggle">
                    <label class="btn btn-danger">
<<<<<<< HEAD
                        <input type="radio" wire:model="qty.{{ $change_cat->id }}.{{ $change->id }}"
                            name="qty[{{ $change_cat->id }}][{{ $change->id }}]" autocomplete="off" value="-1">
                        @if (isset($qty[$change_cat->id][$change->id]) && $qty[$change_cat->id][$change->id] == -1)
=======
                        <input type="radio" wire:model="qty.{{ $change_cat-> }}.{{ $change-> }}"
                            name="qty[{{ $change_cat-> }}][{{ $change-> }}]" autocomplete="off" value="-1">
                        @if (isset($qty[$change_cat->][$change->]) && $qty[$change_cat->][$change->] == -1)
>>>>>>> 77b1e2748 (first)
                            [-]
                        @else
                            -
                        @endif
                    </label>
                    <label class="btn btn-secondary">
<<<<<<< HEAD
                        <input type="radio" wire:model="qty.{{ $change_cat->id }}.{{ $change->id }}"
                            name="qty[{{ $change_cat->id }}][{{ $change->id }}]" autocomplete="off" value="0">
                        @if (isset($qty[$change_cat->id][$change->id]) && $qty[$change_cat->id][$change->id] == 0)
=======
                        <input type="radio" wire:model="qty.{{ $change_cat-> }}.{{ $change-> }}"
                            name="qty[{{ $change_cat-> }}][{{ $change-> }}]" autocomplete="off" value="0">
                        @if (isset($qty[$change_cat->][$change->]) && $qty[$change_cat->][$change->] == 0)
>>>>>>> 77b1e2748 (first)
                            [&nbsp;]
                        @else
                            &nbsp;
                        @endif
                    </label>
                    <label class="btn btn-primary active">
<<<<<<< HEAD
                        <input type="radio" wire:model="qty.{{ $change_cat->id }}.{{ $change->id }}"
                            name="qty[{{ $change_cat->id }}][{{ $change->id }}]" autocomplete="off" value="1">
                        @if (isset($qty[$change_cat->id][$change->id]) && $qty[$change_cat->id][$change->id] == 1)
=======
                        <input type="radio" wire:model="qty.{{ $change_cat-> }}.{{ $change-> }}"
                            name="qty[{{ $change_cat-> }}][{{ $change-> }}]" autocomplete="off" value="1">
                        @if (isset($qty[$change_cat->][$change->]) && $qty[$change_cat->][$change->] == 1)
>>>>>>> 77b1e2748 (first)
                            [+]
                        @else
                            +
                        @endif
                    </label>
                </div>
            @endforeach
        @endforeach
    @endforeach




    <pre>
    {{ print_r($qty) }}
    </pre>
    ---------------------------------
</div>
