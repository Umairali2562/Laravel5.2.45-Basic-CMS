<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('/zhc',function(){

    $permissions=Auth::user()->role->permission();
    /*foreach($permissions as $permission){
        echo $permission->name;
    }*/

    $ok=Auth::user()->role->hasAccess($permissions);
    //return $per
    //missions;
});




Route::group(['middleware'=>'Admin'],function(){

    Route::get('/admin',function(){
        return view('admin.index');
    });

    Route::resource('/admin/users','AdminUsersController');
    Route::resource('/admin/posts','AdminPostsController');
    Route::resource('/admin/permissions','UserPermissionsController');
    Route::resource('/admin/categories','AdminCategoriesController');
    Route::resource('/admin/media','AdminMediasController');
    //Route::get('/admin/media/upload',['as'=>'admin.media.upload','uses'=>'AdminMediasController@store']);


});

route::get('/ok',function(){

    return view('admin.users.ok');
});