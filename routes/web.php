<?php

use App\Http\Middleware\AdminCheck;
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


Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
    Route::post('create_tag', 'TagController@store');
    Route::post('edit_tag', 'TagController@update');
    Route::post('delete_tag', 'TagController@destroy');
    Route::get('get_tags', 'TagController@index');

    // categoy controller starts
    Route::post('upload', 'CategoryController@storeImage');
    Route::post('delete_image', 'CategoryController@delete_image');
    Route::post('create_category', 'CategoryController@store');
    Route::get('get_category', 'CategoryController@index');
    Route::post('delete_category', 'CategoryController@destroy');
    Route::post('edit_category', 'CategoryController@update');
    // categoy controller ends

    // admin controller starts

    Route::post('upload/userImage', 'AdminController@storeUserImage');
    Route::post('upload/create_user', 'AdminController@createUser');
    Route::post('user/delete_image', 'AdminController@delete_image');
    Route::get('get_user', 'AdminController@getUser');
    Route::post('edit_user', 'AdminController@update');
    Route::post('edit_user/password', 'AdminController@changePassword');

    // admin login starts


    // admin login ends
    // admin role management starts
    Route::post('create_role', 'AdminController@createRole');
    Route::get('get_role', 'AdminController@getRole');
    Route::get('get_role_id', 'AdminController@getRoleId');
    Route::post('edit_role_name', 'AdminController@editRole');
    Route::post('assign_roles', 'AdminController@assign_roles');
    // admin role management ends

    // admin controller ends
});
Route::post('app/admin/login', 'AdminController@login');






// user dashboard starts

Route::get('/', 'AdminController@index');
Route::get('/logout', 'AdminController@logout');
Route::any('{slug}', 'AdminController@index');


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', 'testController@welcome');
// Route::any('{slug}', function(){
//     return view('welcome');
// });

// user dashboard ends
