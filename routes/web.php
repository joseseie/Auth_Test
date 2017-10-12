<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::user())
    {
        $user = Auth::user();
    } else
    {
        $user = \App\User::find(1);
    }

//    $user->notifications()->delete();

//    $user->notify(new App\Notifications\PostNotification());

//    $user = \App\User::get();
//    Notification::send($user, new App\Notifications\PostNotification());

//    Notification::send($user,new App\Notifications\RegisterNotification($user));
    return view('welcome');
});

//Novo
Route::get('/verifyemail/{token}','Auth\RegisterController@verify');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'RegistrationController@confirm'
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
