<?php

declare(strict_types=1);
?>
@extends('adm_theme::layouts.app')
@section('content')

<form action="{{ Request::fullUrl() }}" method="POST" enctype="multipart/form-data">
                @csrf
    <input type="text" name="search" class="form-control">
<<<<<<< HEAD
    <button class="btn btn-primary">search</button>
=======
    <button class="btn btn-primary">{{ __('xot::db_inspector.actions.search.label') }}</button>
>>>>>>> laraxot/dev
</form>

<table class="table table-bordered">
    <tr>
<<<<<<< HEAD
        <th>Table name</th>
        <th>Fields</th>
=======
        <th>{{ __('xot::db_inspector.sections.table_name.label') }}</th>
        <th>{{ __('xot::db_inspector.sections.fields.label') }}</th>
>>>>>>> laraxot/dev
    </tr>
    @foreach ($rows as $row)
        <tr>
            <td>{{ $row['name'] }}
<<<<<<< HEAD
                <br/>model exists ?
                @if ($model_service->modelExistsByTableName($row['name']))
                <b style="color:darkgreen">SI</b>
                @else
                <b style="color:darkred">NO</b>
=======
                <br/>{{ __('xot::db_inspector.sections.model_exists.label') }}
                @if ($model_service->modelExistsByTableName($row['name']))
                <b style="color:darkgreen">{{ __('xot::db_inspector.sections.yes.label') }}</b>
                @else
                <b style="color:darkred">{{ __('xot::db_inspector.messages.no.message') }}</b>
>>>>>>> laraxot/dev
                @endif

            </td>
            <td>
                <table  class="table table-bordered">
                    <tr>
<<<<<<< HEAD
                        <th>Field Name</th>
                        <th>Field Type</th>
=======
                        <th>{{ __('xot::db_inspector.sections.field_name.label') }}</th>
                        <th>{{ __('xot::db_inspector.sections.field_type.label') }}</th>
>>>>>>> laraxot/dev
                    </tr>
                    @foreach ($row['fields'] as $field)
                        <tr><td>{{ $field['name'] }}</td><td>{{ $field['type'] }}</td></tr>
                    @endforeach
                </table>
            </td>
        </tr>
    @endforeach
</table>

@endsection
