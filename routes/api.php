<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FooterController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\EducationPageController;
use App\Http\Controllers\Api\HelpcenterPageController;
use App\Http\Controllers\Api\Auth\UserProfileController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\InvestmentInterestController;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\CMS\CMSController;

//health-check
Route::get("/check", function () {
    return "All right!... 👍";
});

//Guest user routes
Route::group(['middleware' => 'guest:api'], function () {

    // Login & Register
    Route::post('/login', [LoginController::class, 'login']); // working

    Route::post('/register', [AuthenticationController::class, 'register']); // working

    // Password Reset
    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']); // working
    Route::post('/resend-otp', [ResetPasswordController::class, 'resendOtp']); // working
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifyOTP']); // working
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']); // working

    //help center page routes
    Route::get('/faq', [HelpcenterPageController::class, 'getFaqs']); //

    //help center hero section route
    Route::get('/hero', [HelpcenterPageController::class, 'helpCenterHero']);


    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);


    Route::get('/help-center', [CMSController::class, 'helpCenterPage']); // working - help center page
});



Route::group(['middleware' => 'auth:api'], function () {
    //User logout
    Route::post('/logout', [LoginController::class, 'logout']); // working

    // user profile
    Route::get('/profile', [UserProfileController::class, 'me']); // working
    Route::post('/update-profile', [UserProfileController::class, 'updateProfile']); // working
    Route::post('/update-password', [UserProfileController::class, 'updatePassword']); // working
    Route::post('/update-avatar', [UserProfileController::class, 'updateAvatar']); // working
    Route::post('/update-firm', [UserProfileController::class, 'updateFirm']); // working


    Route::get('/articles/list', [EducationPageController::class, 'getEducationlist']); // working - articles list
    Route::get('/article/{id}', [EducationPageController::class, 'show']); // working - education details
    Route::get('/pinned/article', [EducationPageController::class, 'pinnedEducation']); // working - pinned articles


    // investment list
    Route::get('/deals-list', [InvestmentController::class, 'investmentList']); // working
    Route::get('/deals/{id}', [InvestmentController::class, 'show']); // investment details

    // filtering item
    Route::get('/deals-strategy', [InvestmentController::class, 'investmentStrategy']); // get asset class
    Route::get('/filter-data', [InvestmentController::class, 'filterData']); // get country list


    Route::get('/categories', [EducationPageController::class, 'getCategories']); // categories list

    // footer data
    Route::get('/footer', [FooterController::class, 'index']);

    // dashboard stats
    Route::get('/dashboard/stats', [DashboardController::class, 'index']);
    Route::get('/dashboard/approved-deals', [DashboardController::class, 'approvedDeals']);

    // interested user
    Route::post('/investment/interested', [InvestmentInterestController::class, 'store']);
});
