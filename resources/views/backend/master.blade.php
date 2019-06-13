<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }}</title>
    {{ Html::script('assets/jquery/dist/jquery.min.js') }}
    {{ Html::style('assets/bootstrap/dist/css/bootstrap.min.css') }}
    {{ Html::style('css/all.css') }}
    {{ Html::style('css/backend-style.css') }}
    {{ Html::style('css/flat-icon/flaticon.css') }}
    {{ Html::style('assets/font-awesome/css/font-awesome.min.css') }}
    {{ Html::script('assets/bootstrap/dist/js/bootstrap.min.js') }}
    @yield('css')
</head>
<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row full-width wrapper">
        <div class="large-12 columns content-bg">
            @include('backend.layout.top-menu')
            <div class="row">
                @include('backend.layout.left-side-bar')
                @yield('main')
            </div>
        </div>
    </div>
    {{ Html::script('js/allscript.js') }}
    {{ Html::script('js/backend-scripts.js') }}
    @yield('script')
</body>
</html>
