<?php

use App\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Auth')->group(function () {

    //Login Routes
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    // Route::get('logout','LoginController@logout')->name('logout'); 
});


Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function () {
        /*----   Admin bill route ------*/
        Route::get('all-bill-request', 'BillRequestController@showForm');
        Route::post('all-bill-request', 'BillRequestController@billRequest')->name('bill.request');
        Route::post('all-bill-request-phoneno', 'BillRequestController@billRequestPhoneNo')->name('bill.request-phoneno');
        Route::post('all-bill-process', 'BillRequestController@billProcess');
        Route::get('all-bill-processed', 'BillRequestController@allProcessed');


        /*----   Admin data route ------*/
        Route::get('all-data-request', 'DataRequestController@showForm');
        Route::post('all-data-operator-request', 'DataRequestController@checkValue');
        Route::post('all-data-request', 'DataRequestController@dataRequest')->name('data.request');
        Route::post('all-data-request-phoneno', 'DataRequestController@dataRequestPhoneNo')->name('data.request-phoneno');
        Route::post('all-data-process', 'DataRequestController@dataProcess');
        Route::get('all-data-processed', 'DataRequestController@allProcessed');

        /*----- account route ------*/
        Route::get('fetch-account', 'AccountController@index');
        Route::get('create-account', 'AccountController@showForm');
        Route::post('create-account', 'AccountController@create')->name('create');

        Route::post('edit-account', 'AccountController@edit')->name('edit-account');
        Route::post('update-account', 'AccountController@update')->name('update-account');

        Route::get('all-batches', 'BatchController@allBatches');
    });

    Route::get('logout', 'Auth\LoginController@logout');




    /*----   User bill route ------*/
    Route::get('my-bill-request', 'UserBillRequestController@show');
    Route::post('my-bill-request', 'UserBillRequestController@billRequest')->name('my.bill.request');
    Route::post('my-bill-request-phoneno', 'UserBillRequestController@billRequestPhoneNo')->name('my.bill.request-phoneno');
    Route::post('my-bill-process', 'UserBillRequestController@billProcess');
    Route::get('my-bill-processed', 'UserBillRequestController@Processed');


    /*----   User data route ------*/
    Route::get('my-data-request', 'UserDataRequestController@show');
    Route::post('my-data-operator-request', 'UserDataRequestController@checkValue');
    Route::post('my-data-request', 'UserDataRequestController@dataRequest')->name('my.data.request');
    Route::post('my-data-request-phoneno', 'UserDataRequestController@dataRequestPhoneNo')->name('my.data.request-phoneno');
    Route::post('my-data-process', 'UserDataRequestController@dataProcess');
    Route::get('my-data-processed', 'UserDataRequestController@Processed');




    // Route::get('create-service', 'ServiceController@showForm');
    // Route::post('create-service', 'ServiceController@create')->name('service');
    // Route::get('added-service/{customer_id}', 'ServiceController@addedForm');
    // Route::post('added-service', 'ServiceController@addedService')->name('added.service');
    // Route::get('edit-service/{customer_id}/{service_id}', 'ServiceController@editForm');
    // Route::post('edit-service', 'ServiceController@editService')->name('edit');
    // Route::get('delete-service/{service_id}', 'ServiceController@deleteService');



    /*----- batch route ------*/
    Route::get('my-batches', 'BatchController@batches');

    Route::get('batch-export/{id}', 'BatchController@allExport');
    Route::get('batch-detail/{batch_id}', 'BatchController@batchDetail');


    /*-------------- retry route --------------------*/
    Route::post('retry-bill-process', 'RetryController@retryBillRequest');
    Route::post('retry-data-process', 'RetryController@retryDataRequest');



    /*-------------------filter------------------*/
    Route::post('bill-filter', 'BillRequestController@myFilter');
    Route::get('bill-filter', 'BillRequestController@myFilter');
    Route::post('data-filter', 'DataRequestController@myFilter');
    Route::get('data-filter', 'DataRequestController@myFilter');
    Route::post('all-bill-filter', 'BillRequestController@allFilter');
    Route::get('all-bill-filter', 'BillRequestController@allFilter');
    Route::post('all-data-filter', 'DataRequestController@allFilter');
    Route::get('all-data-filter', 'DataRequestController@allFilter');

    /*-------------------Sample Excel Download------------------*/
    Route::get('sample-excel-download', 'BillRequestController@sampleExcelDownload');


    // dashboard year search
    Route::post('ysearch', 'HomeController@ysearch');
});
Route::post('services', 'BillRequestController@getServices');
