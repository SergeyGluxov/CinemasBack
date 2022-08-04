@extends('adminlte::page')

@section('title', 'Пользователи')


@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('content_header')
    <h1>Пользователи</h1>
@stop

@section('content')
    <section class="content">
        <div id="app">
            <passport-clients></passport-clients>
            <passport-personal-access-tokens></passport-personal-access-tokens>
            <passport-authorized-clients></passport-authorized-clients>
        </div>
    </section>
@stop


@section('js')
    <script src="{{ mix('js/app.js') }}"></script>

@stop
