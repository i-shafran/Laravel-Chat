<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('add_page/{external_id}', 'PageController@addPage');


/*
 * 	Дока по Route::resource
 * 
	Verb 		URI 				Action

	GET 		/photos 			index
	GET 		/photos/create 		create
	POST 		/photos 			store
	GET 		/photos/{photo} 	show
	GET 		/photos/{photo}/	edit
	PUT/PATCH 	/photos/{photo} 	update
	DELETE 		/photos/{photo} 	destroy
*/