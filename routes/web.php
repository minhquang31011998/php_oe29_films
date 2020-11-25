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

Route::group([
    'namespace' => 'Frontend',
    'as' => 'frontend.',
    'middleware' => 'language',
], function () {
    Route::get('/', 'MovieController@index')->name('home');
    Route::get('/home', 'MovieController@index')->name('home');
    Route::get('/filter', 'MovieController@filter')->name('filter');
    Route::get('/catalog/{key}/{slug}', 'MovieController@listMovie')->name('catalog');
    Route::get('/movie/{movie}/{prioritize}/{video?}', 'MovieController@watchMovie')->name('watchMovie');
    Route::get('/search', 'MovieController@search')->name('searchMovie');
    Route::get('/search/{slug}', 'MovieController@searchTag')->name('searchTag');
    Route::get('language/{language}', 'HomeController@language')->name('language');
    Route::post('rate', 'RateController@rate')->name('rate');
    Route::group([
        'as' => 'comment.',
        'middleware' => 'auth',
        'prefix' => 'comment',
    ], function () {
        Route::post('store', 'CommentController@store')->name('store');
        Route::get('data', 'CommentController@getData')->name('comment');
        Route::get('mark-noti', 'CommentController@markReadNoti')->name('markNoti');
        Route::get('replyData', 'CommentController@getReplyData')->name('replyComment');
        Route::delete('delete/{id}', 'CommentController@destroy')->name('destroy');
    });
});
Route::group([
    'namespace' => 'Backend',
    'middleware' => ['language', 'auth'],
    'prefix' => 'request',
], function () {
    Route::post('store', 'RequestController@store')->name('sendRequest');
    Route::group([
        'middleware' => 'checkAdmin',
        'as' => 'request.',
    ], function () {
        Route::get('/', 'RequestController@index')->name('index');
        Route::get('data', 'RequestController@getData')->name('data');
        Route::delete('delete/{id}', 'RequestController@destroy')->name('destroy');
    });
});

Route::group([
    'namespace'=>'Backend',
    'prefix'=>'admin',
    'as'=>'backend.',
    'middleware' => ['language', 'auth', 'checkAdmin'],
], function () {
    Route::get('/home', 'DashboardController@home')->name('home');
    Route::get('/chart', 'ChartController@index')->name('chart');
    Route::group([
        'prefix'=>'movie',
        'as'=>'movie.'
    ], function () {
        Route::get('/', 'MovieController@index')->name('index');
        Route::get('create', 'MovieController@create')->name('create');
        Route::post('store', 'MovieController@store')->name('store');
        Route::get('{id}/edit', 'MovieController@edit')->name('edit');
        Route::put('update/{id}', 'MovieController@update')->name('update');
        Route::get('types', 'MovieController@types')->name('types');
        Route::delete('delete/{id}', 'MovieController@destroy')->name('destroy');
        Route::get('data', 'MovieController@getData')->name('data');
        Route::get('moviePlaylists/{id}', 'MovieController@getMoviePlaylists')->name('moviePlaylists');
        Route::get('choosePlaylists/{id}', 'MovieController@getPlaylists')->name('choosePlaylists');
        Route::get('chooseVideos/{id}', 'MovieController@getVideos')->name('chooseVideos');
        Route::post('updatePlaylist/{id}', 'MovieController@updatePlaylist')->name('updatePlaylist');
        Route::post('updateVideo/{id}', 'MovieController@updateVideo')->name('updateVideo');
        Route::get('{id}/nominations', 'MovieController@nominations')->name('nominations');
        Route::get('tags', 'MovieController@getTags')->name('getTags');
    });
    Route::group([
        'prefix'=>'playlist',
        'as'=>'playlist.'
    ], function () {
        Route::get('/', 'PlaylistController@index')->name('index');
        Route::get('create', 'PlaylistController@create')->name('create');
        Route::post('store', 'PlaylistController@store')->name('store');
        Route::get('{id}/edit', 'PlaylistController@edit')->name('edit');
        Route::get('show/{id}', 'PlaylistController@show')->name('show');
        Route::put('update/{id}', 'PlaylistController@update')->name('update');
        Route::post('changeStatus/{id}', 'PlaylistController@changeStatus')->name('changeStatus');
        Route::delete('delete/{id}', 'PlaylistController@destroy')->name('destroy');
        Route::post('detach/{id}', 'PlaylistController@detach')->name('detach');
        Route::get('data', 'PlaylistController@getData')->name('data');
        Route::get('playlistVideos/{id}', 'PlaylistController@getPlaylistVideos')->name('playlistVideos');
        Route::get('videos/{id}', 'PlaylistController@getVideos')->name('videos');
        Route::post('chooseVideo/{id}', 'PlaylistController@chooseVideo')->name('chooseVideo');
    });
    Route::group([
        'prefix'=>'channel',
        'as'=>'channel.'
    ],function(){
        Route::get('/', 'ChannelController@index')->name('index');
        Route::get('create', 'ChannelController@create')->name('create');
        Route::post('store', 'ChannelController@store')->name('store');
        Route::get('{id}/edit', 'ChannelController@edit')->name('edit');
        Route::put('update/{id}', 'ChannelController@update')->name('update');
        Route::post('changeStatus/{id}', 'ChannelController@changeStatus')->name('changeStatus');
        Route::delete('delete/{id}', 'ChannelController@destroy')->name('destroy');
        Route::get('data', 'ChannelController@getData')->name('data');
    });
    Route::group([
        'prefix'=>'source',
        'as'=>'source.'
    ],function(){
        Route::get('create', 'SourceController@create')->name('create');
        Route::post('store', 'SourceController@store')->name('store');
        Route::get('{id}/edit', 'SourceController@edit')->name('edit');
        Route::post('update/{id}', 'SourceController@update')->name('update');
        Route::post('delete/{id}', 'SourceController@destroy')->name('destroy');
    });
    Route::group([
        'prefix'=>'video',
        'as'=>'video.'
    ],function(){
        Route::get('/', 'VideoController@index')->name('index');
        Route::get('create', 'VideoController@create')->name('create');
        Route::post('store', 'VideoController@store')->name('store');
        Route::get('{id}/edit/', 'VideoController@edit')->name('edit');
        Route::put('update/{id}', 'VideoController@update')->name('update');
        Route::post('changeStatus/{id}', 'VideoController@changeStatus')->name('changeStatus');
        Route::delete('delete/{id}', 'VideoController@destroy')->name('destroy');
        Route::post('detach/{id}', 'VideoController@detach')->name('detach');
        Route::get('data', 'VideoController@getData')->name('data');
        Route::get('source', 'VideoController@getSources')->name('sources');
    });
    Route::group([
        'prefix'=>'type',
        'as'=>'type.'
    ],function(){
        Route::get('/', 'TypeController@index')->name('index');
        Route::get('create', 'TypeController@create')->name('create');
        Route::post('store', 'TypeController@store')->name('store');
        Route::get('{id}/edit', 'TypeController@edit')->name('edit');
        Route::get('show/{id}', 'TypeController@show')->name('show');
        Route::put('update/{id}', 'TypeController@update')->name('update');
        Route::delete('delete/{id}', 'TypeController@destroy')->name('destroy');
        Route::get('data', 'TypeController@getData')->name('data');
    });
    Route::group([
        'prefix'=>'user',
        'as'=>'user.'
    ],function(){
        Route::get('/', 'UserController@index')->name('index');
        Route::get('{id}/edit', 'UserController@edit')->name('edit');
        Route::put('update/{id}', 'UserController@update')->name('update');
        Route::put('changePassword/{id}', 'UserController@changePassword')->name('changePassword');
        Route::post('changeStatus/{id}', 'UserController@changeStatus')->name('changeStatus');
        Route::delete('delete/{id}', 'UserController@destroy')->name('destroy');
        Route::get('data', 'UserController@getData')->name('data');
    });
});

Route::group([
    'middleware' => 'language',
    'namespace' => 'Auth',
], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login')->name('loginProcess');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register')->name('registerProcess');
});
Route::group([
    'middleware' => 'language',
    'namespace' => 'Backend',
], function () {
    Route::get('forgot-password', 'UserController@getFormForgotPassword')->name('forgotPassword');
    Route::post('send-email-forgot-password', 'UserController@sendEmailForgotPassword')->name('sendMailForgotPassword');
});
