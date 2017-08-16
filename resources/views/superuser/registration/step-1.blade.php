<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <title>Register : Super User</title>
        
        <!-- Styles -->
        <link href="{{ asset('thesaas/css/core.min.css') }}" rel="stylesheet">
        <link href="{{ asset('thesaas/css/thesaas.min.css') }}" rel="stylesheet">
        <link href="{{ asset('thesaas/css/style.css') }}" rel="stylesheet">

        <!-- Favicons -->
        <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">
        <link rel="icon" href="{{ asset('img/favicon.png') }}">

    </head>
    <body class="mh-fullscreen bg-img center-vh p-20" style="background-image: url({{ asset('img/bg-4.jpg')  }});">      
        <div class="card card-shadowed p-20 w-400 mb-0" style="max-width: 100%">
              @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h5 class="text-uppercase text-center p-10">Register</h5>
            <br><br>
            <form class="form-type-material" action="{{ route('superuser.register.save') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group har-error">
                    <input type="text" class="form-control" placeholder="E.g. John Doe" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="E.g. jondoe@example.com" name="email" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="E.g. 9876543210" name="mobile" value="{{ old('mobile') }}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="E.g. Professor" name="designation" value="{{ old('designation') }}">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password (confirm)" name="password_confirmation">
                </div>
                <div class="form-group">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">I agree to all <a class="text-primary" href="#">terms</a></span>
                    </label>
                </div>
                <br>
                <button class="btn btn-bold btn-block btn-primary" type="submit">Register</button>
            </form>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('thesaas/js/core.min.js') }}"></script>
        <script src="{{ asset('thesaas/js/thesaas.min.js') }}"></script>
        <script src="{{ asset('thesaas/js/script.js') }}"></script>

    </body>
</html>