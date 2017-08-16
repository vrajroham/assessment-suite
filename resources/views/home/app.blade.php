<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800|Dosis:300,600" relstylesheet">     -->
    <link href="{{ asset('thesaas/css/core.min.css') }}" rel="stylesheet">    
    <link href="{{ asset('thesaas/css/thesaas.min.css') }}" rel="stylesheet">    
    <link href=" asset('thesaas/css/style.css') }}" rel="stylesheet">    

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
  </head>
    <!-- Topbar -->
    @include('home.layouts.topbar')
    <!-- END Topba
    <!-- Header -->
    @include('home.layouts.header')
    <!-- END Header -->


    <!-- Main container -->
    <main class="main-content">
	@yield('content')
    </main>
    <!-- END Main container -->


    <!-- Footer -->
	@include('home.layouts.footer')    
    <!-- END Footer -->



    <!-- Scripts -->
    <script src="{{ asset('thesaas/js/core.min.js') }}"></script>    
    <script src="{{ asset('thesaas/js/thesaas.min.js') }}"></script>    
    <script src="{{ asset('thesaas/js/script.js') }}"></script>    

  </body>
</html>
