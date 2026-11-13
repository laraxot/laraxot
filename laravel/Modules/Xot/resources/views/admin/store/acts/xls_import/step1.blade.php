<?php

declare(strict_types=1);
?>
@extends('adm_theme::layouts.app')
@section('content')
<<<<<<< HEAD
PASSO 1
=======
{{ __('xot::xls_import.steps.step1.title') }}
>>>>>>> laraxot/dev
{!! $data_html !!}
<form action="{{ Request::fullUrlWithQuery(['step' => 2]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
    @foreach ($head as $v)
        <div class="col">
        {{ $v }}
        <select name="key[{{ $v }}]" class="form-select custom-select form-input" >
            <option value="">---</option>
            @foreach ($fillable as $vf)
                <option value="{{ $vf }}">{{ $vf }}</option>
            @endforeach
        </select>
        </div>
    @endforeach
    </div>
    <br/>
<<<<<<< HEAD
    <button class="btn btn-success">Import!</button>
=======
    <button class="btn btn-success">{{ __('xot::xls_import.actions.import.label') }}</button>
>>>>>>> laraxot/dev
</form>


@endsection
