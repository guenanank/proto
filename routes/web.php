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
Auth::routes();
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard.home');
    });

    Route::get('profiles', 'ProfilesController@index')->name('profiles.index');

    Route::resources([
      'groups' => 'GroupsController',
      'media' => 'MediaController',
      'channels' => 'ChannelsController',
      'topics' => 'TopicsController',
    ]);

    Route::post('media/dataTable', 'MediaController@dataTable');
    Route::post('channels/dataTable', 'ChannelsController@dataTable');
    Route::post('topics/dataTable', 'TopicsController@dataTable');

    Route::prefix('galleries')->group(function () {
        Route::get('{type}', 'GalleriesController@index')->name('galleries');
        Route::get('{type}/create', 'GalleriesController@create')->name('galleries.create');
        Route::get('{type}/{gallery}', 'GalleriesController@show')->name('galleries.show');
        Route::post('{type}', 'GalleriesController@store')->name('galleries.store');
        Route::get('{type}/{gallery}/edit', 'GalleriesController@edit')->name('galleries.edit');
        Route::match(['PUT','PATCH'], '{type}/{gallery}', 'GalleriesController@update')->name('galleries.update');
        Route::delete('{type}/{gallery}', 'GalleriesController@destroy')->name('galleries.destroy');
    });
// });

Route::get('/home', 'HomeController@index')->name('home');
