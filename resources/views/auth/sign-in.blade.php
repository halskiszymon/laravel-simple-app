<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Zaloguj się - Zadanie 1 - Szymon Halski</title>
        @include('include.head')
    </head>
    <body class="text-center">
        @include('include.header')
        <main class="form-auth mt-5">
            <form id="form-sign-in">
                {{ csrf_field() }}
                <img class="mb-4" src="{{ asset('img/bootstrap-logo.svg') }}" alt="" width="72" height="57">
                <h1 class="h3 mb-3 fw-normal">Zaloguj się</h1>
                <div class="row justify-content-center" id="form-alert" style="display: none;"><div class="alert" role="alert" id="form-alert-reason"></div></div>

                <label for="email" class="visually-hidden">Wpisz adres e-mail</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Wpisz adres e-mail." autofocus required>

                <label for="password" class="visually-hidden">Wpisz hasło.</label>
                <input type="password" id="password" name="password" class="form-control mt-2" placeholder="Wpisz hasło." required>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Zaloguj się</button>
            </form>
        </main>
        <script>
            $('#form-sign-in').on('submit', function(event)
            {
                event.preventDefault();
                $.ajax(
                    {
                        dataType: 'json',
                        type: 'POST',
                        url: '{{ route('requests.sign-in') }}',
                        data: $('#form-sign-in').serialize(),
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

                                $('#form-sign-in button').attr('disabled', 'disabled');
                                setTimeout(function()
                                {
                                    window.location = '{{ url('/') }}';
                                }, 3000);
                            }
                        },
                        error: function()
                        {
                            $('#form-alert-reason').html('<b>Ooops, coś poszło nie tak.</b><br>Wystąpił błąd podczas rejestracji.');
                            $('#form-alert').fadeIn().children().first().removeClass('alert-success').addClass('alert-danger');
                        }
                    });
            });
        </script>
    </body>
</html>

