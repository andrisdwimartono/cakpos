<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showFormRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
 
Route::group(['middleware' => 'auth'], function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user/editprofile', 'App\Http\Controllers\UserController@editprofile');
    Route::post('/updateprofile', 'App\Http\Controllers\UserController@updateprofile');
    Route::middleware(['checkauth'])->group(function () {
        //Route::get('admin', [AdminController::class, 'index']);
        Route::get('/menu', 'App\Http\Controllers\MenuController@index')->name('menu');
        Route::get('/menu', 'App\Http\Controllers\MenuController@index')->name('menu');
        Route::post('/getlistmenu', 'App\Http\Controllers\MenuController@get_list');
        Route::get('/menu/{menu}', 'App\Http\Controllers\MenuController@show');
        Route::get('/createmenu', 'App\Http\Controllers\MenuController@create');
        Route::post('/storemenu', 'App\Http\Controllers\MenuController@store');
        Route::get('/menu/{menu}/edit', 'App\Http\Controllers\MenuController@edit');
        Route::post('/getdatamenu', 'App\Http\Controllers\MenuController@getdata');
        Route::post('/updatemenu/{menu}', 'App\Http\Controllers\MenuController@update');
        Route::post('/deletemenu', 'App\Http\Controllers\MenuController@destroy');


        Route::get('/user', 'App\Http\Controllers\UserController@index')->name('user');
        Route::post('/getlistuser', 'App\Http\Controllers\UserController@get_list');
        Route::get('/user/{user}', 'App\Http\Controllers\UserController@show');
        Route::get('/createuser', 'App\Http\Controllers\UserController@create');
        Route::post('/storeuser', 'App\Http\Controllers\UserController@store');
        Route::get('/user/{user}/edit', 'App\Http\Controllers\UserController@edit');
        Route::post('/getdatauser', 'App\Http\Controllers\UserController@getdata');
        Route::post('/getoptionsuser', 'App\Http\Controllers\UserController@getoptions');
        Route::post('/updateuser/{user}', 'App\Http\Controllers\UserController@update');
        Route::post('/deleteuser', 'App\Http\Controllers\UserController@destroy');
        Route::post('/uploadfileuser', 'App\Http\Controllers\UserController@storeUploadFile');
        Route::get('/assignmenu/{user}/edit', 'App\Http\Controllers\UserController@assignmenu');
        Route::post('/assignmenu/{user}', 'App\Http\Controllers\UserController@update_assignmenu');
        Route::post('/getdataassignmenuuser', 'App\Http\Controllers\UserController@getdataassignmenuuser');
        Route::post('/getdataassignmenuuserrole', 'App\Http\Controllers\UserController@getdataassignmenuuserrole');
        Route::post('/updateassignmenuuser/{user}', 'App\Http\Controllers\UserController@updateassignmenu');
        Route::get('/chartusertotal', 'App\Http\Controllers\UserController@chartusertotal');
        Route::post('/getchartusertotal', 'App\Http\Controllers\UserController@getchartusertotal');

        Route::get('/company', 'App\Http\Controllers\CompanyController@index')->name('company');
        Route::post('/getlistcompany', 'App\Http\Controllers\CompanyController@get_list');
        Route::get('/company/{company}', 'App\Http\Controllers\CompanyController@show');
        Route::get('/createcompany', 'App\Http\Controllers\CompanyController@create');
        Route::post('/storecompany', 'App\Http\Controllers\CompanyController@store');
        Route::get('/company/{company}/edit', 'App\Http\Controllers\CompanyController@edit');
        Route::post('/getdatacompany', 'App\Http\Controllers\CompanyController@getdata');
        Route::post('/updatecompany/{company}', 'App\Http\Controllers\CompanyController@update');
        Route::post('/deletecompany', 'App\Http\Controllers\CompanyController@destroy');
        Route::post('/getlinkscompany', 'App\Http\Controllers\CompanyController@getlinks');

        Route::get('/uom', 'App\Http\Controllers\UomController@index')->name('uom');
        Route::post('/getlistuom', 'App\Http\Controllers\UomController@get_list');
        Route::get('/uom/{uom}', 'App\Http\Controllers\UomController@show');
        Route::get('/createuom', 'App\Http\Controllers\UomController@create');
        Route::post('/storeuom', 'App\Http\Controllers\UomController@store');
        Route::get('/uom/{uom}/edit', 'App\Http\Controllers\UomController@edit');
        Route::post('/getdatauom', 'App\Http\Controllers\UomController@getdata');
        Route::post('/updateuom/{uom}', 'App\Http\Controllers\UomController@update');
        Route::post('/deleteuom', 'App\Http\Controllers\UomController@destroy');

        Route::get('/category', 'App\Http\Controllers\CategoryController@index')->name('category');
        Route::post('/getlistcategory', 'App\Http\Controllers\CategoryController@get_list');
        Route::get('/category/{category}', 'App\Http\Controllers\CategoryController@show');
        Route::get('/createcategory', 'App\Http\Controllers\CategoryController@create');
        Route::post('/storecategory', 'App\Http\Controllers\CategoryController@store');
        Route::get('/category/{category}/edit', 'App\Http\Controllers\CategoryController@edit');
        Route::post('/getdatacategory', 'App\Http\Controllers\CategoryController@getdata');
        Route::post('/updatecategory/{category}', 'App\Http\Controllers\CategoryController@update');
        Route::post('/deletecategory', 'App\Http\Controllers\CategoryController@destroy');

        Route::get('/product', 'App\Http\Controllers\ProductController@index')->name('product');
        Route::post('/getlistproduct', 'App\Http\Controllers\ProductController@get_list');
        Route::get('/product/{product}', 'App\Http\Controllers\ProductController@show');
        Route::get('/createproduct', 'App\Http\Controllers\ProductController@create');
        Route::post('/storeproduct', 'App\Http\Controllers\ProductController@store');
        Route::get('/product/{product}/edit', 'App\Http\Controllers\ProductController@edit');
        Route::post('/getdataproduct', 'App\Http\Controllers\ProductController@getdata');
        Route::post('/updateproduct/{product}', 'App\Http\Controllers\ProductController@update');
        Route::post('/deleteproduct', 'App\Http\Controllers\ProductController@destroy');
        Route::post('/getoptionsproduct', 'App\Http\Controllers\ProductController@getoptions');
        Route::post('/getlinksproduct', 'App\Http\Controllers\ProductController@getlinks');
        Route::post('/uploadfileproduct', 'App\Http\Controllers\ProductController@storeUploadFile');

        Route::get('/warehouse', 'App\Http\Controllers\WarehouseController@index')->name('warehouse');
        Route::post('/getlistwarehouse', 'App\Http\Controllers\WarehouseController@get_list');
        Route::get('/warehouse/{warehouse}', 'App\Http\Controllers\WarehouseController@show');
        Route::get('/createwarehouse', 'App\Http\Controllers\WarehouseController@create');
        Route::post('/storewarehouse', 'App\Http\Controllers\WarehouseController@store');
        Route::get('/warehouse/{warehouse}/edit', 'App\Http\Controllers\WarehouseController@edit');
        Route::post('/getdatawarehouse', 'App\Http\Controllers\WarehouseController@getdata');
        Route::post('/updatewarehouse/{warehouse}', 'App\Http\Controllers\WarehouseController@update');
        Route::post('/deletewarehouse', 'App\Http\Controllers\WarehouseController@destroy');

        Route::get('/segment_level', 'App\Http\Controllers\Segment_levelController@index')->name('segment_level');
        Route::post('/getlistsegment_level', 'App\Http\Controllers\Segment_levelController@get_list');
        Route::get('/segment_level/{segment_level}', 'App\Http\Controllers\Segment_levelController@show');
        Route::get('/createsegment_level', 'App\Http\Controllers\Segment_levelController@create');
        Route::post('/storesegment_level', 'App\Http\Controllers\Segment_levelController@store');
        Route::get('/segment_level/{segment_level}/edit', 'App\Http\Controllers\Segment_levelController@edit');
        Route::post('/getdatasegment_level', 'App\Http\Controllers\Segment_levelController@getdata');
        Route::post('/updatesegment_level/{segment_level}', 'App\Http\Controllers\Segment_levelController@update');
        Route::post('/deletesegment_level', 'App\Http\Controllers\Segment_levelController@destroy');

        Route::get('/member_level', 'App\Http\Controllers\Member_levelController@index')->name('member_level');
        Route::post('/getlistmember_level', 'App\Http\Controllers\Member_levelController@get_list');
        Route::get('/member_level/{member_level}', 'App\Http\Controllers\Member_levelController@show');
        Route::get('/createmember_level', 'App\Http\Controllers\Member_levelController@create');
        Route::post('/storemember_level', 'App\Http\Controllers\Member_levelController@store');
        Route::get('/member_level/{member_level}/edit', 'App\Http\Controllers\Member_levelController@edit');
        Route::post('/getdatamember_level', 'App\Http\Controllers\Member_levelController@getdata');
        Route::post('/updatemember_level/{member_level}', 'App\Http\Controllers\Member_levelController@update');
        Route::post('/deletemember_level', 'App\Http\Controllers\Member_levelController@destroy');

        Route::get('/customer', 'App\Http\Controllers\CustomerController@index')->name('customer');
        Route::post('/getlistcustomer', 'App\Http\Controllers\CustomerController@get_list');
        Route::get('/customer/{customer}', 'App\Http\Controllers\CustomerController@show');
        Route::get('/createcustomer', 'App\Http\Controllers\CustomerController@create');
        Route::post('/storecustomer', 'App\Http\Controllers\CustomerController@store');
        Route::get('/customer/{customer}/edit', 'App\Http\Controllers\CustomerController@edit');
        Route::post('/getdatacustomer', 'App\Http\Controllers\CustomerController@getdata');
        Route::post('/updatecustomer/{customer}', 'App\Http\Controllers\CustomerController@update');
        Route::post('/deletecustomer', 'App\Http\Controllers\CustomerController@destroy');
        Route::post('/getlinkscustomer', 'App\Http\Controllers\CustomerController@getlinks');
        Route::post('/uploadfilecustomer', 'App\Http\Controllers\CustomerController@storeUploadFile');
    });
});



