<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => "\Laravel\Passport\Http\Controllers\\"], function ($router) {
    $router->post('login', [
        'as' => 'login',
        'uses' => 'AccessTokenController@issueToken',
        'middleware' => ['throttle']
    ]);
});
Route::group([], function ($router) {
    $router->post('register', [
        'as' => 'register',
        'uses' => 'AuthController@Register',
    ]);
    $router->get('/verify-user-email/{code}', [
        'as' => 'verify-user-email',
        'uses' => 'AuthController@verifyUserEmail'
    ]);
    $router->post('/verify-user-mobile', [
        'as' => 'verify-user-mobile',
        'uses' => 'AuthController@verifyUserMobile'
    ]);
    $router->post('/resend-activation-code', [
        'as' => 'resend-activation-code',
        'uses' => 'AuthController@resendActivationCode'
    ]);
});
Route::group([], function ($router) {
    $router->post('/change-email', [
        'as' => 'change-email',
        'uses' => 'UserController@changeEmail'
    ])->middleware('auth:api');
    $router->get('/verify-change-email/{code}', [
        'as' => 'verify-change-email',
        'uses' => 'UserController@verifyChangeEmail'
    ]);
    $router->post('/change-password', [
        'as' => 'change-password',
        'uses' => 'UserController@changePassword'
    ])->middleware('auth:api');
    $router->delete('/user/delete', [
        'as' => 'user-delete',
        'uses' => 'UserController@delete'
    ])->middleware('auth:api');
});
Route::group(['middleware' => ['auth:api'], 'prefix' => 'channel'], function ($router) {
    $router->put('update/{id?}', [
        'as' => 'channel-update',
        'uses' => 'ChannelController@update'
    ]);
    $router->match(['put', 'post'], '/', [
        'as' => 'channel-update',
        'uses' => 'ChannelController@uploadBanner'
    ]);
    $router->match(['put', 'post'], '/socials', [
        'as' => 'channel-socials',
        'uses' => 'ChannelController@updateSocials'
    ]);
    $router->match(['get', 'post'], '/{channel}/follow', [
        'as' => 'channel-follow',
        'uses' => 'ChannelController@follow'
    ]);
    $router->match(['get', 'post'], '/{channel}/unfollow', [
        'as' => 'channel-unFollow',
        'uses' => 'ChannelController@unFollow'
    ]);
    $router->get('/followers', [
        'as' => 'channel-followers',
        'uses' => 'ChannelController@followers'
    ]);
    $router->get('/followings', [
        'as' => 'channel-followings',
        'uses' => 'ChannelController@followings'
    ]);
    $router->get('/statistics', [
        'as' => 'channel-statistics',
        'uses' => 'ChannelController@statistics'
    ]);
});
Route::group(['prefix' => 'video'], function ($router) {
    $router->group(['middleware' => 'auth:api'], function ($router) {
        $router->post('upload', [
            'as' => 'upload-video',
            'uses' => 'VideoController@upload'
        ]);
        $router->post('upload/banner', [
            'as' => 'upload-video-banner',
            'uses' => 'VideoController@uploadBanner'
        ]);
        $router->post('', [
            'as' => 'create-video',
            'uses' => 'VideoController@createVideo'
        ]);
        $router->post('/{video}/update', [
            'as' => 'update-video',
            'uses' => 'VideoController@update'
        ]);
        $router->put('/{video}/state', [
            'as' => 'change-state-video',
            'uses' => 'VideoController@changeState'
        ]);
        $router->post('/{video}/republish', [
            'as' => 'republish-video',
            'uses' => 'VideoController@republish'
        ]);
        $router->get('/liked', [
            'as' => 'video-liked-list',
            'uses' => 'VideoController@likedList'
        ]);
        $router->get('/{video}/statistics', [
            'as' => 'video-statistics',
            'uses' => 'VideoController@statistics'
        ]);
        $router->delete('/{video}/delete', [
            'as' => 'delete-delete',
            'uses' => 'VideoController@delete'
        ]);
    });
    $router->post('/{video}/like', [
        'as' => 'like-video',
        'uses' => 'VideoController@like'
    ]);
    $router->get('/list', [
        'as' => 'video-list',
        'uses' => 'VideoController@list'
    ]);
    $router->get('/{video}', [
        'as' => 'show-video',
        'uses' => 'VideoController@show'
    ]);
});
Route::group(['middleware' => ['auth:api'], 'prefix' => 'category'], function ($router) {
    $router->get('', [
        'as' => 'category-all',
        'uses' => 'CategoryController@getAllCategories'
    ]);
    $router->get('/my', [
        'as' => 'category-my',
        'uses' => 'CategoryController@getMyCategories'
    ]);
    $router->post('/banner', [
        'as' => 'category-banner-upload',
        'uses' => 'CategoryController@uploadBanner'
    ]);
    $router->post('/create', [
        'as' => 'category-create',
        'uses' => 'CategoryController@create'
    ]);
});
Route::group(['middleware' => ['auth:api'], 'prefix' => 'playlist'], function ($router) {
    $router->get('', [
        'as' => 'playlist-all',
        'uses' => 'PlaylistController@getAllPlaylists'
    ]);
    $router->get('/my', [
        'as' => 'playlist-my',
        'uses' => 'PlaylistController@getMyPlaylists'
    ]);
    $router->get('/show/{playlist}', [
        'as' => 'playlist-show',
        'uses' => 'PlaylistController@show'
    ]);
    $router->post('/create', [
        'as' => 'playlist-create',
        'uses' => 'PlaylistController@create'
    ]);
    $router->match(['put', 'patch'], '/sort/{playlist}', [
        'as' => 'sort-playlist-videos',
        'uses' => 'PlaylistController@sortVideos'
    ]);
    $router->match(['put', 'patch'], '/add/{playlist}/{video}', [
        'as' => 'add-video-to-playlist',
        'uses' => 'PlaylistController@addVideo'
    ]);
});
Route::group(['middleware' => ['auth:api'], 'prefix' => 'tag'], function ($router) {
    $router->get('', [
        'as' => 'tag-all',
        'uses' => 'TagController@getAllTags'
    ]);
    $router->post('', [
        'as' => 'tag-create',
        'uses' => 'TagController@createTag'
    ]);
});
Route::group(['middleware' => ['auth:api'], 'prefix' => 'comment'], function ($router) {
    $router->get('', [
        'as' => 'comment-all',
        'uses' => 'CommentController@list'
    ]);
    $router->post('', [
        'as' => 'comment-create',
        'uses' => 'CommentController@create'
    ]);
    $router->match(['put', 'post'], '/{comment}/state', [
        'as' => 'comment-change-state',
        'uses' => 'CommentController@changeState'
    ]);
    $router->delete('/{comment}/delete', [
        'as' => 'comment-delete',
        'uses' => 'CommentController@delete'
    ]);
});





