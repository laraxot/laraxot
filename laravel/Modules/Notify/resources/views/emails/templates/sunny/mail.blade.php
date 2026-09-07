<?php

declare(strict_types=1);

?>
@extends('notify::emails.templates.sunny')

@section('content')

    {{-- @include ('beautymail::templates.sunny.heading', [
        'heading' => 'Hello!',
<<<<<<< HEAD
<<<<<<< HEAD
        'level' => 'h1']) --}}
=======
        'level' => 'h1',
    ]) --}}
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'level' => 'h1']) --}}
>>>>>>> a988596b (first)

    @include('notify::emails.templates.sunny.contentStart')

    {!! $html !!}

    @include('notify::emails.templates.sunny.contentEnd')

    {{-- @include('beautymail::templates.sunny.button', [
        'title' => 'Click me',
<<<<<<< HEAD
<<<<<<< HEAD
        'link' => 'http://google.com']) --}}
=======
        'link' => 'http://google.com',
    ]) --}}
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'link' => 'http://google.com']) --}}
>>>>>>> a988596b (first)

@stop
