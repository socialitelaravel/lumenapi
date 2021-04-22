<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('password-reset', 'UserController@passwordReset');

$router->group(['prefix'=>'api'],function() use($router){
    $router->post('login','UserController@login');
    $router->get('verify-email/{token}', 'UserController@verifyUser');
    $router->get('updatePassword', 'UserController@updatePassword');
    $router->post('forgetPassword', 'UserController@forgetPassword');
    $router->get('password-reset/{id}', 'UserController@passwordReset');
    $router->post('password-change', 'UserController@changePassword');

    $router->group(['middleware'=>'APIToken'],function() use($router){
        $router->get('users','UserController@allUser');
        $router->post('storeUser','UserController@storeUser');
        $router->get('user_view/{id}','UserController@viewUser');
        $router->post('user_update/{id}','UserController@updateUser');
        $router->post('logout','UserController@logout');
        $router->delete('user_delete/{id}','UserController@deleteUser');

      });

});