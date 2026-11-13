<?php

declare(strict_types=1);
?>
<div>
    @component('ui::components.modal.simple', ['guid' => $modal_guid, 'title' => $modal_title])
        @slot('content')
            @if (session()->has('message'))
                <div class="alert alert-success" style="margin-top:30px;">x
                    {{ session('message') }}
                </div>
            @endif
            @foreach ($goals as $goal)
                @livewire('rate.single', ['model' => $model, 'goal' => $goal])
            @endforeach
        @endslot
        @slot('btns')
<<<<<<< HEAD
            <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">{{ __('xot::rate.actions.close.label') }}</button>
=======
            <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Close</button>
>>>>>>> 77b1e2748 (first)
        @endslot
    @endcomponent

    <button data-toggle="modal" data-target="#{{ $modal_guid }}" class="btn btn-primary mb-2">
<<<<<<< HEAD
        {{ __('xot::rate.actions.vote.label') }} <i class="fas fa-star"></i>
=======
        Vota <i class="fas fa-star"></i>
>>>>>>> 77b1e2748 (first)
    </button>

</div>
