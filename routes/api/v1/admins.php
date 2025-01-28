<?php


// Admins routes

use App\Http\Controllers\Api\V1\Admins\{AuthController, AdminsController, CategoriesController, FaqsController, SettingsController};
use Illuminate\Support\Facades\Route;

Route::prefix("admins")->name("adminsApi.")->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        // logout route
        Route::post('/logout', [AuthController::class, 'logout']);
        // admins route
        Route::apiResource('/admins', AdminsController::class);
        Route::apiResource('/faqs', FaqsController::class);
        Route::apiResource('/categories', CategoriesController::class);

        Route::get('/general-settings', [SettingsController::class, 'generalSettings']);
        Route::put('/general-settings', [SettingsController::class, 'updateGeneralSettings']);
        Route::get('/contacts-settings', [SettingsController::class, 'contactsSettings']);
        Route::put('/contacts-settings', [SettingsController::class, 'updateContactsSettings']);
    });
    // login
    Route::post('/login', [AuthController::class, 'login']);
});
