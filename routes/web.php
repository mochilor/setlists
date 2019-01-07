<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // Login
    $router->post('auth/login',  ['uses' => 'AuthController@login']);

    $router->group(['middleware' => 'auth'], function () use ($router) {
        // Song routes
        $router->group(['prefix' => 'song'], function () use ($router) {
            $router->post('',  ['uses' => 'SongController@createSong']);
            $router->patch('{id}',  ['uses' => 'SongController@updateSong']);
            $router->delete('{id}',  ['uses' => 'SongController@deleteSong']);
            $router->delete('{id}/force',  ['uses' => 'SongController@forceDeleteSong']);
            $router->get('{id}',  ['uses' => 'SongController@getSong']);
        });
        $router->get('songs',  ['uses' => 'SongController@getSongs']);
        //$router->get('songs/title/{title}',  ['uses' => 'SongController@getSongsByTitle']);

        // Setlist routes
        $router->group(['prefix' => 'setlist'], function () use ($router) {
            $router->post('',  ['uses' => 'SetlistController@createSetlist']);
            $router->patch('{id}',  ['uses' => 'SetlistController@updateSetlist']);
            $router->delete('{id}',  ['uses' => 'SetlistController@deleteSetlist']);
            $router->get('{id}',  ['uses' => 'SetlistController@getSetlist']);
        });
        $router->get('setlists',  ['uses' => 'SetlistController@getSetlists']);
    });
});
