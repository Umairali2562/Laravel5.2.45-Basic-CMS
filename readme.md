Steps for upgrading 5.2.45 to 5.3:


The major difference in both of the Versions are of routes , The newer version has a seperate folder while the older has a file route.php


Step #1 trying to upgrade to 5.3 via composer command:

go ahead in the composer.json and edit the below code from this :

"require": {
        "php": ">=5.5.9",
        "laravel/framework": "5.2.*",
        "laravelcollective/html": "^5.2.0"
    },


to this :

"require": {
        "php": ">=5.5.9",
        "laravel/framework": "5.3.*",
        "laravelcollective/html": "^5.2.0"
    },

and then write and hit composer update to see what errors it would give you , My error :

  [ErrorException]
  Declaration of App\Providers\EventServiceProvider::boot(Illuminate\Contracts\Events\Dispatcher $events) should be compatible with Illuminate\Foundation\Suppor
  t\Providers\EventServiceProvider::boot()


Script php artisan optimize handling the post-update-cmd event returned with error code 1


let's resolve this in the next step :

Step#2 resolving errors coming from the composer:

Remove all the methods from the boot method in the "App\Providers\EventServiceProvider"

so as the existing code you'll see would be like this :

<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}



it becomes like this :


<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}


Now try composer update and you'd get the error of route service provider , just like below :


  [ErrorException]
  Declaration of App\Providers\RouteServiceProvider::boot(Illuminate\Routing\Router $router) should be compatible with Illuminate\Foundation\Support\Providers\R
  outeServiceProvider::boot()


Script php artisan optimize handling the post-update-cmd event returned with error code 1



so as the existing code of routeServiceProvider you'll see would be like this :



<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}








change the above to this :

Remove all parameters and replace this $router->group([ with this Route::group([ also include the Route class in the top of the code of the file.

use Illuminate\Support\Facades\Route;

also change this path :

require app_path('Http/routes.php'); to this

require base_path('routes/web.php');

also grabb the routes folder given and move it to the project folder that you are working on , do not put it in app folder (the link of the routes folder would be below the description  if not download a fresh copy of laravel 5.3 and get those files from there).


type composer update and hit enter.


and  valia ! you'd get no Error.





Step #3: copy the old routes to see if we get more errors or not:


Now as you see in the first step you got zero errors, but the time when you declare you routes from the previous route files , it's gonna give you a lot of errors.

Right now if you click the login button it would give you a 404 , or any other error if you haven't defined anything in the 404 page.


Putting the routes in the route list :

Route::get('/home', 'HomeController@index');

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





php artisan route:list ---> type this command and let's see what it's giving .


php artisan route:list
PHP Fatal error:  Trait 'Illuminate\Foundation\Auth\Access\AuthorizesResources' not found in C:\xampp\htdocs\zhc\um\Application\app\Http\Controllers\Controller.php
 on line 13


  [Symfony\Component\Debug\Exception\FatalErrorException]
  Trait 'Illuminate\Foundation\Auth\Access\AuthorizesResources' not found




This error says you have to modify the controller class :


the base controller class would look like this :


<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
}




remove the AuthorizesResources and it would become like this :




<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}



and then you get the output , yyayyy so it's working ...



php artisan route:list
+--------+-----------+-------------------------------------+---------------------+--------------------------------------------------------+------------+
| Domain | Method    | URI                                 | Name                | Action                                                 | Middleware |
+--------+-----------+-------------------------------------+---------------------+--------------------------------------------------------+------------+
|        | GET|HEAD  | /                                   |                     | Closure                                                | web        |
|        | GET|HEAD  | admin                               |                     | Closure                                                | web,Admin  |
|        | GET|HEAD  | admin/categories                    | categories.index    | App\Http\Controllers\AdminCategoriesController@index   | web,Admin  |
|        | POST      | admin/categories                    | categories.store    | App\Http\Controllers\AdminCategoriesController@store   | web,Admin  |
|        | GET|HEAD  | admin/categories/create             | categories.create   | App\Http\Controllers\AdminCategoriesController@create  | web,Admin  |
|        | DELETE    | admin/categories/{category}         | categories.destroy  | App\Http\Controllers\AdminCategoriesController@destroy | web,Admin  |
|        | PUT|PATCH | admin/categories/{category}         | categories.update   | App\Http\Controllers\AdminCategoriesController@update  | web,Admin  |
|        | GET|HEAD  | admin/categories/{category}         | categories.show     | App\Http\Controllers\AdminCategoriesController@show    | web,Admin  |
|        | GET|HEAD  | admin/categories/{category}/edit    | categories.edit     | App\Http\Controllers\AdminCategoriesController@edit    | web,Admin  |
|        | POST      | admin/media                         | media.store         | App\Http\Controllers\AdminMediasController@store       | web,Admin  |
|        | GET|HEAD  | admin/media                         | media.index         | App\Http\Controllers\AdminMediasController@index       | web,Admin  |
|        | GET|HEAD  | admin/media/create                  | media.create        | App\Http\Controllers\AdminMediasController@create      | web,Admin  |
|        | PUT|PATCH | admin/media/{medium}                | media.update        | App\Http\Controllers\AdminMediasController@update      | web,Admin  |
|        | GET|HEAD  | admin/media/{medium}                | media.show          | App\Http\Controllers\AdminMediasController@show        | web,Admin  |
|        | DELETE    | admin/media/{medium}                | media.destroy       | App\Http\Controllers\AdminMediasController@destroy     | web,Admin  |
|        | GET|HEAD  | admin/media/{medium}/edit           | media.edit          | App\Http\Controllers\AdminMediasController@edit        | web,Admin  |
|        | POST      | admin/permissions                   | permissions.store   | App\Http\Controllers\UserPermissionsController@store   | web,Admin  |
|        | GET|HEAD  | admin/permissions                   | permissions.index   | App\Http\Controllers\UserPermissionsController@index   | web,Admin  |
|        | GET|HEAD  | admin/permissions/create            | permissions.create  | App\Http\Controllers\UserPermissionsController@create  | web,Admin  |
|        | GET|HEAD  | admin/permissions/{permission}      | permissions.show    | App\Http\Controllers\UserPermissionsController@show    | web,Admin  |
|        | PUT|PATCH | admin/permissions/{permission}      | permissions.update  | App\Http\Controllers\UserPermissionsController@update  | web,Admin  |
|        | DELETE    | admin/permissions/{permission}      | permissions.destroy | App\Http\Controllers\UserPermissionsController@destroy | web,Admin  |
|        | GET|HEAD  | admin/permissions/{permission}/edit | permissions.edit    | App\Http\Controllers\UserPermissionsController@edit    | web,Admin  |
|        | GET|HEAD  | admin/posts                         | posts.index         | App\Http\Controllers\AdminPostsController@index        | web,Admin  |
|        | POST      | admin/posts                         | posts.store         | App\Http\Controllers\AdminPostsController@store        | web,Admin  |
|        | GET|HEAD  | admin/posts/create                  | posts.create        | App\Http\Controllers\AdminPostsController@create       | web,Admin  |
|        | GET|HEAD  | admin/posts/{post}                  | posts.show          | App\Http\Controllers\AdminPostsController@show         | web,Admin  |
|        | DELETE    | admin/posts/{post}                  | posts.destroy       | App\Http\Controllers\AdminPostsController@destroy      | web,Admin  |
|        | PUT|PATCH | admin/posts/{post}                  | posts.update        | App\Http\Controllers\AdminPostsController@update       | web,Admin  |
|        | GET|HEAD  | admin/posts/{post}/edit             | posts.edit          | App\Http\Controllers\AdminPostsController@edit         | web,Admin  |
|        | POST      | admin/users                         | users.store         | App\Http\Controllers\AdminUsersController@store        | web,Admin  |
|        | GET|HEAD  | admin/users                         | users.index         | App\Http\Controllers\AdminUsersController@index        | web,Admin  |
|        | GET|HEAD  | admin/users/create                  | users.create        | App\Http\Controllers\AdminUsersController@create       | web,Admin  |
|        | DELETE    | admin/users/{user}                  | users.destroy       | App\Http\Controllers\AdminUsersController@destroy      | web,Admin  |
|        | PUT|PATCH | admin/users/{user}                  | users.update        | App\Http\Controllers\AdminUsersController@update       | web,Admin  |
|        | GET|HEAD  | admin/users/{user}                  | users.show          | App\Http\Controllers\AdminUsersController@show         | web,Admin  |
|        | GET|HEAD  | admin/users/{user}/edit             | users.edit          | App\Http\Controllers\AdminUsersController@edit         | web,Admin  |
|        | GET|HEAD  | home                                |                     | App\Http\Controllers\HomeController@index              | web,auth   |
+--------+-----------+-------------------------------------+---------------------+--------------------------------------------------------+------------+








Step 4#: Defining the Login Route:


put this line in the route list:

Route::auth();

and write this in console:

php artisan route:list

or you can visit the login page to see what's it gonna be ..


Error :

php artisan route:list


  [ReflectionException]
  Class App\Http\Controllers\Auth\LoginController does not exist



so what you gotta do here is you need to create a loginController which you give you more headaches later so better that you grab a fresh copy of the controllers
from the site below:


https://laravel.com/docs/5.3/upgrade#upgrade-5.3.0

and it's done , working let's now move to the next step...



Step #5: Defining the logout ..


as we logged in now we wanna logout , but when we click on logout it gives us an error:

MethodNotAllowedHttpException in RouteCollection.php line 218:

write this in the web.php :

Route::get('/logout', 'Auth\LoginController@logout');


there ! now you are good to go ...







Step #6: Defining the name of the routes :

After the login section is done let's go ahead and type php artisan route:list ..



php artisan route:list
+--------+-----------+-------------------------------------+---------------------+--------------------------------------------------------+------------+
| Domain | Method    | URI                                 | Name                | Action                                                 | Middleware |
+--------+-----------+-------------------------------------+---------------------+--------------------------------------------------------+------------+
|        | GET|HEAD  | /                                   |                     | Closure                                                | web        |
|        | GET|HEAD  | admin                               |                     | Closure                                                | web,Admin  |
|        | GET|HEAD  | admin/categories                    | categories.index    | App\Http\Controllers\AdminCategoriesController@index   | web,Admin  |
|        | POST      | admin/categories                    | categories.store    | App\Http\Controllers\AdminCategoriesController@store   | web,Admin  |
|        | GET|HEAD  | admin/categories/create             | categories.create   | App\Http\Controllers\AdminCategoriesController@create  | web,Admin  |
|        | DELETE    | admin/categories/{category}         | categories.destroy  | App\Http\Controllers\AdminCategoriesController@destroy | web,Admin  |
|        | PUT|PATCH | admin/categories/{category}         | categories.update   | App\Http\Controllers\AdminCategoriesController@update  | web,Admin  |
|        | GET|HEAD  | admin/categories/{category}         | categories.show     | App\Http\Controllers\AdminCategoriesController@show    | web,Admin  |
|        | GET|HEAD  | admin/categories/{category}/edit    | categories.edit     | App\Http\Controllers\AdminCategoriesController@edit    | web,Admin  |
|        | POST      | admin/media                         | media.store         | App\Http\Controllers\AdminMediasController@store       | web,Admin  |
|        | GET|HEAD  | admin/media                         | media.index         | App\Http\Controllers\AdminMediasController@index       | web,Admin  |
|        | GET|HEAD  | admin/media/create                  | media.create        | App\Http\Controllers\AdminMediasController@create      | web,Admin  |
|        | PUT|PATCH | admin/media/{medium}                | media.update        | App\Http\Controllers\AdminMediasController@update      | web,Admin  |
|        | GET|HEAD  | admin/media/{medium}                | media.show          | App\Http\Controllers\AdminMediasController@show        | web,Admin  |
|        | DELETE    | admin/media/{medium}                | media.destroy       | App\Http\Controllers\AdminMediasController@destroy     | web,Admin  |
|        | GET|HEAD  | admin/media/{medium}/edit           | media.edit          | App\Http\Controllers\AdminMediasController@edit        | web,Admin  |
|        | POST      | admin/permissions                   | permissions.store   | App\Http\Controllers\UserPermissionsController@store   | web,Admin  |
|        | GET|HEAD  | admin/permissions                   | permissions.index   | App\Http\Controllers\UserPermissionsController@index   | web,Admin  |
|        | GET|HEAD  | admin/permissions/create            | permissions.create  | App\Http\Controllers\UserPermissionsController@create  | web,Admin  |
|        | GET|HEAD  | admin/permissions/{permission}      | permissions.show    | App\Http\Controllers\UserPermissionsController@show    | web,Admin  |
|        | PUT|PATCH | admin/permissions/{permission}      | permissions.update  | App\Http\Controllers\UserPermissionsController@update  | web,Admin  |
|        | DELETE    | admin/permissions/{permission}      | permissions.destroy | App\Http\Controllers\UserPermissionsController@destroy | web,Admin  |
|        | GET|HEAD  | admin/permissions/{permission}/edit | permissions.edit    | App\Http\Controllers\UserPermissionsController@edit    | web,Admin  |
|        | GET|HEAD  | admin/posts                         | posts.index         | App\Http\Controllers\AdminPostsController@index        | web,Admin  |
|        | POST      | admin/posts                         | posts.store         | App\Http\Controllers\AdminPostsController@store        | web,Admin  |
|        | GET|HEAD  | admin/posts/create                  | posts.create        | App\Http\Controllers\AdminPostsController@create       | web,Admin  |
|        | GET|HEAD  | admin/posts/{post}                  | posts.show          | App\Http\Controllers\AdminPostsController@show         | web,Admin  |
|        | DELETE    | admin/posts/{post}                  | posts.destroy       | App\Http\Controllers\AdminPostsController@destroy      | web,Admin  |
|        | PUT|PATCH | admin/posts/{post}                  | posts.update        | App\Http\Controllers\AdminPostsController@update       | web,Admin  |
|        | GET|HEAD  | admin/posts/{post}/edit             | posts.edit          | App\Http\Controllers\AdminPostsController@edit         | web,Admin  |
|        | POST      | admin/users                         | users.store         | App\Http\Controllers\AdminUsersController@store        | web,Admin  |
|        | GET|HEAD  | admin/users                         | users.index         | App\Http\Controllers\AdminUsersController@index        | web,Admin  |
|        | GET|HEAD  | admin/users/create                  | users.create        | App\Http\Controllers\AdminUsersController@create       | web,Admin  |
|        | DELETE    | admin/users/{user}                  | users.destroy       | App\Http\Controllers\AdminUsersController@destroy      | web,Admin  |
|        | PUT|PATCH | admin/users/{user}                  | users.update        | App\Http\Controllers\AdminUsersController@update       | web,Admin  |
|        | GET|HEAD  | admin/users/{user}                  | users.show          | App\Http\Controllers\AdminUsersController@show         | web,Admin  |
|        | GET|HEAD  | admin/users/{user}/edit             | users.edit          | App\Http\Controllers\AdminUsersController@edit         | web,Admin  |
|        | GET|HEAD  | home                                |                     | App\Http\Controllers\HomeController@index              | web,auth   |
+--------+-----------+-------------------------------------+---------------------+--------------------------------------------------------+------------+



and if you'd notice the admin routes above you would see that it is ommiting the admin from it check this 

admin/categories  ---------> categories.index


also if you'd login and try accessing the admin panel 


http://localhost/zhc/um/Application/public/admin , it's gonna give you an error of route names are not define ...

so we get to know that we gotta define route's name in it.


before moving any further change this  protected $redirectTo = '/home';

to this :

protected $redirectTo = '/admin';  in the LoginController.php 

so that you may redirect to the admin controller when you login .


so coming back to the admin name defining , what erorr we were getting while opening the admin panel ??

Route [admin.permissions.index] not defined. (View:


it means the error is in view let's go there , and here you'd see this line :



{{route('admin.permissions.index')}}

so it's saying that this line is not actually defined , and where we gotta define it ? 

in the web.php , where else ?


  Route::resource('/admin/permissions','UserPermissionsController',['names'=>[

        'index'=>'admin.permissions.index',
        'create'=>'admin.permissions.create',
        'store'=>'admin.permissions.store',
        'edit'=>'admin.permissions.edit'

    ]]);


and done , like this you gotta do that for every route...


the full web.php is here :


<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/logout','Auth\LoginController@logout');

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

    Route::resource('/admin/users','AdminUsersController',['names'=>[
        'index'=>'admin.users.index',
        'create'=>'admin.users.create',
        'store'=>'admin.users.store',
        'edit'=>'admin.users.edit'

    ]]);


    Route::resource('/admin/posts','AdminPostsController',['names'=>[

        'index'=>'admin.posts.index',
        'create'=>'admin.posts.create',
        'store'=>'admin.posts.store',
        'edit'=>'admin.posts.edit'


    ]]);

    Route::resource('/admin/categories','AdminCategoriesController',['names'=>[

        'index'=>'admin.categories.index',
        'create'=>'admin.categories.create',
        'store'=>'admin.categories.store',
        'edit'=>'admin.categories.edit'

    ]]);

    Route::resource('/admin/media','AdminMediasController',['names'=>[

        'index'=>'admin.media.index',
        'create'=>'admin.media.create',
        'store'=>'admin.media.store',
        'edit'=>'admin.media.edit'

    ]]);



    Route::resource('/admin/permissions','UserPermissionsController',['names'=>[

        'index'=>'admin.permissions.index',
        'create'=>'admin.permissions.create',
        'store'=>'admin.permissions.store',
        'edit'=>'admin.permissions.edit'

    ]]);


    // Route::resource('/admin/comments','PostsCommentsController');
   // Route::resource('/admin/comment/replies','CommentRepliesController');
    //Route::get('/admin/media/upload',['as'=>'admin.media.upload','uses'=>'AdminMediasController@store']);


});




Step#6: Replacing Lists() with pluck:


pluck does the same thing as lists so if you were using lists() function anywhere in your code or controller ,so in this case when we

try to edit users,posts,categories they all gives us error or query builder , this is because you must replace it with that.





Step #7: Upgrading middleware:

go in kernal.php and replace this 

'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,


with this :


'can' => \Illuminate\Auth\Middleware\Authorize::class,


this is all given in the guide ...

https://laravel.com/docs/5.3/upgrade#upgrade-5.3.0

also follow more below to upgrade it completely.
