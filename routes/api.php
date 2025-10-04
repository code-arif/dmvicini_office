<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EducationPageController;
use App\Http\Controllers\Api\HelpcenterPageController;
use App\Http\Controllers\Api\Auth\UserProfileController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\AuthenticationController;

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
    Route::get('/education/list', [EducationPageController::class, 'getEducationlist']);
    Route::get('/education/{id}', [EducationPageController::class, 'show']);

    //pinned education
    Route::get('/pinned/education', [EducationPageController::class, 'pinnedEducation']);
});


Route::group(['middleware' => 'auth:api'], function () {
    //User logout
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    // upload signature
    Route::post('/update-signature', [UserProfileController::class, 'updateSignature']);
});
