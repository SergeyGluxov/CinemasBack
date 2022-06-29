@extends('adminlte::page')

@section('title', 'Dashboard')


@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('content_header')
@stop

@section('content')
    <section class="content">
        <div id="app">
            <content-component :id="{{$id}}"></content-component>
        </div>
    </section>
@stop


@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
