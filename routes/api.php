<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EducationPageController;
use App\Http\Controllers\Api\HelpcenterPageController;
use App\Http\Controllers\Api\Auth\UserProfileController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InvestmentController;
use Illuminate\Contracts\Auth\UserProvider;

//health-check
Route::get("/check", function () {
    return "All right!... 👍";
});

//Guest user routes
Route::group(['middleware' => 'guest:api'], function () {

    // Login & Register
    Route::post('/login', [AuthenticationController::class, 'login']); // working
    Route::post('/register', [AuthenticationController::class, 'register']); // working
    Route::post('/verify-email', [AuthenticationController::class, 'verifyEmail']); // working
    Route::post('/resend-register-otp', [AuthenticationController::class, 'resendRegisterOtp']); // working

    // Password Reset
    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/resend-otp', [ResetPasswordController::class, 'resendOtp']);
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifyOTP']);
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);

    //help center page routes
    Route::get('/faq', [HelpcenterPageController::class, 'getFaqs']);

    //help center hero section route
    Route::get('/hero', [HelpcenterPageController::class, 'helpCenterHero']);

    //eudcation routes
    Route::get('/education/list', [EducationPageController::class, 'getEducationlist']); // working

    //pinned education
    Route::get('/pinned/education', [EducationPageController::class, 'pinnedEducation']); // working

    // investment list
   // working

    // filtering item
    Route::get('/investment-strategy', [InvestmentController::class, 'investmentStrategy']); // get asset class
    Route::get('/filter-data', [InvestmentController::class,'filterData']); // get country list
});

  Route::get('/investment-list', [InvestmentController::class, 'investmentList']);

Route::group(['middleware' => 'auth:jwt'], function () {
    //User logout
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    // user profile
    Route::get('/profile', [UserProfileController::class, 'profile']);
    Route::post('/update-profile', [UserProfileController::class, 'updateProfile']);
    Route::post('/update-password', [UserProfileController::class, 'updatePassword']);


    Route::get('/education/{id}', [EducationPageController::class, 'show']); // education details
    Route::get('/investment/{id}', [InvestmentController::class, 'show']); // investment details


    // dashboard stats
    Route::get('/dashboard/stats', [DashboardController::class, 'index']);
});
