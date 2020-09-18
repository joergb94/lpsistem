<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});

Auth::routes();
Route::group(['middleware'=>['auth']], function(){
    
Route::get('logout', 'Auth\LoginController@redirectUrlLogout');
Route::get('/home', 'HomeController@index')->name('home');
//user
Route::get('/users', 'UserController@index');
Route::post('/users/add', 'UserController@store');
Route::post('/users/update', 'UserController@update');
Route::post('/users/password', 'UserController@change_password');
Route::post('/users/change_status','UserController@change_status');
Route::post('/users/deleteOrResotore','UserController@deleteOrResotore');

//profile
Route::get('/profile', 'ProfileController@index');
Route::post('/profile/update', 'ProfileController@update');
Route::post('/profile/password', 'ProfileController@change_password');

//ManagmentTickets
Route::get('/tickets', 'ManagementTicketsController@index');
Route::post('/tickets/add', 'ManagementTicketsController@store');
Route::get('/tickets/detail', 'ManagementTicketsController@detail');
Route::post('/tickets/deleteOrResotore','ManagementTicketsController@deleteOrResotore');

//UserTickets
Route::get('/my-tickets', 'UserTicketsController@index');
Route::post('/my-tickets/add', 'UserTicketsController@store');
Route::get('/my-tickets/detail', 'UserTicketsController@detail');
Route::post('/my-tickets/deleteOrResotore','UserTicketsController@deleteOrResotore');

//UserTickets
Route::get('/deposits', 'DepositsController@index');
//Route::post('/deposits/add', 'DepositsController@store');
//Route::get('/deposits/detail', 'DepositsController@detail');
//Route::post('/deposits/deleteOrResotore','DepositsController@deleteOrResotore');
//Route::post('/deposits/update', 'DepositsController@update');


//GeneralServies 
Route::get('/coins', 'GeneralServiceController@get_coins');


});