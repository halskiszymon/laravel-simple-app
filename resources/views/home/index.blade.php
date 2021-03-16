<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Todo list - Zadanie 1 - Szymon Halski</title>
        @include('include.head')
    </head>
    <body class="text-center">
        @include('include.header')
        <main>
            <div class="row justify-content-center">
                <div class="col-11 col-sm-8 col-md-9 mt-5">
                    <h1 class="h3 mb-3 fw-normal">Todo list</h1>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Treść</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $entry)
                            <tr>
                                <th scope="row">{{ $entry->id }}</th>
                                <td>{{ $entry->name }}</td>
                                <td><a href="{{ route('home.modify-id', ['id' => $entry->id]) }}" class="btn btn-primary">Edytuj</a> <a href="{{ route('home.remove-id', ['id' => $entry->id]) }}" class="btn btn-danger">Usuń</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>

