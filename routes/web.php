<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAccountController;

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

Route::group(['middleware' => 'IfUserNotLogin'], function () {
    Route::get('/', [UserAccountController::class, 'index'])->name('app.index');
    Route::post('/createUserAccount', [UserAccountController::class, 'createUserAccount'])->name('createUserAccount');
    Route::post('/loginUserAccount', [UserAccountController::class, 'loginUserAccount'])->name('loginUserAccount');
});

Route::group(['middleware' => 'IfUserLogin'], function () {

    Route::get('/logoutUserAccount', [UserAccountController::class, 'logoutUserAccount'])->name('logoutUserAccount');
    
    Route::group(['prefix' => 'myAccount'], function() {

        Route::get('/dashboard', [UserAccountController::class, 'quickDashBoard'])->name('quickDashBoard');
        
        /** UPDATE PROFILE */
        Route::get('/', [UserAccountController::class, 'myAccount'])->name('myAccount');
        Route::post('/updateProfile', [UserAccountController::class, 'updateProfile'])->name('updateProfile');
       
        /** CHANGE PASSWORD */
        Route::get('/changePassword', [UserAccountController::class, 'changePassword'])->name('changePassword');
        Route::post('/changePassword', [UserAccountController::class, 'changePasswordChange'])->name('changePasswordChange');

        /** PROFILE IMAGE */
        Route::get('/uploadProfileImage', [UserAccountController::class, 'uploadProfileImage'])->name('uploadProfileImage');
        Route::post('/uploadProfileImage', [UserAccountController::class, 'uploadProfileImageChange'])->name('uploadProfileImageChange');
        Route::post('/deleteProfileImage', [UserAccountController::class, 'deleteProfileImage'])->name('deleteProfileImage');
        
        /** BUSINESS LOGO */
        Route::get('/uploadBusinessLogo', [UserAccountController::class, 'uploadBusinessLogo'])->name('uploadBusinessLogo');
        Route::post('/uploadBusinessLogo', [UserAccountController::class, 'uploadBusinessLogoChange'])->name('uploadBusinessLogoChange');
        Route::post('/deleteBusinessLogo', [UserAccountController::class, 'deleteBusinessLogo'])->name('deleteBusinessLogo');

        /** CLIENTS */
        Route::group(['prefix' => 'clients'], function() {
            Route::get('/', [UserAccountController::class, 'myClients'])->name('myClients');
            Route::post('/bulkAction', [UserAccountController::class, 'clientListingBulkAction'])->name('clientListingBulkAction');
            Route::post('/delete', [UserAccountController::class, 'clientDelete'])->name('clientDelete');
            Route::get('/{id}/edit', [UserAccountController::class, 'clientEdit'])->name('clientEdit');
            Route::post('/{id}/edit', [UserAccountController::class, 'clientUpdate'])->name('clientUpdate');
            Route::get('/add', [UserAccountController::class, 'addNewClient'])->name('addNewClient');
            Route::post('/add', [UserAccountController::class, 'addNewClientSave'])->name('addNewClientSave');
            Route::get('/exportExcel', [UserAccountController::class, 'clientExportExcel'])->name('clientExportExcel');
            Route::post('/importExcel', [UserAccountController::class, 'clientImportExcel'])->name('clientImportExcel');
            Route::get('/importPdf', [UserAccountController::class, 'clientImportPdf'])->name('clientImportPdf');
        });
    });
});