<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
     * Check if user is logged
     */
    public static function is_logged()
    {
        session_start();
        if(isset($_SESSION['logged']))
            if($_SESSION['logged'] !== true)
                return false;
            else
                return true;
        return false;
    }

    /*
     * Generate result for request
     */
    private function generate_result($success, $message)
    {
        $result = array();
        $result['result']['success'] = $success;
        $result['result']['message'] = $message;

        exit(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    /*
     * Init sign-in page
     */
    public function init_signin()
    {
        if(!$this->is_logged())
            return view('auth.sign-in');
        return redirect('/');
    }

    /*
     * Init sign-up page
     */
    public function init_signup()
    {
        if(!$this->is_logged())
            return view('auth.sign-up');
        return redirect('/');
    }

    /*
     * Request logout
     */
    public function request_logout()
    {
        if($this->is_logged())
            session_destroy();

        return redirect('/');

    }

    /*
     * Validate sign-in form and add new user to database
     */
    public function request_signin(Request $request)
    {
        if(!empty($request))
        {
            header('Content-Type: application/json');

            /*
             * Get post data
             */
            $email = strtolower($request->email);
            $password = $request->password;

            /*
             * Validate all inputs, if incorrect return errors
             */
            if(strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                if(strlen($password) > 0)
                {
                    $user = User::where('email', '=', $email);
                    if($user->count() == 1)
                    {
                        $user = $user->first();
                        if(Hash::check($password, $user->password))
                        {
                            session_start();

                            $_SESSION['logged'] = true;
                            $_SESSION['user'] = $user;

                            $this->generate_result(true, 'Zostałeś pomyślnie zalogowany. Za chwile zostaniesz przekierowany na stronę główną.');
                        }
                        else
                            $this->generate_result(false, 'Podano błędny adres e-mail i/lub hasło. Spróbuj ponownie.');
                    }
                    else
                        $this->generate_result(false, 'Podano błędny adres e-mail i/lub hasło. Spróbuj ponownie.');
                }
                else
                    $this->generate_result(false, 'Wartość w polu hasło jest niepoprawna.');
            }
            else
                $this->generate_result(false, 'Wartość w polu e-mail jest niepoprawna.');
        }
    }

    /*
     * Validate sign-up form and add new user to database
     */
    public function request_signup(Request $request)
    {
        $user = new User();

        if(!empty($request))
        {
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
            if(strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                if(strlen($name) >= 3 && strlen($name) <= 15 && preg_match('/^[a-zA-ZĘÓĄŚŁŻŹĆŃęóąśłżźćń]{3,}$/', $name))
                {
                    if(strlen($password) >= 6 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[0-9]/', $password))
                    {
                        if($confirm_password == $password)
                        {
                            if(User::where('email', '=', $email)->count() == 0)
                            {
                                $user->email = $email;
                                $user->name = $name;
                                $user->password = bcrypt($password);

                                $user->save();

                                if(User::where('email', '=', $email)->count() == 1)
                                    $this->generate_result(true, 'Zostałeś pomyślnie zarejestrowany. Za chwilę zostaniesz przekierowany na stronę logowania.');
                                else
                                    $this->generate_result(false, 'Wystąpił błąd podczas rejestracji.');
                            }
                            else
                                $this->generate_result(false, 'Użytkownik o podanym adresie e-mail jest już zarejestrowany.');
                        }
                        else
                            $this->generate_result(false, 'Podane hasła nie są takie same.');
                    }
                    else
                        $this->generate_result(false, 'Hasło musi składać się z minimum 6 liter w tym jedna duża i jedna mała litera oraz jedna cyfra.');
                }
                else
                    $this->generate_result(false, 'Wartość w polu imię jest niepoprawna.');
            }
            else
                $this->generate_result(false, 'Wartość w polu e-mail jest niepoprawna.');
        }
    }
}
