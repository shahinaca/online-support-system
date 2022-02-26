<?php

use Illuminate\Http\Request;
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
/**Protected URL */
Route::middleware('auth:sanctum')->group(function () {
    /** Auth Route */
    Route::get('/user', 'AuthController@getAuthenticatedUser');
    Route::get('/logout', 'AuthController@logout');

    Route::post('/search', 'EnquiryManageController@index');
    Route::post('/create-question', 'EnquiryManageController@createQuestion');

    Route::post('/reply/{enquiry_code}', 'EnquiryManageController@postAnswer');

    Route::post('/close-question/{enquiry_code}', 'EnquiryManageController@closeEnquiry');
    Route::post('/spam-report/{enquiry_code}', 'EnquiryManageController@spamReport');

    Route::get('/view-reply/{id}', 'EnquiryManageController@viewAnswer');
    Route::get('/all-replies/{enquiry_code}', 'EnquiryManageController@allreplies');
    Route::get('/view-question/{enquiry_code}', 'EnquiryManageController@viewQuestion');

});

/**Unprotected URL */
Route::post('login','AuthController@login');
Route::post('signup','AuthController@signup');
