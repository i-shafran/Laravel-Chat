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

Route::get('/', 'IndexController@index');

Route::post('messages', 'IndexController@sendMessages');
Route::post('private_messages', 'IndexController@sendPrivateMessages');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
