<?php

declare(strict_types=1);
?>
@extends('pub_theme::layouts.app')
<<<<<<< HEAD
@section('title', 'Web Routes List')
@section('content')
<div class="text-center">
    <h3>Web Routes List</h3>
=======
@section('title', __('xot::route_list.sections.title.title'))
@section('content')
<div class="text-center">
    <h3>{{ __('xot::route_list.sections.title.title') }}</h3>
>>>>>>> laraxot/dev
</div>

<table id="routetable" class="table table-striped table-bordered nowrap" style="width:100%">
    <thead>
        <tr>
<<<<<<< HEAD
            <th scope="col">HTTP Method</th>
            <th scope="col">Route</th>
            <th scope="col">Name</th>
            <th scope="col">Corresponding Action</th>
=======
            <th scope="col">{{ __('xot::route_list.sections.http_method.label') }}</th>
            <th scope="col">{{ __('xot::route_list.sections.route.label') }}</th>
            <th scope="col">{{ __('xot::route_list.sections.name.label') }}</th>
            <th scope="col">{{ __('xot::route_list.sections.action.label') }}</th>
>>>>>>> laraxot/dev
        </tr>
    </thead>
    <tbody>
        @if (request()->has('only') == true)
        @foreach ($routeCollection->getIterator() as $data)
        @if (strpos($data->uri, 'api') !== false)
        <tr>
            <td>{{ $data->methods()[0] }}</td>
            <td>{{ $data->uri() }}</td>
            <td>{{ $data->getName() }}</td>
            <td>{{ $data->getActionName() }}</td>
        </tr>
        @endif
        @endforeach
        @else
        @foreach ($routeCollection as $data)
        <tr>
            <td>{{ $data->methods()[0] }}</td>
            <td>{{ $data->uri() }}</td>
            <td>{{ $data->getName() }}</td>
            <td>{{ $data->getActionName() }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endsection
@section('script-footer')
<script>
    $(document).ready(function() {
        $('#routetable').DataTable({
            "order": [
                [3, "desc"]
            ]
        });
    });
</script>
@endsection
