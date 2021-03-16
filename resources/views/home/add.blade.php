<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Dodaj wpis - Zadanie 1 - Szymon Halski</title>
        @include('include.head')
    </head>
    <body class="text-center">
        @include('include.header')
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
                        error: function()
                        {
                            $('#form-alert-reason').html('<b>Ooops, coś poszło nie tak.</b><br>Wystąpił błąd podczas dodawania wpisu.');
                            $('#form-alert').fadeIn().children().first().removeClass('alert-success').addClass('alert-danger');
                        }
                    });
            });
        </script>
    </body>
</html>

