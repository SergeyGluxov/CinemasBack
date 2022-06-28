
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>


    </script>
@stop
@section('content')
    <section class="content">
        <div id="app">
            <content-table-component/>
        </div>
    </section>

@stop



@section('js')
    <script> console.log('Hi!'); </script>
@stop
