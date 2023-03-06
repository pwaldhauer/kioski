<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', 'App\Http\Controllers\ItemController@index')->name('home');
Route::get('/item/{id}', 'App\Http\Controllers\ItemController@index')->name('items.show');

Route::get('/item/{id}', 'App\Http\Controllers\ItemController@index')->name('items.show');


Route::get('/manage', 'App\Http\Controllers\ManageController@index')->name('manage');

Route::get('/manage/{id}', 'App\Http\Controllers\ManageController@edit')->name('edit');

Route::delete('/manage/{id}', 'App\Http\Controllers\ManageController@delete')->name('manage.delete');

Route::post('/manage', 'App\Http\Controllers\ManageController@store')->name('manage.store');

Route::post('/manage/create', 'App\Http\Controllers\ManageController@create')->name('manage.create');
