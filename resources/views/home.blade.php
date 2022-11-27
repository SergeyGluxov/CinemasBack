
@section('title', 'Dashboard')


@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <section class="content">
        <div id="app">
        </div>
    </section>
@stop


@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
