<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    /*
     * Generate result for request
     */
    private function generate_result($success, $message, $data = false)
    {
        $result = array();
        $result['result']['success'] = $success;
        $result['result']['message'] = $message;
        if($data !== false)
            $result['result']['data'] = $data;

        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    /*
     * Init index page
     */
    public function init_index()
    {
        if(AuthController::is_logged())
            return view('home.index', ['list' => TodoList::all()]);

        return redirect('/sign-in');
    }

    /*
     * Init add book page
     */
    public function init_add()
    {
        if(AuthController::is_logged())
            return view('home.add', ['user' => $_SESSION['user']]);
        return redirect('/sign-in');
    }

    /*
 * Init modify book page
 */
    public function init_modify()
    {
        if(AuthController::is_logged())
            return view('home.modify');
        return redirect('/sign-in');
    }

    /*
     * Init remove book page
     */
    public function init_remove()
    {
        if(AuthController::is_logged())
            return view('home.remove');
        return redirect('/sign-in');
    }

    /*
     * Validate add book form and add new book to database
     */
    public function request_add(Request $request)
    {
        $list = new TodoList();

        if(!empty($request))
        {
            header('Content-Type: application/json');

            /*
             * Get post data
             */
            $name = $request->name;

            /*
             * Validate all inputs, if incorrect return error
             */
            if(strlen($name) >= 3 && strlen($name) <= 200)
            {
                $list->name = $name;

                $list->save();

                if(TodoList::where('name', '=', $name)->count() == 1)
                    $this->generate_result(true, 'Wpis został pomyślnie dodany. <a href="' . route('home.index') . '">Przejdź do listy.</a>');
                else
                    $this->generate_result(false, 'Wystąpił błąd podczas dodawania wpisu.');
            }
            else
                $this->generate_result(false, 'Wartość w polu nazwa jest niepoprawna.');
        }
    }

    /*
     * Validate modify book form and add new book to database
     */
    public function request_modify(Request $request)
    {
        if(!empty($request))
        {
            header('Content-Type: application/json');

            /*
             * Get post data
             */
            $id = $request->id;

            /*
             * Validate all inputs, if incorrect return error
             */
            if(!is_nan($id) && $id > 0)
            {
                $list = TodoList::where('id', '=', $id);
                if($list->count() == 1)
                {
                    if(!isset($request->name))
                    {
                        $list = $list->first();
                        $this->generate_result(true, '', array('name' => $list->name));
                    }
                    else if(isset($request->name))
                    {
                        /*
                         * Get post data
                         */
                        $name = $request->name;

                        /*
                         * Validate all inputs, if incorrect return error
                         */
                        if(strlen($name) >= 3 && strlen($name) <= 70)
                        {
                            $list->update(['name' => $name]);

                            if(TodoList::where([['id', '=', $id], ['name', '=', $name]])->count() == 1)
                                $this->generate_result(true, 'Wpis został pomyślnie zmodyfikowany. <a href="' . route('home.index') . '">Przejdź do listy.</a>');
                            else
                                $this->generate_result(false, 'Wystąpił błąd podczas modyfikacji książki.');
                        }
                        else
                            $this->generate_result(false, 'Wartość w polu nazwa jest niepoprawna.');
                    }
                }
                else
                    $this->generate_result(false, 'Nie znaleziono takiego wpisu w bazie danych.');
            }
            else
                $this->generate_result(false, 'Wartość w polu id jest niepoprawna.');
        }
    }

    /*
     * Validate remove book form and add new book to database
     */
    public function request_remove(Request $request)
    {
        if(!empty($request))
        {
            header('Content-Type: application/json');

            /*
             * Get post data
             */
            $id = $request->id;

            /*
             * Validate all inputs, if incorrect return error
             */
            if(!is_nan($id) && $id > 0)
            {
                $list = TodoList::where('id', '=', $id);
                if($list->count() == 1)
                {
                    $list->delete();
                    if(TodoList::where('id', '=', $id)->count() == 0)
                        $this->generate_result(true, 'Wpis został pomyślnie usunięty. <a href="' . route('home.index') . '">Przejdź do listy.</a>');
                    else
                        $this->generate_result(false, 'Wystąpił błąd podczas usuwania wpisu.');
                }
                else
                    $this->generate_result(false, 'Nie znaleziono takiego wpisu w bazie danych.');
            }
            else
                $this->generate_result(false, 'Wartość w polu id jest niepoprawna.');
        }
    }
}
