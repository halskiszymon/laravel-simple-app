<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Edytuj wpis - Zadanie 1 - Szymon Halski</title>
        @include('include.head')
    </head>
    <body class="text-center">
        @include('include.header')
        <main class="form-auth mt-5">
            <form id="form-modify">
                {{ csrf_field() }}
                <img class="mb-4" src="{{ asset('img/bootstrap-logo.svg') }}" alt="" width="72" height="57">
                <h1 class="h3 mb-3 fw-normal">Edytuj wpis</h1>
                <div class="row justify-content-center" id="form-alert" style="display: none;"><div class="alert" role="alert" id="form-alert-reason"></div></div>

                <label for="id" class="visually-hidden">Podaj id wpisu.</label>
                <input type="number" id="id" name="id" class="form-control" @if(isset($entry)) value="{{ $entry['id'] }}" hidden @endif placeholder="Podaj id wpisu." autofocus required>

                @if(isset($entry))
                    <label for="name" class="visually-hidden">Podaj treść wpisu.</label>
                    <input type="text" id="name" name="name" class="form-control mt-2" value="{{ $entry['name'] }}" placeholder="Podaj treść wpisu." required>

                    <button class="w-100 btn btn-lg btn-primary mt-2" type="submit">Edytuj wpis</button>
                @else
                    <button class="w-100 btn btn-lg btn-primary mt-2" type="submit">Wyszukaj</button>
                @endif
            </form>
        </main>
        <script>
            $('#form-modify').on('submit', function(event)
            {
                event.preventDefault();
                $.ajax(
                    {
                        dataType: 'json',
                        type: 'POST',
                        url: '{{ route('requests.modify') }}',
                        data: $('#form-modify').serialize(),
                        success: function(data)
                        {
                            if(!data['result']['success'])
                            {
                                $('#form-alert-reason').html('<b>Ooops, coś poszło nie tak.</b><br>' + data['result']['message']);
                                $('#form-alert').fadeIn().children().first().removeClass('alert-success').addClass('alert-danger');
                            }
                            else
                            {
                                if(!! data['result']['data'])
                                {
                                    $('#form-modify :last').before(
                                        '<label for="name" class="visually-hidden">Podaj treść wpisu.</label>' +
                                        '<input type="text" id="name" name="name" class="form-control mt-2" value="' + data['result']['data']['name'] + '" placeholder="Podaj treść wpisu." required>'
                                    ).hide().fadeIn();
                                    $('#form-modify button').html('Edytuj wpis');
                                }
                                else
                                {
                                    $('#form-alert-reason').html('<b>Sukces!</b><br>' + data['result']['message']);
                                    $('#form-alert').fadeIn().children().first().removeClass('alert-danger').addClass('alert-success');
                                }
                            }
                        },
                        error: function()
                        {
                            $('#form-alert-reason').html('<b>Ooops, coś poszło nie tak.</b><br>Wystąpił błąd podczas modyfikacji wpisu.');
                            $('#form-alert').fadeIn().children().first().removeClass('alert-success').addClass('alert-danger');
                        }
                    });
            });
        </script>
    </body>
</html>
