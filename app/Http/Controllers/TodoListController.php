<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    //Generate result for request
    private function generateResult($success, $message, $data = false)
    {
        $result = array();
        $result['result']['success'] = $success;
        $result['result']['message'] = $message;
        if($data !== false)
            $result['result']['data'] = $data;

        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    //Init index page
    public function initIndex()
    {
        session_start();
        if(AuthController::isLogged())
            return view('home.index', ['list' => TodoList::all(), 'page' => route('home.index')]);
        return redirect(route('auth.sign-in'));
    }

    //Init add book page
    public function initAdd()
    {
        session_start();
        if(AuthController::isLogged())
            return view('home.add', ['user' => $_SESSION['user'], 'page' => route('home.add')]);
        return redirect(route('auth.sign-in'));
    }

    //Init modify book page
    public function initModify($id = false)
    {
        session_start();
        if(AuthController::isLogged())
            return view('home.modify', ['entry' => TodoList::find($id), 'page' => route('home.modify')]);
        return redirect(route('auth.sign-in'));
    }

    //Init remove book page
    public function initRemove($id = false)
    {
        session_start();
        if(AuthController::isLogged())
            return view('home.remove', ['entry' => TodoList::find($id), 'page' => route('home.remove')]);
        return redirect(route('auth.sign-in'));
    }

    //Validate add book form and add new book to database
    public function requestAdd(Request $request)
    {
        //Check auth status
        if(AuthController::isLogged() == false)
            self::generateResult(false, 'Brak autoryzacji.');

        //Check request
        if(empty($request))
            self::generateResult(false, 'Brak danych.');

        //Set json
        header('Content-Type: application/json');

        //Get post data
        $name = $request->name;

        //Validate all inputs, if incorrect return error
        if(strlen($name) < 1 || strlen($name) > 200)
            self::generateResult(false, 'Wartość w polu nazwa jest niepoprawna.');

        //Init new list entry
        $list = new TodoList();

        //Add entry to database
        $list->name = $name;
        $list->save();

        //Check and return
        if(TodoList::where('name', '=', $name)->count() == 1)
            self::generateResult(true, 'Wpis został pomyślnie dodany. <a href="' . route('home.index') . '">Przejdź do listy.</a>');
        self::generateResult(false, 'Wystąpił błąd podczas dodawania wpisu.');
    }

    //Validate modify book form and add new book to database
    public function requestModify(Request $request)
    {
        //Check auth status
        if(AuthController::isLogged() == false)
            self::generateResult(false, 'Brak autoryzacji.');

        //Check request
        if(empty($request))
            self::generateResult(false, 'Brak danych.');

        //Set json
        header('Content-Type: application/json');

        //Get post data
        $id = $request->id;

        //Validate all inputs, if incorrect return error
        if(is_nan($id) || $id == 0)
            self::generateResult(false, 'Wartość w polu id jest niepoprawna.');

        //Get entry from database
        $list = TodoList::where('id', '=', $id);

        //Check if entry exists
        if($list->count() == 0)
            self::generateResult(false, 'Nie znaleziono takiego wpisu w bazie danych.');

        //Fetch data from entry
        $list = $list->first();

        //Return entry data if no new data has been sent
        if(!isset($request->name))
            self::generateResult(true, '', array('name' => $list->name));

        //Get post data
        $name = $request->name;

        //Validate all inputs, if incorrect return error
        if(strlen($name) < 1 || strlen($name) > 200)
            self::generateResult(false, 'Wartość w polu nazwa jest niepoprawna.');

        //Check difference
        if($list->name == $name)
            self::generateResult(false, 'Treść wpisu do aktualizacji jest taka sama jak poprzednia.');

        //Update data for entry in database
        $list->name = $name;
        $list->save();

        //Check and return
        if(TodoList::where([['id', '=', $id], ['name', '=', $name]])->count() == 1)
            self::generateResult(true, 'Wpis został pomyślnie zmodyfikowany. <a href="' . route('home.index') . '">Przejdź do listy.</a>');
        self::generateResult(false, 'Wystąpił błąd podczas modyfikacji książki.');
    }

    //Validate remove book form and add new book to database
    public function requestRemove(Request $request)
    {
        //Check auth status
        if(AuthController::isLogged() == false)
            self::generateResult(false, 'Brak autoryzacji.');

        //Check request
        if(empty($request))
            self::generateResult(false, 'Brak danych.');

        //Set json
        header('Content-Type: application/json');

        //Get post data
        $id = $request->id;

        //Validate all inputs, if incorrect return error
        if(is_nan($id) || $id == 0)
            self::generateResult(false, 'Wartość w polu id jest niepoprawna.');

        //Get entry from database
        $list = TodoList::where('id', '=', $id);

        //Check if entry exists
        if($list->count() == 0)
            self::generateResult(false, 'Nie znaleziono takiego wpisu w bazie danych.');

        //Delete from database
        $list->delete();

        //Check and return
        if(TodoList::where('id', '=', $id)->count() == 0)
            self::generateResult(true, 'Wpis został pomyślnie usunięty. <a href="' . route('home.index') . '">Przejdź do listy.</a>');
        self::generateResult(false, 'Wystąpił błąd podczas usuwania wpisu.');
    }
}
