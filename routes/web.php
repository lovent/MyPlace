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
Route::get('/login', 'HomeController@index')->name('home')->middleware('CheckLogout');
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::group(['prefix' => 'admin', 'middleware' => 'CheckAdmin'], function () {
    Route::get('/', 'AdminController@index')->name('adminPage');
    Route::resource('users', 'UserController')->middleware('CheckAdmin');
    Route::resource('category', 'CategoryController');
    Route::resource('city', 'CityController');
    Route::resource('district', 'DistrictController');
    Route::resource('place', 'PlaceController');
    Route::get('listplace', 'PlaceController@listPlace')->name('listplace');
    Route::get('previewplade/{id}', 'PlaceController@previewPlade')->name('previewplade');
    Route::post('deleteplace', 'PlaceController@deletePlace')->name('deleteplace');
    Route::post('appoveplace', 'PlaceController@appovePlace')->name('appoveplace');
    Route::resource('reports', 'ReportController');
    Route::post('approve', 'ReportController@approve')->name('approve');
});
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'member'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/search', 'SearchController@searchKey')->name('search');
    Route::resource('reviews', 'ReviewController');
    Route::resource('collection', 'CollectionController');
    Route::post('removereview', 'ReviewController@removeReview')->name('removeReview');
    Route::post('sendreport', 'ReportController@sendReport')->name('sendreport');
    Route::post('addplace', 'PlaceController@addPlace')->name('addplace');
    Route::post('likereviews', 'ReviewController@favorite');
    Route::post('deleteimage', 'ReviewController@remove')->name('remove');
    Route::post('comment', 'ReviewController@comment');
    Route::post('updatecomment', 'ReviewController@updateComment');
    Route::post('deletecomment', 'ReviewController@deleteComment');
    Route::get('reviews/{id}/collection', 'ReviewController@addToCollection')->name('savecollection');
    Route::post('removecolection', 'CollectionController@removeConlection')->name('removecolection');
    Route::get('reviews/{id}/save/{collection_id}', 'CollectionController@save')->name('savereview');
    Route::get('showplace/{id}', 'PlaceController@showPlace')->name('showplace');
    Route::get('editplace/{id}', 'PlaceController@editPlace')->name('editplace');
    Route::post('uploadplace/{id}', 'PlaceController@uploadPlace')->name('uploadplace');
    Route::get('topweek', 'PlaceController@topWeek')->name('topweek');
    Route::get('category/{id}', 'CategoryController@cate')->name('cateShow');
    Route::group(['prefix' => 'user'], function () {
        Route::get('edit/{id}', 'UserController@edit')->name('editprofile');
        Route::post('edit/{id}', 'UserController@editProfile');
        Route::get('wall/{id}', 'UserController@myWall')->name('mywall');
        Route::get('collection/{id}', 'UserController@showCollection')->name('mycollection');
        Route::post('follow', 'UserController@follow')->name('follow');
        Route::post('unfollow', 'UserController@unFollow')->name('unFollow');
    });
    Route::group(['prefix' => 'notifications'], function () {
        Route::post('changestatus', 'NotificationController@changeStatus')->name('changestatus');
    });
});
Route::get('/get-places', 'SearchController@getPlaces');
Route::get('/get-dists', 'SearchController@getDists');
