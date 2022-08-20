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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// rutas de login y register
Route::post('/login', 'UserController@login')->name('user.login');
Route::post('/loginGoogle', 'UserController@login')->name('user.loginGoogle');
Route::post('/register', 'UserController@register')->name('user.register');
Route::post('/registerGoogle', 'UserController@registerGoogle')->name('user.registerGoogle');
Route::delete('/deleteToken', 'UserController@deleteToken')->name('user.deleteToken');

Route::delete('/destroysession/{idUser}', 'SessionController@destroySession')->name('delete.session');

Route::post('/sendEmailResetPassword', 'SessionController@sendEmailResetPassword')->name('reset.password');

Route::get('/password/{token}', 'SessionController@authorizePasswordChange')->name('get.authorizePasswordChange');


// rutas de admin
Route::group(['middleware' => 'auth:api'], function () {

    Route::put('/resetpassword', 'SessionController@putUserPassword')->name('put.resetpassword');
    
    Route::apiResource('/workers', 'WorkerController');
    Route::post('/putworkers/{workers}', 'WorkerController@update')->name('update.workers');
    
    Route::apiResource('/quotes', 'QuoteController');

    Route::apiResource('/services', 'ServiceController');
    Route::post('/putservices/{service}', 'ServiceController@update')->name('update.services');
    Route::get('/servicesall', 'ServiceController@servicesAll')->name('get.servicesall');
    
    Route::apiResource('/subservices', 'SubserviceController');
    Route::post('/putsubservices/{subservice}', 'SubserviceController@update')->name('update.subservices');
    Route::get('/subservicesall', 'SubserviceController@subservicesAll')->name('get.subservicesall');

    Route::apiResource('/users', 'UserController');
    Route::apiResource('/reviews', 'ReviewController');
    Route::apiResource('/typepayments', 'TypepaymentController');
    Route::apiResource('/payments', 'PaymentController');

    Route::get('/bookings', 'PaymentController@index')->name('reservas.index');
    Route::get('/bookings/{payment}', 'PaymentController@show')->name('reserva.show');

    //TOKEN ACTIONS
    // Route::get('/oauth/tokens', 'AuthorizedAccessTokenController@forUser');
    // Route::get('/oauth/clients', 'ClientController@forUser');
    // Route::get('/oauth/personal-access-tokens', 'PersonalAccessTokenController@forUser');



// Lista de citas del cliente   
    Route::get('/mysquotes/{idCustomer}', 'QuoteController@mysquotes')->name("ver.mis.citas"); 

// Lista de pagos del cliente
    Route::get('/myspayments/{idCustomer}', 'PaymentController@mysPayments')->name("mis.pagos"); 
    
});


// envio de emails
    Route::post('/emailapplicants', 'EmailController@store')->name('send.emails');


// rutas generales
    //TYPE-SERVICES
    Route::get('/typepaymentsweb', 'TypepaymentController@index')->name("ver.tipos.de.pagos"); 
    Route::get('/typepaymentsweb/{typepayment}', 'TypepaymentController@show')->name("ver.tipo.de.pago"); 

    //WORKERS
    Route::get('/workersweb', 'WorkerController@index')->name('workers.web');
    Route::get('/workersweb/{worker}', 'WorkerController@show')->name('workers.show');

    //SUB-SERVICES
    Route::get('/subservicesweb', 'SubserviceController@index')->name("subservices.web"); 
    Route::get('/subservicesweb/{subservice}', 'SubserviceController@show')->name("subservices.show"); 
    Route::get('/subservicesfilter/{idService}',  'SubserviceController@filter')->name("subservice.filter");

    //SERVICES
    Route::get('/servicesweb',  'ServiceController@index')->name("service.web");
    Route::get('/servicesweb/{service}',  'ServiceController@show')->name("service.show");



