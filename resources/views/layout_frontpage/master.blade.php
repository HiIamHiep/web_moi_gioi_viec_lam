<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="">
    <link rel="icon" type="image/png" href="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $title ?? config('app.name', 'Laravel')}}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/material-kit.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body class="ecommerce-page">
<!-- navbar -->

@include('layout_frontpage.navbar')

<!-- end navbar -->

<!-- navbar -->

@include('layout_frontpage.header')

<!-- end navbar -->

<div class="main main-raised">
    <!-- section -->
    <div class="section">
        <div class="container">
            @yield('content')
        </div>
    </div>
</div>
<!-- section -->

<!-- end-main-raised -->

<!-- footer -->

@include('layout_frontpage.footer')

<!-- end footer -->

<script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/material.min.js') }}"></script>

<!--	Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/   -->
<script src="{{ asset('js/nouislider.min.js') }}" type="text/javascript"></script>

<!--	Plugin for Tags, full documentation here: http://xoxco.com/projects/code/tagsinput/   -->
<script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>

<!-- Control Center for Material Kit: activating the ripples, parallax effects, scripts from the example pages etc -->
<script src="{{ asset('js/material-kit.js') }}" type="text/javascript"></script>

@stack('js')

</body>
<!--   Core JS Files   -->
</html>
