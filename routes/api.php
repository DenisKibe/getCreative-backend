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
Route::group(['prefix'=>'v1',], function(){
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/register', 'Auth\RegisterController@register');
});
Route::group(['prefix'=>'v1','middleware'=>['auth:api','cors'],], function(){
    Route::post('/logout', 'Auth\LoginController@logout');
    Route::get('/articles', 'ArticlesController@index');
    Route::post('/postArticle','ArticlesController@createPost');
    Route::put('/edit/{blog_identifier}','ArticlesController@edit');
    Route::delete('/deletesoft/{blog_identifier}','ArticlesController@softDelete');
    Route::get('/viewdeleted','ArticlesController@getDeleted');
    Route::post('/retrivedel','ArticlesController@restoreDeleted');
    Route::delete('/permdelete','ArticlesController@permanentDelete');
    Route::post('/comment','CommentsController@index');
    Route::post('/comments','CommentsController@commentsCreate');
    Route::post('/like','LikeController@index');
    Route::post('/likes','LikeController@likeIt');
    Route::post('/followednumber','FollowersController@followed');
    Route::post('/follow','FollowersController@follow');
    Route::post('/followedpeople','FollowersController@userFollowed');
    Route::post('/followersnumber','FollowersController@followers');
    Route::post('/followerspeople','FollowersController@userFollowers');
    Route::post('/userInfo','UserController@userInfo');
    Route::post('/uploadDp','UserController@uploadPic');
    Route::post('/getAvatar','UserController@getDp');
});

