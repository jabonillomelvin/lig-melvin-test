<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::get('posts', 'PostsController@index');
Route::get('posts/{post}', 'PostsController@detail');
Route::get('posts/{post}/comments', 'CommentsController@index');

Route::group([
                 'middleware' => 'auth:api'
             ], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::post('posts', 'PostsController@create');
    Route::patch('posts/{post}', 'PostsController@patch');
    Route::delete('posts/{post}', 'PostsController@delete');

    Route::post('/posts/{post}/comments', 'CommentsController@create');
    Route::patch('posts/{post}/comments/{id}', 'CommentsController@patch');
    Route::delete('posts/{post}/comments/{id}', 'CommentsController@delete');
});
