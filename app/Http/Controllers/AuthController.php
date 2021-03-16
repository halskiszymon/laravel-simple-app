<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /*
     * Check if user is logged
     */
    public static function isLogged()
    {
        if(isset($_SESSION['logged']))
            if($_SESSION['logged'] != true)
                return false;
            else
                return true;
        return false;
    }

    /*
     * Generate result for request
     */
    private function generateResult($success, $message)
    {
        $result = array();
        $result['result']['success'] = $success;
        $result['result']['message'] = $message;

        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    /*
     * Init sign-in page
     */
    public function initSignin()
    {
        session_start();
        if(!self::isLogged())
            return view('auth.sign-in', ['page' => route('auth.sign-in')]);
        return redirect(route('home.index'));
    }

    /*
     * Init sign-up page
     */
    public function initSignup()
    {
        session_start();
        if(!self::isLogged())
            return view('auth.sign-up', ['page' => route('auth.sign-up')]);
        return redirect(route('home.index'));
    }

    /*
     * Request logout
     */
    public function requestLogout()
    {
        if(self::isLogged())
            session_destroy();
        return redirect(route('auth.sign-in'));
    }

    /*
 * Validate sign-up form and add new user to database
 */
    public function requestSignup(Request $request)
    {
        /*
         * Check auth status
         */
        if(self::isLogged())
            self::generateResult(false, 'Jesteś już zalogowany.');
        /*
         * Check request
         */
        if(empty($request))
            self::generateResult(false, 'Brak danych.');

        /*
         * Set json
         */
        header('Content-Type: application/json');

        /*
         * Get post data
         */
        $email = strtolower($request->email);
        $name = ucfirst(strtolower($request->name));
        $password = $request->password;
        $confirm_password = $request->confirm_password;

        /*
         * Validate all inputs, if incorrect return error
         */
        if(strlen($email) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL) != true)
            self::generateResult(false, 'Wartość w polu e-mail jest niepoprawna.');

        if((strlen($name) < 3 || strlen($name) > 15) || preg_match('/^[a-zA-ZĘÓĄŚŁŻŹĆŃęóąśłżźćń]{3,}$/', $name) != true)
            self::generateResult(false, 'Wartość w polu imię jest niepoprawna.');

        if(strlen($password) < 6 || preg_match('/[A-Z]/', $password) != true || preg_match('/[a-z]/', $password) != true || preg_match('/[0-9]/', $password) != true)
            self::generateResult(false, 'Hasło musi składać się z minimum 6 liter w tym jedna duża i jedna mała litera oraz jedna cyfra.');

        if($confirm_password != $password)
            self::generateResult(false, 'Podane hasła nie są takie same.');

        if(User::where('email', '=', $email)->count() != 0)
            self::generateResult(false, 'Użytkownik o podanym adresie e-mail jest już zarejestrowany.');

        /*
         * Init new user
         */
        $user = new User();

        /*
         * Add user to database
         */
        $user->email = $email;
        $user->name = $name;
        $user->password = bcrypt($password);
        $user->save();

        /*
         * Check and return
         */
        if(User::where('email', '=', $email)->count() == 1)
            self::generateResult(true, 'Zostałeś pomyślnie zarejestrowany. Za chwilę zostaniesz przekierowany na stronę logowania.');
        self::generateResult(false, 'Wystąpił błąd podczas rejestracji.');
    }

    /*
     * Validate sign-in form and add new user to database
     */
    public function requestSignin(Request $request)
    {
        /*
         * Check auth status
         */
        if(self::isLogged())
            self::generateResult(false, 'Jesteś już zalogowany.');

        /*
         * Check request
         */
        if(empty($request))
            self::generateResult(false, 'Brak danych.');

        /*
         * Set json
         */
        header('Content-Type: application/json');

        /*
         * Get post data
         */
        $email = strtolower($request->email);
        $password = $request->password;

        /*
         * Validate all inputs, if incorrect return errors
         */
        if(strlen($email) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL) != true)
            self::generateResult(false, 'Wartość w polu e-mail jest niepoprawna.');

        if(strlen($password) == 0)
            self::generateResult(false, 'Wartość w polu hasło jest niepoprawna.');

        $user = User::where('email', '=', $email);
        if($user->count() != 1)
            self::generateResult(false, 'Podano błędny adres e-mail i/lub hasło. Spróbuj ponownie.');

        $user = $user->first();
        if(Hash::check($password, $user->password) == false)
            self::generateResult(false, 'Podano błędny adres e-mail i/lub hasło. Spróbuj ponownie.');

        /*
         * Store session data and return success
         */
        session_start();
        $_SESSION['logged'] = true;
        $_SESSION['user'] = $user;

        self::generateResult(true, 'Zostałeś pomyślnie zalogowany. Za chwile zostaniesz przekierowany na stronę główną.');
    }
}
