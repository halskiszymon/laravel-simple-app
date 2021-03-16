<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Szymon Halski">
        <title>Dodaj wpis - Zadanie 1 - Szymon Halski</title>
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
                            <a class="nav-link" href="{{ route('home.index') }}">Lista</a>
                            <a class="nav-link active" href="{{ route('home.add') }}">Dodaj wpis</a>
                            <a class="nav-link" href="{{ route('home.modify') }}">Edytuj wpis</a>
                            <a class="nav-link" href="{{ route('home.remove') }}">Usuń wpis</a>
                            <a class="nav-link" href="{{ route('requests.logout') }}">Wyloguj się</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main class="form-auth mt-5">
            <form id="form-add">
                {{ csrf_field() }}
                <img class="mb-4" src="{{ asset('img/bootstrap-logo.svg') }}" alt="" width="72" height="57">
                <h1 class="h3 mb-3 fw-normal">Dodaj wpis</h1>
                <div class="row justify-content-center" id="form-alert" style="display: none;"><div class="alert" role="alert" id="form-alert-reason"></div></div>

                <label for="name" class="visually-hidden">Podaj treść wpisu.</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Podaj treść wpisu." autofocus required>

                <button class="w-100 btn btn-lg btn-primary mt-2" type="submit">Dodaj</button>
            </form>
        </main>
        <script>
            $('#form-add').on('submit', function(event)
            {
                event.preventDefault();
                $.ajax(
                    {
                        dataType: 'json',
                        type: 'POST',
                        url: '{{ route('requests.add') }}',
                        data: $('#form-add').serialize(),
                        success: function(data)
                        {
                            if(!data['result']['success'])
                            {
                                $('#form-alert-reason').html('<b>Ooops, coś poszło nie tak.</b><br>' + data['result']['message']);
                                $('#form-alert').fadeIn().children().first().removeClass('alert-success').addClass('alert-danger');
                            }
                            else
                            {
                                $('#form-alert-reason').html('<b>Sukces!</b><br>' + data['result']['message']);
                                $('#form-alert').fadeIn().children().first().removeClass('alert-danger').addClass('alert-success');

                                $('#form-add').trigger('reset');
                            }
                        },
                        error: function(data)
                        {
                            $('#form-alert-reason').html('<b>Ooops, coś poszło nie tak.</b><br>Wystąpił błąd podczas dodawania wpisu.');
                            $('#form-alert').fadeIn().children().first().removeClass('alert-success').addClass('alert-danger');
                        }
                    });
            });
        </script>
    </body>
</html>

