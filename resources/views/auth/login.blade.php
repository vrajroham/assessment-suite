<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <title>Login</title>

        <!-- Styles -->
        <link href="{{ asset('thesaas/css/core.min.css') }}" rel="stylesheet">
        <link href="{{ asset('thesaas/css/thesaas.min.css') }}" rel="stylesheet">
        <link href="{{ asset('thesaas/css/style.css') }}" rel="stylesheet">
        <!-- Favicons -->
        <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">
        <link rel="icon" href="{{ asset('img/favicon.png') }}">
    </head>
    <body class="mh-fullscreen bg-img center-vh p-20" style="background-image: url({{ asset('img/bg-key1.jpg')  }});">
        <div class="card card-shadowed p-40 w-400 mb-0" style="max-width: 100%">
            <h5 class="text-uppercase text-center">Login</h5>
            <br><br>
            <form method="post" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' text-danger' : '' }}">
                    <input type="text" class="form-control" placeholder="Username" name="email" required="">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' text-danger' : '' }}">
                    <input type="password" class="form-control" placeholder="Password" name="password" required="">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group flexbox py-10">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Remember me</span>
                    </label>
                    <a class="text-muted hover-primary fs-13" href="#">Forgot password?</a>
                </div>
                <div class="form-group">
                    <button class="btn btn-bold btn-block btn-primary" type="submit">Login</button>
                </div>
            </form>
            <p class="text-center text-muted fs-13 mt-20">Don't have an account? <a href="{{ url('/') }}">Sign up</a></p>
        </div>
        <!-- Scripts -->
        <script src="{{ asset('thesaas/js/core.min.js') }}"></script>
        <script src="{{ asset('thesaas/js/thesaas.min.js') }}"></script>
        <script src="{{ asset('thesaas/js/script.js') }}"></script>
    </body>
</html>
