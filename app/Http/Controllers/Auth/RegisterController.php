<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:2|confirmed',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $confirmation_code = str_random(30);

        DB::beginTransaction();
        try
        {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'confirmation_code' => $confirmation_code
            ]);
            // After creating the user send an email with the random token generated in the create method above
            // $email = new EmailVerification(new User(['email_token' => $user->email_token, 'name' => $user->name]));
            
            // Mail::to($user->email)->send($email);

//            Mail::send('email.verify', $confirmation_code, function($message) {
//                $message->to($data['email'], $data['name'])
//                    ->subject('Verify your email address');
//            });
//
//            Flash::message('Thanks for signing up! Please check your email.');

            DB::commit();
            // return back();
        
        }
        catch(Exception $e)
        {
            
            DB::rollback(); 
            return "Ocorreu um erro:";
            return back();
        }

        

        

        // return Redirect::home();
//        return "<h1>Utilizador criado. verifique o configo de confirmacao que lhe foi enviado</h1>";
        return $user;
    }

    public function confirm($confirmation_code)
    {
        if( ! $confirmation_code)
        {
            return "Erro: Codigo de confirmacao nao enviado.";
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            return "Codigo de confirmacao inválido";
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        Flash::message('You have successfully verified your account.');

        return "Confirmado com sucesso!";
        return Redirect::home();
        return Redirect::route('login_path');
    }
    
}
