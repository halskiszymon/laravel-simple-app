<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">Zadanie1</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <div class="navbar-nav">
                    @if(\App\Http\Controllers\AuthController::isLogged())
                    <a class="nav-link @if($page == route('home.index')) active @endif" href="{{ route('home.index') }}">Lista</a>
                    <a class="nav-link @if($page == route('home.add')) active @endif" href="{{ route('home.add') }}">Dodaj wpis</a>
                    <a class="nav-link @if($page == route('home.modify')) active @endif" href="{{ route('home.modify') }}">Edytuj wpis</a>
                    <a class="nav-link @if($page == route('home.remove')) active @endif" href="{{ route('home.remove') }}">Usuń wpis</a>
                    <a class="nav-link" href="{{ route('requests.logout') }}">Wyloguj się</a>
                    @else
                    <a class="nav-link @if($page == route('auth.sign-up')) active @endif" href="{{ route('auth.sign-up') }}">Zarejestruj się</a>
                    <a class="nav-link @if($page == route('auth.sign-in')) active @endif" href="{{ route('auth.sign-in') }}">Zaloguj się</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</header>
