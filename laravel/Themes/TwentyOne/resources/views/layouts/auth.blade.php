@extends('pub_theme::layouts.base')

@section('body')
    <div class="bg-neutral-1">
        @yield('content')

        @isset($slot)
            {{ $slot }}
        @endisset
    </div>
@endsection
