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

    // Song routes
    $router->group(['prefix' => 'song'], function () use ($router) {
        $router->post('',  ['uses' => 'SongController@createSong']);
        $router->patch('{id}',  ['uses' => 'SongController@updateSong']);
        $router->delete('{id}',  ['uses' => 'SongController@deleteSong']);
    });

    // Setlist routes
    $router->group(['prefix' => 'setlist'], function () use ($router) {
        $router->post('',  ['uses' => 'SetlistController@createSetlist']);
        $router->patch('{id}',  ['uses' => 'SetlistController@updateSetlist']);
        $router->delete('{id}',  ['uses' => 'SetlistController@deleteSetlist']);
    });


//    $router->get('authors/{id}', ['uses' => 'AuthorController@showOneAuthor']);
//
//    $router->post('authors', ['uses' => 'AuthorController@create']);
//
//    $router->delete('authors/{id}', ['uses' => 'AuthorController@delete']);
//
//    $router->put('authors/{id}', ['uses' => 'AuthorController@update']);
});
