<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>
    <body class="antialiased">
        <header>
            @if (Route::has('login'))
                <div class="navbar navbar-expand-lg navbar-light bg-light justify-content-end">
                    @auth
                        <a href="{{ route('home') }}" class="text-sm text-gray-700 underline pr-3">Home</a>
                        <a href="{{ route('logout') }}" class="text-sm text-gray-700 underline">Log out</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </header>

        <div class="container">
            @yield('content')
        </div>

        <footer>
            @if (Route::has('login'))
                <nav class="navbar navbar-expand-lg navbar-light bg-light d-flex justify-content-center">
                    @auth
                        <a href="{{ route('home') }}" class="text-gray-700 pr-3"><i class="fas fa-home"></i></a>
                        <a href="{{ route('logout') }}" class="text-gray-700"><i class="fas fa-sign-out-alt"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700"><i class="fas fa-sign-in-alt"></i></a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-gray-700"><i class="fas fa-user"></i></a>
                        @endif
                    @endauth
                </nav>
            @endif
        </footer>
    </body>
</html>
