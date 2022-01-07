<?php

use Illuminate\Support\Facades\Route;

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

  Route::get('/', function () {
                return redirect('admin-login');
            });
Route::get('/__clear__', function(){

            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('route:cache');
            Artisan::call('config:cache');
            Artisan::call('view:clear');



return 'cache cleard';
});

Route::post('forgot-password','App\Http\Controllers\UserController@reset_password')->name('forgot-password');
Route::get('forgot-password','App\Http\Controllers\UserController@reset_link')->name('reset-password');
Route::post('update-password','App\Http\Controllers\UserController@update')->name('update-password');
Route::get('change-password','App\Http\Controllers\UserController@update_form')->name('change-password');

Route::post('do_verify','App\Http\Controllers\UserController@do_verify')->name('do_verify');


Route::post('do_login','App\Http\Controllers\UserController@do_login')->name('do_login');
Route::get('admin-login','App\Http\Controllers\UserController@admin_login')->name('admin_login');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');




        Route::group(['middleware' => 'auth'], function () {
    	
    		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
    		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
    		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
    		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
    		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
    		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
    		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'App\Http\Controllers\PageController@upgrade']);
        
            
        });
Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

//  TransectionAlphaController
    Route::get('get-transactions-alpha','App\Http\Controllers\TransectionAlphaController@getTransactionsalpha')->name('getTransactionsalpha');
    Route::get('download_transections','App\Http\Controllers\TransectionAlphaController@download_excel')->name('download_excel');
    Route::get('upload_transection','App\Http\Controllers\TransectionAlphaController@upload_csv')->name('upload_csv');
    Route::get('delete_transections','App\Http\Controllers\TransectionAlphaController@delete_transections')->name('delete_transections');
    Route::post('import_transections','App\Http\Controllers\TransectionAlphaController@import_transections')->name('import_transections');
    
    
    Route::get('filter-transection','App\Http\Controllers\TransectionAlphaController@filterTransection')->name('filterTransection');
});


/** Ajmal Email Routes for making the emails auto and manul*/
Route::post('/is-email-allow','App\Http\Controllers\MailController@isEmailAllow');
Route::post('/manual-email-alpha','App\Http\Controllers\MailController@manualEmailAlpha');
Route::post('/upload-csv','App\Http\Controllers\MailController@uploadTransactionCSV')->name('uploadCsv');



// Daily Manual Email Route
Route::post('/daily-manual-email','App\Http\Controllers\MailController@dailyManualEmail')->name('dailyMail');
// Weekly Manual Email Route
Route::post('/weekly-manual-email','App\Http\Controllers\MailController@weeklyManualEmail')->name('weeklyMail');
// Monthly Manual Email Route
Route::post('/monthly-manual-email','App\Http\Controllers\MailController@monthlyManualEmail')->name('monthlyMail');


// for alpha upload data to manual email by
Route::post('/manual-email','App\Http\Controllers\MailController@manualEmail');
Route::get('download_transection','App\Http\Controllers\TransactionController@downloadExcelManual')->name('download_excel_file');
/** end */


// E-mail Alpha
Route::get('/daily_alpha_mails','App\Http\Controllers\MailController@daily_alpha_mails')->name('daily_alpha_mails');
Route::get('/weekly_alpha_mails','App\Http\Controllers\MailController@weekly_alpha_mails')->name('weekly_alpha_mails');
Route::get('/monthly_alpha_mails','App\Http\Controllers\MailController@monthly_mails')->name('monthly_alpha_mails');


