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

Route::get('/privacy_policy', function () {
    return view('privacy_policy');
});
Route::get('/verify_email/{token}', 'App\Http\Controllers\CustomerController@verify_email');
/*Route::get('/', function () {
    return view('home');
});*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contact-us', function () {
    return view('contact');
});
Route::post('/contact', 'App\Http\Controllers\WebController@doRegister');

/* Dispatch */
Route::post('/find_trip', 'App\Http\Controllers\DispatchController@find_trip');
Route::post('/contact', 'App\Http\Controllers\WebController@doRegister');
Route::get('/customer_chat/{id}', "App\Http\Controllers\CustomerController@customer_chat");
Route::post('/save_polygon', "App\Http\Controllers\WebController@save_polygon");
Route::get('/dispatch_panel', "App\Http\Controllers\WebController@dispatch_panel");
Route::get('/create_zone/{id}/{capital_lat}/{capital_lng}', "App\Http\Controllers\WebController@create_zone");
Route::get('/paywithpaypal/{amount}', array('as' => 'paywithpaypal','uses' => 'App\Http\Controllers\PaypalController@payWithPaypal',));
Route::post('/paypal', array('as' => 'paypal','uses' => 'App\Http\Controllers\PaypalController@postPaymentWithpaypal',));
Route::get('/paypal', array('as' => 'status','uses' => 'App\Http\Controllers\PaypalController@getPaymentStatus',));
Route::get('/paypal_success',  "App\Http\Controllers\PaypalController@success");
Route::get('/paypal_failed',  "App\Http\Controllers\PaypalController@failed");