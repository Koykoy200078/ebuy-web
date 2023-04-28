<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') </title>

    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keywords')">
    <meta name="author" content="ECOMMERCE">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Scripts -->

    <!-- <link href="{{asset('assets/css/main.css')}}" rel="stylesheet"> -->

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <!-- CSS -->

    <link href="{{ asset('assets/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/owl.theme.default.min.css') }}" rel="stylesheet">
    <!-- {{-- Owl Carousal --}} -->

    <link href="{{ asset('assets/exzoom/jquery.exzoom.css') }}" rel="stylesheet">

    <!-- {{-- EXZOOM Product Image --}} -->
    <link rel="stylesheet" href="{{ asset('assets/css/alertify.min.css') }}" />
    <!-- Default theme -->
    <link rel="stylesheet" href="{{ asset('assets/css/default.min.css') }}" />
    @livewireStyles
</head>

<body>
    <div id="app">

        @include('layouts.inc.frontend.navbar')



        {{-- <main class="py-4"> --}}
        <main>
            @yield('content')
        </main>

        @include('layouts.inc.frontend.footer')

    </div>

    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}">
    </script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/js/alertify.min.js') }}"></script>
    <script>
        window.addEventListener('message', event => {
            if (event.detail) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify(event.detail.text, event.detail.type);
            }
        });
    </script>

    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('assets/exzoom/jquery.exzoom.js') }}"></script>

    <script src="{{ asset('admin/js/dashboard.js') }}"></script>
    <script src="{{ asset('admin/js/data-table.js') }}"></script>
    <script src="{{ asset('admin/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('admin/js/dataTables.bootstrap4.js') }}"></script>
    <!-- End custom js for this page-->

    @yield('scripts2')

    @yield('script')

    @livewireScripts
    @stack('scripts')

</body>

</html>