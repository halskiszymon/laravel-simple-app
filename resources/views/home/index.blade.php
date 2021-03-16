<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Szymon Halski">
        <title>Todo list - Zadanie 1 - Szymon Halski</title>
        <link rel="icon" href="{{ asset('img/favicon-16x16.png') }}" sizes="16x16">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body class="text-center">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ url('/') }}">Zadanie1</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar">
                        <div class="navbar-nav">
                            <a class="nav-link active" href="{{ route('home.index') }}">Lista</a>
                            <a class="nav-link" href="{{ route('home.add') }}">Dodaj wpis</a>
                            <a class="nav-link" href="{{ route('home.modify') }}">Edytuj wpis</a>
                            <a class="nav-link" href="{{ route('home.remove') }}">Usuń wpis</a>
                            <a class="nav-link" href="{{ route('requests.logout') }}">Wyloguj się</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <div class="row justify-content-center">
                <div class="col-11 col-sm-8 col-md-9 mt-5">
                    <h1 class="h3 mb-3 fw-normal">Todo list</h1>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Treść</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $entry)
                            <tr>
                                <th scope="row">{{ $entry->id }}</th>
                                <td>{{ $entry->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>

